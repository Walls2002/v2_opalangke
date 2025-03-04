<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreVoucherRequest;
use App\Http\Requests\UpdateVoucherRequest;
use App\Models\User;
use App\Models\UserVoucher;
use App\Models\Voucher;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

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
        if ($request->user()->role !== 'admin') {
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
        if ($request->user()->role !== 'admin') {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        if ($voucher->is_deleted) {
            return response()->json(['message' => 'Not found.'], 404);
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
        if ($request->user()->role !== 'admin') {
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
        if ($request->user()->role !== 'admin') {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        if ($voucher->is_deleted) {
            return response()->json(['message' => 'Not found.'], 404);
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
        if ($request->user()->role !== 'admin') {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        if ($voucher->is_deleted) {
            return response()->json(['message' => 'Not found.'], 404);
        }

        $voucher->update([
            'is_deleted' => true,
        ]);

        return response()->json([
            'message' => 'Voucher deleted',
            'voucher' => $voucher,
        ], 200);
    }

    /**
     * Give the voucher to all the customers.
     *
     * @param Request $request
     * @param Voucher $voucher
     * @return JsonResponse
     */
    public function giveVoucherAll(Request $request, Voucher $voucher): JsonResponse
    {
        if ($request->user()->role !== 'admin') {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        if ($voucher->is_deleted) {
            return response()->json(['message' => 'Not found.'], 404);
        }

        $request->validate([
            'expiration_date' => ['required', 'date_format:Y-m-d', 'after:today'],
            'amount' => ['required', 'integer', 'min:1', 'max:100'],
        ]);

        $expiration = $request->date('expiration_date')->endOfDay();
        $amount = $request->amount;

        DB::beginTransaction();
        try {
            $vouchersCreatedCount = 0;

            User::where('role', 'customer')->chunk(100, function ($customers) use ($voucher, $expiration, $amount, &$vouchersCreatedCount) {
                foreach ($customers as $customer) {
                    for ($i = 0; $i < $amount; $i++) {
                        $userVoucher = new UserVoucher();
                        $userVoucher->user_id = $customer->id;
                        $userVoucher->voucher_id = $voucher->id;
                        $userVoucher->used_at = null;
                        $userVoucher->expired_at = $expiration;

                        if (!$userVoucher->save()) {
                            throw new \Exception('Failed to save record for customer id: ' . $customer->id);
                        }

                        $vouchersCreatedCount++;
                    }
                }
            });

            DB::commit();

            return response()->json([
                'message' => 'Vouchers given.',
                'vouchers_created_count' => $vouchersCreatedCount,
            ], 200);
        } catch (\Throwable $th) {
            DB::rollBack();
            return response()->json([
                'message' => 'Vouchers could not be distributed.',
            ], 400);
        }
    }

    /**
     * Give the voucher to a specific customer.
     *
     * @param Request $request
     * @param Voucher $voucher
     * @return JsonResponse
     */
    public function giveVoucherSingle(Request $request, Voucher $voucher): JsonResponse
    {
        if ($request->user()->role !== 'admin') {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        if ($voucher->is_deleted) {
            return response()->json(['message' => 'Not found.'], 404);
        }

        $request->validate([
            'user_id' => ['required', 'exists:users,id'],
            'expiration_date' => ['required', 'date_format:Y-m-d', 'after:today'],
            'amount' => ['required', 'integer', 'min:1', 'max:100'],
        ]);

        $expiration = $request->date('expiration_date')->endOfDay();
        $amount = $request->integer('amount');

        DB::beginTransaction();
        try {
            $vouchersCreatedCount = 0;

            for ($i = 0; $i < $amount; $i++) {
                $userVoucher = new UserVoucher();
                $userVoucher->user_id = $request->user_id;
                $userVoucher->voucher_id = $voucher->id;
                $userVoucher->used_at = null;
                $userVoucher->expired_at = $expiration;

                if (!$userVoucher->save()) {
                    throw new \Exception('Failed to save record for customer id');
                }

                $vouchersCreatedCount++;
            }

            DB::commit();

            return response()->json([
                'message' => 'Voucher given to user.',
                'vouchers_created_count' => $vouchersCreatedCount,
            ], 200);
        } catch (\Throwable $th) {
            DB::rollBack();
            return response()->json([
                'message' => 'Vouchers could not be distributed.',
            ], 400);
        }
    }
}