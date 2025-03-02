<?php

namespace App\Http\Controllers;

use App\Http\Resources\UserVoucherResource;
use App\Models\UserVoucher;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class UserVoucherController extends Controller
{
    /**
     * Show all the valid vouchers of the logged user.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function index(Request $request): JsonResponse
    {
        $vouchers = UserVoucher::query()
            ->with(['voucher'])
            ->where('used_at', null)
            ->where('expired_at', '>', now('Asia/Manila'))
            ->whereRelation('voucher', 'is_deleted', '=', false)
            ->orderBy('expired_at', 'ASC')
            ->get();

        return response()->json([
            'message' => 'Vouchers fetched',
            'vouchers' => UserVoucherResource::collection($vouchers),
        ], 200);
    }
}