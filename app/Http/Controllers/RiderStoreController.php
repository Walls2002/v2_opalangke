<?php

namespace App\Http\Controllers;

use App\Enums\OrderStatus;
use App\Http\Resources\StoreOrderResource;
use App\Models\Order;
use App\Models\Rider;
use App\Models\RiderStore;
use App\Models\Store;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class RiderStoreController extends Controller
{
    /**
     * Get all the riders added by in this stores location.
     *
     * @param Request $request
     * @param Store $store
     * @return JsonResponse
     */
    public function indexLocal(Request $request, Store $store): JsonResponse
    {
        if ($request->user()->id !== $store->vendor_id) {
            return response()->json(['message' => 'Unauthorized.'], 403);
        }

        $storeRiders = Rider::with(['user'])
            ->whereRelation('user', 'location_id', '=', $store->location_id)
            ->where('is_active', true)
            ->get();

        return response()->json([
            'message' => 'Local riders fetched.',
            'store_riders' => $storeRiders,
        ]);
    }

    /**
     * Get all the riders added by this store as part of their team.
     *
     * @param Request $request
     * @param Store $store
     * @return JsonResponse
     */
    public function index(Request $request, Store $store): JsonResponse
    {
        if ($request->user()->id !== $store->vendor_id) {
            return response()->json(['message' => 'Unauthorized.'], 403);
        }

        $storeRiders = Rider::with(['user'])
            ->whereRelation('riderStores', 'store_id', '=', $store->id)
            ->where('is_active', true)
            ->get();

        return response()->json([
            'message' => 'Store riders fetched.',
            'store_riders' => $storeRiders,
        ]);
    }

    /**
     * Register a rider to this store and add them as a part of their team.
     *
     * @param Request $request
     * @param Store $store
     * @return JsonResponse
     */
    public function storeRegister(Request $request, Store $store): JsonResponse
    {
        if ($request->user()->id !== $store->vendor_id) {
            return response()->json(['message' => 'Unauthorized.'], 403);
        }

        $request->validate([
            'last_name' => 'required|string|max:50',
            'first_name' => 'required|string|max:50',
            'middle_name' => 'required|string|max:50',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8',
            'contact' => 'nullable|string|max:15',
            'license_number' => 'required|string|max:15|unique:riders,license_number',
            'plate_number' => 'required|string|max:15|unique:riders,plate_number',
        ]);

        $futureRider = User::create([
            'location_id' => $store->location_id,
            'last_name' => $request->last_name,
            'first_name' => $request->first_name,
            'middle_name' => $request->middle_name,
            'email' => $request->email,
            'password' => $request->password,
            'contact' => $request->contact,
            'role' => 'rider',
            'email_verified_at' => null,
        ]);

        $futureRider->rider = Rider::create([
            'user_id' => $futureRider->id,
            'license_number' => $request->license_number,
            'plate_number' => $request->plate_number,
        ]);

        $riderStore = RiderStore::create([
            'rider_id' => $futureRider->rider->id,
            'store_id' => $store->id,
        ]);

        return response()->json([
            'message' => 'Rider created, waiting for admin verification.',
            'store_rider' => $riderStore,
        ]);
    }

    /**
     * Add a rider to this store as part of their team.
     *
     * @param Request $request
     * @param Store $store
     * @return JsonResponse
     */
    public function store(Request $request, Store $store): JsonResponse
    {
        if ($request->user()->id !== $store->vendor_id) {
            return response()->json(['message' => 'Unauthorized.'], 403);
        }

        $request->validate([
            'user_email' => ['required', 'exists:users,email']
        ]);

        $futureRider = User::with(['rider'])->where('email', $request->user_email)->first();
        if (!$futureRider || $futureRider->role !== 'rider' || $futureRider->rider === null) {
            return response()->json([
                'message' => 'Invalid user, the user must be a registered rider.'
            ]);
        }

        if (!$futureRider->rider->is_active) {
            return response()->json([
                'message' => 'Invalid user, the user must be a an active rider.'
            ]);
        }

        if ($futureRider->location_id !== $store->location_id) {
            return response()->json([
                'message' => 'Invalid rider, the rider and store must be on the same location.'
            ]);
        }

        if (RiderStore::where('rider_id', $futureRider->rider->id)->where('store_id', $store->id)->exists()) {
            return response()->json([
                'message' => 'Rider is already part of the team of this store.'
            ]);
        }

        $riderStore = RiderStore::create([
            'rider_id' => $futureRider->rider->id,
            'store_id' => $store->id,
        ]);

        return response()->json([
            'message' => 'Rider added to store riders team.',
            'store_rider' => $riderStore,
        ]);
    }

    /**
     * Remove a rider to this store as part of their team.
     *
     * @param Request $request
     * @param RiderStore $riderStore
     * @return JsonResponse
     */
    public function destroy(Request $request, RiderStore $riderStore): JsonResponse
    {
        $riderStore->load('store');

        if ($request->user()->id !== $riderStore->store->vendor_id) {
            return response()->json(['message' => 'Unauthorized.'], 403);
        }

        $riderStore->delete();

        return response()->json(['message' => 'Rider removed from store team.'], 200);
    }

    /**
     * View all the orders of a rider from the vendor.
     *
     * @param Request $request
     * @param Store $store
     * @return JsonResponse
     */
    public function riderOrders(Request $request, Store $store): JsonResponse
    {
        if ($request->user()->id !== $store->vendor_id) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $request->validate([
            'rider_store_id' => ['required', 'exists:rider_stores,id']
        ]);

        $riderStore = RiderStore::find($request->rider_store_id);

        $query = Order::query()
            ->with(['items', 'user', 'rider'])
            ->where('store_id', $store->id)
            ->where('rider_id', $riderStore->rider_id);

        $selectedStatus = [];
        if ($request->boolean('show_pending')) {
            $selectedStatus[] = OrderStatus::PENDING;
        }

        if ($request->boolean('show_confirmed')) {
            $selectedStatus[] = OrderStatus::CONFIRMED;
        }

        if ($request->boolean('show_dispatched')) {
            $selectedStatus[] = OrderStatus::DISPATCHED;
        }

        if ($request->boolean('show_assigned')) {
            $selectedStatus[] = OrderStatus::ASSIGNED;
        }

        if ($request->boolean('show_delivered')) {
            $selectedStatus[] = OrderStatus::DELIVERED;
        }

        if ($request->boolean('show_canceled')) {
            $selectedStatus[] = OrderStatus::CANCELED;
        }

        $query->whereIn('status', $selectedStatus);
        $query->orderBy('created_at', 'desc');

        $data = $query->get();

        return response()->json(['message' => 'Rider vendor orders fetched.', 'orders' => StoreOrderResource::collection($data)], 200);
    }
}
