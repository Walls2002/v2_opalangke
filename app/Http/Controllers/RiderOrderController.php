<?php

namespace App\Http\Controllers;

use App\Enums\OrderStatus;
use App\Http\Resources\RiderOrderResource;
use App\Models\Order;
use App\Models\Rider;
use App\Models\RiderStore;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class RiderOrderController extends Controller
{
    /**
     * View all the available orders the rider can take in the local location pool.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function localOrders(Request $request): JsonResponse
    {
        $rider = $request->user();
        if ($rider->role !== 'rider') {
            return response()->json(['message' => 'You are not a rider.'], 403);
        }

        $data = Order::query()
            ->with(['items', 'store', 'user', 'userVoucher.voucher'])
            ->where('rider_team_only', false)
            ->where('status', OrderStatus::DISPATCHED)
            ->whereRelation('store', 'location_id', '=', $rider->location_id)
            ->orderBy('created_at', 'asc')
            ->get();

        return response()->json(['message' => 'Available local orders fetched.', 'orders' => RiderOrderResource::collection($data)], 200);
    }

    /**
     * View all the available orders the rider can take in the rider's team pool.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function teamOrders(Request $request): JsonResponse
    {
        $rider = $request->user();
        if ($rider->role !== 'rider') {
            return response()->json(['message' => 'You are not a rider.'], 403);
        }

        $rider->load('rider');

        $riderStoreIds = RiderStore::where('rider_id', $rider->rider->id)->get(['store_id']);

        $data = Order::query()
            ->with(['items', 'store', 'user', 'userVoucher.voucher'])
            ->whereIn('store_id', $riderStoreIds)
            ->where('rider_team_only', true)
            ->where('status', OrderStatus::DISPATCHED)
            ->whereRelation('store', 'location_id', '=', $rider->location_id)
            ->orderBy('created_at', 'asc')
            ->get();

        return response()->json(['message' => 'Available team orders fetched.', 'orders' => RiderOrderResource::collection($data)], 200);
    }

    /**
     * View all the orders assigned to the rider.
     *
     * @param Request $request
     * @param Rider $rider
     * @return JsonResponse
     */
    public function index(Request $request): JsonResponse
    {
        $user = $request->user();
        if ($user->role !== 'rider') {
            return response()->json(['message' => 'You are not a rider.'], 403);
        }

        $user->load(['rider']);

        $data = Order::query()
            ->with(['items', 'user', 'userVoucher.voucher'])
            ->where('rider_id', $user->rider->id)
            ->where('status', OrderStatus::ASSIGNED)
            ->orderBy('created_at', 'asc')
            ->get();

        return response()->json(['message' => 'Rider assigned orders fetched.', 'orders' => RiderOrderResource::collection($data)], 200);
    }

    /**
     * Show the order assigned to the rider.
     *
     * @param Request $request
     * @param Order $order
     * @return JsonResponse
     */
    public function show(Request $request, Order $order): JsonResponse
    {
        $rider = $request->user();
        if ($rider->role !== 'rider') {
            return response()->json(['message' => 'You are not a rider.'], 403);
        }

        $order->load(['items', 'user', 'store', 'rider']);

        if ($rider->id != $order->rider->user_id) {
            return response()->json(['message' => 'You are not the assigned rider to this order.'], 403);
        }

        return response()->json(['message' => 'Order fetched.', 'order' => new RiderOrderResource($order)], 200);
    }

    /**
     * Take the order for delivery.
     *
     * @param Request $request
     * @param Order $order
     * @return JsonResponse
     */
    public function take(Request $request, Order $order): JsonResponse
    {
        $rider = $request->user();
        $rider->load('rider');
        $order->load(['items', 'user', 'store']);

        if ($rider->is_active === false) {
            return response()->json(['message' => 'Your rider account is deactivated, you can not take orders.'], 422);
        }

        if ($order->rider_team_only) {
            if (!RiderStore::where('rider_id', $rider->rider->id)->where('store_id', $order->store->id)->exists()) {
                return response()->json(['message' => 'You are not part of the rider team of this store.'], 403);
            }
        }

        if ($order->status != OrderStatus::DISPATCHED) {
            return response()->json(['message' => 'You can only take dispatched orders.'], 422);
        }

        if ($order->rider_id) {
            return response()->json(['message' => 'This order is already taken by another rider.'], 422);
        }

        $order->rider_id = $rider->rider->id;
        $order->status = OrderStatus::ASSIGNED;

        if (!$order->save()) {
            return response()->json(['message' => 'Encountered an error taking the order.'], 400);
        }

        return response()->json(['message' => 'Order taken fetched.', 'order' => new RiderOrderResource($order)], 200);
    }

    /**
     * Deliver the order.
     *
     * @param Request $request
     * @param Order $order
     * @return JsonResponse
     */
    public function store(Request $request, Order $order): JsonResponse
    {
        $rider = $request->user();
        if ($rider->role !== 'rider') {
            return response()->json(['message' => 'You are not a rider.'], 403);
        }

        if ($rider->rider->id !== $order->rider_id) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        if ($order->status != OrderStatus::ASSIGNED) {
            return response()->json(['message' => 'You can only deliver assigned status orders.'], 422);
        }

        $request->validate([
            'image' => ['required', 'image', 'mimes:jpeg,png,jpg', 'max:2048'],
        ]);

        DB::beginTransaction();
        try {
            if ($request->hasFile('image')) {
                $filePath = $data['image'] = $request->file('image')->store('deliveries', 'public');
            }

            $order->status = OrderStatus::DELIVERED;
            $order->delivered_at = now();
            $order->delivery_image = $filePath ?? null;

            if (!$order->save()) {
                throw new \Exception('Order updated failed.');
            }

            DB::commit();
        } catch (\Throwable $th) {
            DB::rollBack();
            return response()->json(['message' => 'Encountered an error delivering the order.'], 400);
        }

        return response()->json(['message' => 'Order delivered.', 'order' => $order], 200);
    }

    /**
     * Cancel the order by rider.
     *
     * @param Request $request
     * @param Order $order
     * @return JsonResponse
     */
    public function cancel(Request $request, Order $order): JsonResponse
    {
        $rider = $request->user();
        if ($rider->role !== 'rider') {
            return response()->json(['message' => 'You are not a rider.'], 403);
        }
        if ($rider->rider->id !== $order->rider_id) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }
        if ($order->status != OrderStatus::ASSIGNED) {
            return response()->json(['message' => 'You can only cancel assigned status orders.'], 422);
        }
        $order->status = OrderStatus::CANCELED;
        // $order->canceled_at = now();
        if (!$order->save()) {
            return response()->json(['message' => 'Failed to cancel order.'], 400);
        }
        return response()->json(['message' => 'Order canceled successfully.'], 200);
    }
}
