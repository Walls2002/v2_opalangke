<?php

namespace App\Http\Controllers;

use App\Enums\OrderStatus;
use App\Http\Resources\StoreOrderResource;
use App\Models\Order;
use App\Models\Rider;
use App\Models\Store;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class VendorOrderController extends Controller
{
    /**
     * View all the orders of the vendor.
     *
     * @param Request $request
     * @param Store $store
     * @return JsonResponse
     */
    public function index(Request $request, Store $store): JsonResponse
    {
        if ($request->user()->id !== $store->vendor_id) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $query = Order::query()
            ->with(['items', 'user', 'rider.user', 'userVoucher.voucher'])
            ->where('store_id', $store->id);

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

        return response()->json(['message' => 'Vendor orders fetched.', 'orders' => StoreOrderResource::collection($data)], 200);
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
        if ($request->user()->id !== $order->store->vendor_id) {
            return response()->json(['message' => 'Unauthorized.'], 403);
        }

        $order->load(['items', 'user', 'store', 'rider.user', 'userVoucher.voucher']);

        return response()->json(['message' => 'Order fetched.', 'order' => new StoreOrderResource($order)], 200);
    }

    /**
     * Cancel/Decline the order.
     *
     * @param Request $request
     * @param Order $order
     * @return JsonResponse
     */
    public function cancel(Request $request, Order $order): JsonResponse
    {
        if ($request->user()->id !== $order->store->vendor_id) {
            return response()->json(['message' => 'Unauthorized.'], 403);
        }

        $order->status = OrderStatus::CANCELED;
        if (!$order->save()) {
            return response()->json(['message' => 'Encountered an error updating the order status.'], 400);
        }

        return response()->json(['message' => 'Order canceled.'], 200);
    }

    /**
     * Confirm the order.
     *
     * @param Request $request
     * @param Order $order
     * @return JsonResponse
     */
    public function confirm(Request $request, Order $order): JsonResponse
    {
        if ($request->user()->id !== $order->store->vendor_id) {
            return response()->json(['message' => 'Unauthorized.'], 403);
        }

        if ($order->status != OrderStatus::PENDING) {
            return response()->json(['message' => 'You can only confirm pending orders.'], 422);
        }

        $order->status = OrderStatus::CONFIRMED;
        if (!$order->save()) {
            return response()->json(['message' => 'Encountered an error updating the order status.'], 400);
        }

        return response()->json(['message' => 'Order confirmed.'], 200);
    }

    /**
     * Dispatch the order.
     *
     * Dispatch it to only the store's rider pool or the location rider pool.
     *
     * @param Request $request
     * @param Order $order
     * @return JsonResponse
     */
    public function dispatchOrder(Request $request, Order $order): JsonResponse
    {
        if ($request->user()->id !== $order->store->vendor_id) {
            return response()->json(['message' => 'Unauthorized.'], 403);
        }

        if (!in_array($order->status, [OrderStatus::CONFIRMED, OrderStatus::DISPATCHED])) {
            return response()->json(['message' => 'You can only dispatch confirmed or dispatched orders.'], 422);
        }

        $request->validate([
            'rider_team_only' => ['required', 'boolean'],
        ]);

        $order->status = OrderStatus::DISPATCHED;
        $order->rider_team_only = $request->boolean('rider_team_only');
        if (!$order->save()) {
            return response()->json(['message' => 'Encountered an error dispatching the order.'], 400);
        }

        return response()->json(['message' => 'Order dispatched.', 'order' => $order], 200);
    }
}
