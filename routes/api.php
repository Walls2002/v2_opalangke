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
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\CustomerOrderController;
use App\Http\Controllers\LocationController;
use App\Http\Controllers\ProductCatalogController;
use App\Http\Controllers\StoreController;
use App\Http\Controllers\RiderController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\RiderOrderController;
use App\Http\Controllers\VendorOrderController;
use Illuminate\Http\Request;

Route::get('/me', function (Request $request) {
    return response()->json([
        'user' => $request->user(),
    ]);
})->middleware('auth:sanctum');

Route::apiResource('users', UserController::class);

Route::post('login', [AuthController::class, 'login']);

Route::apiResource('locations', LocationController::class);

Route::middleware('auth:sanctum')->group(function () {
    Route::apiResource('stores', StoreController::class);
});

Route::middleware('auth:sanctum')->group(function () {
    Route::apiResource('riders', RiderController::class);
});

Route::middleware('auth:sanctum')->group(function () {
    Route::apiResource('products', ProductController::class);
});

Route::middleware(['guest'])->group(function () {
    Route::post('/customers', [CustomerController::class, 'store']);
});

Route::get('/catalog', [ProductCatalogController::class, 'index']);

Route::middleware(['auth:sanctum'])->group(function () {
    Route::get('/cart', [CartController::class, 'index']);
    Route::post('/cart/{product}', [CartController::class, 'store']);
    Route::put('/cart/{product}', [CartController::class, 'update']);
    Route::delete('/cart', [CartController::class, 'destroy']);
    Route::post('/cart/checkout/{store}', [CheckoutController::class, 'store']);
});

Route::middleware(['auth:sanctum'])->group(function () {
    Route::get('/vendor-orders/{store}', [VendorOrderController::class, 'index']);
    Route::get('/vendor-orders/{order}/show', [VendorOrderController::class, 'show']);
    Route::put('/vendor-orders/{order}/confirm', [VendorOrderController::class, 'confirm']);
    Route::post('/vendor-orders/{order}/assign', [VendorOrderController::class, 'assign']);
    Route::delete('/vendor-orders/{order}/cancel', [VendorOrderController::class, 'cancel']);
});

Route::middleware(['auth:sanctum'])->group(function () {
    Route::get('/customer-orders', [CustomerOrderController::class, 'index']);
    Route::get('/customer-orders/{order}', [CustomerOrderController::class, 'show']);
});

Route::middleware(['auth:riders'])->group(function () {
    Route::get('/rider-orders', [RiderOrderController::class, 'index']);
    Route::get('/rider-orders/{order}/show', [RiderOrderController::class, 'show']);
    Route::post('/rider-orders/{order}/deliver', [RiderOrderController::class, 'store']);
});
