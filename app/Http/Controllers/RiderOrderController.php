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
     * View all the orders assigned to the rider.
     *
     * @param Request $request
     * @param Rider $rider
     * @return JsonResponse
     */
    public function index(Request $request): JsonResponse
    {
        $rider = $request->user();
        if ($rider->role !== 'rider') {
            return response()->json(['message' => 'You are not a rider.'], 403);
        }

        $data = Order::query()
            ->with(['items', 'user', 'userVoucher.voucher'])
            ->where('rider_id', $rider->id)
            ->where('status', OrderStatus::ASSIGNED)
            ->orderBy('created_at', 'asc')
            ->get();

        return response()->json(['message' => 'Rider assigned orders fetched.', 'orders' => RiderOrderResource::collection($data)], 200);
    }

    /**
     * Show the order.
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

        $order->load(['items', 'user', 'store']);

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
        $order->load(['items', 'user', 'store']);

        if ($order->rider_team_only) {
            if (!RiderStore::where('rider_id', $rider->id)->where('store_id', $order->store->id)->exists()) {
                return response()->json(['message' => 'You are not part of the rider team of this store.'], 403);
            }
        }

        if ($order->status != OrderStatus::DISPATCHED) {
            return response()->json(['message' => 'You can only take dispatched orders.'], 422);
        }

        if ($order->rider_id) {
            return response()->json(['message' => 'This order is already taken by another rider.'], 422);
        }

        $order->rider_id = $rider->id;
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

        if ($rider->id !== $order->rider_id) {
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
}