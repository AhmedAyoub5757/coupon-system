<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CouponController;
use App\Http\Controllers\StorefrontController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', [StorefrontController::class, 'index'])->name('shop');
Route::post('/cart/{product}', [StorefrontController::class, 'add'])->name('cart.add');
Route::patch('/cart', [StorefrontController::class, 'update'])->name('cart.update');
Route::post('/cart/coupon', [StorefrontController::class, 'applyCoupon'])->name('cart.coupon');
Route::delete('/cart/coupon', [StorefrontController::class, 'removeCoupon'])->name('cart.coupon.remove');

Route::resource('coupons', CouponController::class);
