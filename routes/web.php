<?php

use Illuminate\Support\Facades\Route;

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

//dashboard

Route::get('/home', function () {
    return view('dashboard/home');
});

Route::get('/users', function () {
    return view('dashboard/users');
});

Route::get('/locations', function () {
    return view('dashboard/locations');
});

Route::get('/cart', function () {
    return view('dashboard/cart');
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