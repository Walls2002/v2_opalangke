<?php

namespace App\Http\Controllers;

use App\Enums\OrderStatus;
use App\Models\Order;
use App\Models\ReviewRider;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class RiderReviewController extends Controller
{
    /**
     * Create a review on a rider of an order.
     *
     * @param Request $request
     * @param Order $order
     * @return JsonResponse
     */
    public function store(Request $request, Order $order): JsonResponse
    {
        if ($request->user()->id !== $order->user_id) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $order->load(['riderReview']);

        if ($order->riderReview) {
            return response()->json(['message' => 'Rider already reviewed'], 400);
        }

        if ($order->status !== OrderStatus::DELIVERED) {
            return response()->json(['message' => 'Order is not yet delivered.'], 400);
        }

        $request->validate([
            'stars' => ['required', 'in:1,2,3,4,5'],
        ]);

        $review = ReviewRider::create([
            'order_id' => $order->id,
            'rider_id' => $order->rider_id,
            'stars' => $request->stars,
        ]);

        return response()->json([
            'message' => 'Rider review created.',
            'review' => $review,
        ], 200);
    }
}
