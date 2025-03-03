<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/
use App\Http\Controllers\UserController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ChangePasswordController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\CustomerOrderController;
use App\Http\Controllers\LocationController;
use App\Http\Controllers\ProductCatalogController;
use App\Http\Controllers\StoreController;
use App\Http\Controllers\RiderController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ReviewController;
use App\Http\Controllers\RiderOrderController;
use App\Http\Controllers\RiderReviewController;
use App\Http\Controllers\RiderStoreController;
use App\Http\Controllers\UserVoucherController;
use App\Http\Controllers\VendorOrderController;
use App\Http\Controllers\VoucherController;
use Illuminate\Http\Request;

Route::get('/me', function (Request $request) {
    return response()->json([
        'user' => $request->user(),
    ]);
})->middleware('auth:sanctum');

Route::post('users/vendor-register', [UserController::class, 'storeVendor']);

Route::middleware('auth:sanctum')->group(function () {
    Route::get('users/all', [UserController::class, 'indexAll']);
    Route::post('users/vendor-verify/{user}', [UserController::class, 'verifyVendorUser']);
});

Route::apiResource('users', UserController::class);

Route::post('login', [AuthController::class, 'login']);

Route::apiResource('locations', LocationController::class);

Route::middleware('auth:sanctum')->group(function () {
    Route::post('stores/self-register', [StoreController::class, 'storeUnverified']);
    Route::put('stores/{store}/verify', [StoreController::class, 'verifyStore']);
    Route::apiResource('stores', StoreController::class);
});

Route::middleware('auth:sanctum')->group(function () {
    Route::apiResource('riders', RiderController::class);
});

Route::middleware(['auth:sanctum'])->group(function () {
    Route::get('/categories', [CategoryController::class, 'index']);
    Route::get('/categories/parents', [CategoryController::class, 'parents']);
    Route::get('/categories/children', [CategoryController::class, 'children']);

    Route::post('/categories', [CategoryController::class, 'store']);
    Route::put('/categories/{category}', [CategoryController::class, 'update']);
    Route::delete('/categories/{category}', [CategoryController::class, 'destroy']);
});

Route::middleware('auth:sanctum')->group(function () {
    Route::apiResource('products', ProductController::class);
});

Route::middleware(['guest'])->group(function () {
    Route::post('/customers', [CustomerController::class, 'store']);
});

Route::get('/public-catalog', [ProductCatalogController::class, 'publicIndex']);

Route::middleware(['auth:sanctum'])->group(function () {
    Route::get('/catalog', [ProductCatalogController::class, 'index']);
});

Route::middleware(['auth:sanctum'])->group(function () {
    Route::put('/profile/update', [ProfileController::class, 'update']);
    Route::put('/profile/change-password', [ChangePasswordController::class, 'update']);
    Route::put('/profile/change-location', [ProfileController::class, 'changeLocation']);
    Route::post('/profile/change-profile-picture', [ProfileController::class, 'changeProfilePhoto']);
});

Route::middleware(['auth:sanctum'])->group(function () {
    Route::get('/vouchers', [VoucherController::class, 'index']);
    Route::post('/vouchers', [VoucherController::class, 'store']);
    Route::get('/vouchers/{voucher}', [VoucherController::class, 'show']);
    Route::put('/vouchers/{voucher}', [VoucherController::class, 'update']);
    Route::delete('/vouchers/{voucher}', [VoucherController::class, 'destroy']);

    Route::post('/vouchers/{voucher}/give-all', [VoucherController::class, 'giveVoucherAll']);
    Route::post('/vouchers/{voucher}/give-single', [VoucherController::class, 'giveVoucherSingle']);

    Route::get('/my-vouchers', [UserVoucherController::class, 'index']);
});

Route::middleware(['auth:sanctum'])->group(function () {
    Route::get('/cart', [CartController::class, 'index']);
    Route::post('/cart/{product}', [CartController::class, 'store']);
    Route::put('/cart/{product}', [CartController::class, 'update']);
    Route::delete('/cart', [CartController::class, 'destroy']);
    Route::post('/cart/checkout/{store}', [CheckoutController::class, 'store']);
    Route::post('/cart/checkout-preview/{store}', [CheckoutController::class, 'storePreview']);
});

Route::middleware(['auth:sanctum'])->group(function () {
    Route::get('/vendor-orders/{store}', [VendorOrderController::class, 'index']);
    Route::get('/vendor-orders/{order}/show', [VendorOrderController::class, 'show']);
    Route::put('/vendor-orders/{order}/confirm', [VendorOrderController::class, 'confirm']);
    Route::post('/vendor-orders/{order}/dispatch', [VendorOrderController::class, 'dispatchOrder']);
    Route::delete('/vendor-orders/{order}/cancel', [VendorOrderController::class, 'cancel']);
});

Route::middleware(['auth:sanctum'])->group(function () {
    Route::get('/customer-orders', [CustomerOrderController::class, 'index']);
    Route::get('/customer-orders/{order}', [CustomerOrderController::class, 'show']);
    Route::post('/customer-orders/product-review/{orderItem}', [ReviewController::class, 'store']);
    Route::post('/customer-orders/rider-review/{order}', [RiderReviewController::class, 'store']);
});

Route::middleware(['auth:sanctum'])->group(function () {
    Route::get('/rider-orders/local', [RiderOrderController::class, 'localOrders']);
    Route::get('/rider-orders/team', [RiderOrderController::class, 'teamOrders']);
    Route::get('/rider-orders', [RiderOrderController::class, 'index']);
    Route::get('/rider-orders/{order}/show', [RiderOrderController::class, 'show']);
    Route::post('/rider-orders/{order}/take', [RiderOrderController::class, 'take']);
    Route::post('/rider-orders/{order}/deliver', [RiderOrderController::class, 'store']);
});

Route::middleware(['auth:sanctum'])->group(function () {
    Route::get('/store-riders/{store}', [RiderStoreController::class, 'index']);
    Route::post('/store-riders/{store}', [RiderStoreController::class, 'store']);
    Route::delete('/store-riders/{riderStore}', [RiderStoreController::class, 'destroy']);
});
