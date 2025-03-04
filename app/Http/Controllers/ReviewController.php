<?php

namespace App\Http\Controllers;

use App\Enums\OrderStatus;
use App\Models\OrderItem;
use App\Models\OrderItemReview;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ReviewController extends Controller
{
    /**
     * Create a review on a product order.
     *
     * @param Request $request
     * @param OrderItem $orderItem
     * @return JsonResponse
     */
    public function store(Request $request, OrderItem $orderItem): JsonResponse
    {
        $orderItem->load(['product', 'order', 'orderItemReview']);

        if ($request->user()->id !== $orderItem->order->user_id) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        if ($orderItem->orderItemReview) {
            return response()->json(['message' => 'Already reviewed'], 400);
        }

        if ($orderItem->order->status !== OrderStatus::DELIVERED) {
            return response()->json(['message' => 'Order is not yet delivered.'], 400);
        }

        $request->validate([
            'stars' => ['required', 'in:1,2,3,4,5'],
        ]);

        $review = OrderItemReview::create([
            'order_item_id' => $orderItem->id,
            'stars' => $request->stars,
        ]);

        return response()->json([
            'message' => 'Review created.',
            'review' => $review,
        ], 200);
    }
}