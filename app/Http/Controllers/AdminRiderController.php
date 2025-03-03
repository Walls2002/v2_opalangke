<?php

namespace App\Http\Controllers;

use App\Http\Resources\RiderOrderResource;
use App\Models\Order;
use App\Models\Rider;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class AdminRiderController extends Controller
{
    /**
     * Show all the riders.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function index(Request $request): JsonResponse
    {
        if ($request->user()->role !== 'admin') {
            return response()->json(
                data: ['message' => 'Unauthorized.'],
                status: 422,
            );
        }

        $riders = User::query()
            ->where('role', 'rider')
            ->with(['rider'])
            ->get();

        return response()->json(['riders' => $riders]);
    }

    /**
     * Show the rider.
     *
     * @param Request $request
     * @param Rider $rider
     * @return JsonResponse
     */
    public function show(Request $request, Rider $rider): JsonResponse
    {
        if ($request->user()->role !== 'admin') {
            return response()->json(
                data: ['message' => 'Unauthorized.'],
                status: 422,
            );
        }

        $rider->load(['user']);

        return response()->json(['rider' => $rider]);
    }

    /**
     * View all the orders of the rider.
     *
     * @param Request $request
     * @param Rider $rider
     * @return JsonResponse
     */
    public function showOrders(Request $request, Rider $rider): JsonResponse
    {
        if ($request->user()->role !== 'admin') {
            return response()->json(
                data: ['message' => 'Unauthorized.'],
                status: 422,
            );
        }

        $data = Order::query()
            ->with(['items', 'user', 'userVoucher.voucher'])
            ->where('rider_id', $rider->id)
            ->orderBy('created_at', 'asc')
            ->get();

        return response()->json(['message' => 'Rider assigned orders fetched.', 'orders' => RiderOrderResource::collection($data)], 200);
    }
}
