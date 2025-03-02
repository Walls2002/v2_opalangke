<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreVoucherRequest;
use App\Http\Requests\UpdateVoucherRequest;
use App\Models\Voucher;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class VoucherController extends Controller
{
    /**
     * Show all the vouchers for the admins views.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function index(Request $request): JsonResponse
    {
        if (!$request->user()->role === 'admin') {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $vouchers = Voucher::where('is_deleted', false)->latest()->get();

        return response()->json([
            'message' => 'Vouchers fetched',
            'vouchers' => $vouchers,
        ], 200);
    }

    /**
     * Show a voucher for the admins view.
     *
     * @param Request $request
     * @param Voucher $voucher
     * @return JsonResponse
     */
    public function show(Request $request, Voucher $voucher): JsonResponse
    {
        if (!$request->user()->role === 'admin') {
            return response()->json(['message' => 'Unauthorized'], 403);
        }
        return response()->json([
            'message' => 'Voucher found',
            'voucher' => $voucher,
        ], 200);
    }

    /**
     * Create a voucher.
     *
     * @param StoreVoucherRequest $request
     * @return JsonResponse
     */
    public function store(StoreVoucherRequest $request): JsonResponse
    {
        if (!$request->user()->role === 'admin') {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $voucher = Voucher::create([
            'code' => $request->code,
            'min_order_price' => $request->min_order_price,
            'value' => $request->value,
            'description' => $request->description,
            'is_percent' => $request->is_percent,
            'is_deleted' => false,
        ]);

        return response()->json([
            'message' => 'Voucher created',
            'voucher' => $voucher,
        ], 201);
    }

    /**
     * Update a voucher.
     *
     * @param UpdateVoucherRequest $request
     * @param Voucher $voucher
     * @return JsonResponse
     */
    public function update(UpdateVoucherRequest $request, Voucher $voucher): JsonResponse
    {
        if (!$request->user()->role === 'admin') {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $voucher->update([
            'code' => $request->code,
            'min_order_price' => $request->min_order_price,
            'value' => $request->value,
            'description' => $request->description,
            'is_percent' => $request->is_percent,
        ]);

        return response()->json([
            'message' => 'Voucher updated',
            'voucher' => $voucher,
        ], 200);
    }

    /**
     * Delete a voucher.
     *
     * @param Request $request
     * @param Voucher $voucher
     * @return JsonResponse
     */
    public function destroy(Request $request, Voucher $voucher): JsonResponse
    {
        if (!$request->user()->role === 'admin') {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $voucher->update([
            'is_deleted' => true,
        ]);

        return response()->json([
            'message' => 'Voucher deleted',
            'voucher' => $voucher,
        ], 200);
    }
}