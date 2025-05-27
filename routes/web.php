<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;




/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('index');
});

Route::get('/login', function () {
    return view('login');
});

Route::get('/register', function () {
    return view('register');
});
Route::get('/vendor-register', function () {
    return view('vendor-register');
});

//dashboard

Route::get('/home', function () {
    return view('dashboard/home');
});

Route::get('/update-profile', function () {
    return view('dashboard/update-profile');
});

//admin

Route::get('/admin/vendors', function () {
    return view('dashboard/admin/admin-vendors');
});

Route::get('/admin/vouchers', function () {
    return view('dashboard/admin/admin-vouchers');
});

Route::get('/admin/locations', function () {
    return view('dashboard/admin/admin-locations');
});

Route::get('/admin/stores', function () {
    return view('dashboard/admin/admin-stores');
});

Route::get('/admin/riders', function () {
    return view('dashboard/admin/admin-riders');
});

Route::get('/admin/products', function () {
    return view('dashboard/admin/admin-view-products');
});

Route::get('/admin/orders', function () {
    return view('dashboard/admin/admin-view-orders');
});

Route::get('/admin/riderorders', function () {
    return view('dashboard/admin/admin-view-rider-orders');
});
//

Route::get('/view-product', function () {
    return view('dashboard/view-product');
});

Route::get('/cart', function () {
    return view('dashboard/cart');
});

Route::get('/myvouchers', function () {
    return view('dashboard/customer-vouchers');
});

Route::get('/order-pending', function () {
    return view('dashboard/order-pending');
});

Route::get('/order-confirmed', function () {
    return view('dashboard/order-confirmed');
});

Route::get('/order-delivered', function () {
    return view('dashboard/order-delivered');
});

Route::get('/order-canceled', function () {
    return view('dashboard/order-canceled');
});

//vendor

Route::get('/rider', function () {
    return view('dashboard/rider');
});

Route::get('/products', function () {
    return view('dashboard/products');
});

Route::get('/vendor/store', function () {
    return view('dashboard/vendor-store');
});

Route::get('/vendor-order-pending', function () {
    return view('dashboard/vendor-order-pending');
});


Route::get('/vendor-order-confirmed', function () {
    return view('dashboard/vendor-order-confirmed');
});

Route::get('/vendor-order-delivered', function () {
    return view('dashboard/vendor-order-delivered');
});

Route::get('/vendor-order-canceled', function () {
    return view('dashboard/vendor-order-canceled');
});

//rider

Route::get('/local-orders', function () {
    return view('dashboard/rider-local-orders');
});

Route::get('/team-orders', function () {
    return view('dashboard/rider-team-orders');
});

Route::get('/delivery', function () {
    return view('dashboard/delivery');
});

Route::get('/forgot-password', function () {
    return view('forgot-password');
});
Route::get('/verify-otp', function (Request $request) {
    $email = $request->query('email');
    return view('verify-otp', ['email' => $email]);
});

Route::get('/reset-password', function (Request $request) {
    $email = $request->query('email');
    return view('reset-password', ['email' => $email]);
});
