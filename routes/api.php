<?php

use Illuminate\Http\Request;
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
use App\Http\Controllers\LocationController;
use App\Http\Controllers\StoreController;
use App\Http\Controllers\RiderController;
use App\Http\Controllers\ProductController;

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



