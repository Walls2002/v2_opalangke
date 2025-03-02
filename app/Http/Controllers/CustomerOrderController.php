<?php

namespace App\Http\Controllers;

use App\Enums\OrderStatus;
use App\Http\Resources\CustomerOrderResource;
use App\Models\Order;
use App\Models\Rider;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class CustomerOrderController extends Controller
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
        $query = Order::query()
            ->with(['items', 'user', 'rider', 'store', 'userVoucher.voucher'])
            ->where('user_id', $request->user()->id);

        $selectedStatus = [];
        if ($request->boolean('show_pending')) {
            $selectedStatus[] = OrderStatus::PENDING;
        }

        if ($request->boolean('show_confirmed')) {
            $selectedStatus[] = OrderStatus::CONFIRMED;
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

        return response()->json(['message' => 'Customer orders fetched.', 'orders' => CustomerOrderResource::collection($data)], 200);
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
        if ($request->user()->id !== $order->user_id) {
            return response()->json(['message' => 'Unauthorized.'], 403);
        }

        $order->load(['items', 'user', 'store']);

        return response()->json(['message' => 'Order fetched.', 'order' => new CustomerOrderResource($order)], 200);
    }
}