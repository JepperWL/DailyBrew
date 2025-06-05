<?php

use App\Http\Controllers\AdminLoginController;
use App\Http\Controllers\BeverageController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\PaymentController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\WishlistController;


Route::get('/', function () {
    return redirect('/user/login');
});

Route::middleware(['user.auth'])->group(function () {
    Route::get('/user/home', [HomeController::class, 'index'])->name('user.home');
    Route::get('/user/detail/{id}', [HomeController::class, 'detail'])->name('user.detail');
    Route::get('/user/shop', [HomeController::class, 'shop'])->name('user.shop');
    Route::get('/checkout', [CheckoutController::class, 'index'])->name('user.checkout');
    Route::post('/payment/process', [PaymentController::class, 'processPayment'])->name('payment.process');
    Route::get('/user/order', [OrderController::class, 'index'])->name('user.order');
    Route::get('/user/order-detail/{id}', [OrderController::class, 'detail'])->name('user.order-detail');   
    Route::post('/user/logout', [LoginController::class, 'logout'])->name('user.logout');
    Route::post ('/user/check-status/{order}', [OrderController::class, 'checkStatus'])->name('user.check-status');
    Route::post('/user/cancel-order/{order}', [OrderController::class, 'cancelOrder'])->name('user.cancel-order');
    Route::resource('/user/wishlist', WishlistController::class);
    Route::resource('/user/cart', CartController::class);

});


Route::middleware(['admin.auth'])->group(function () {

    Route::get('/admin/dashboard', [AdminController::class, 'dashboard'])->name('admin.dashboard');
    Route::resource('/admin/beverage', BeverageController::class);
    Route::get('/admin/order', [AdminController::class, 'order'])->name('admin.order');
    Route::get('/admin/order-detail/{id}', [AdminController::class, 'orderDetail'])->name('admin.order-detail');
    Route::put('/admin/order/{order}/update-status', [AdminController::class, 'updateOrderStatus'])->name('admin.order.update-status');
    Route::post('/admin/order/{order}/check-status', [AdminController::class, 'checkOrderStatus'])->name('admin.order.check-status');
    Route::delete('/admin/order/{order}/cancel', [AdminController::class, 'cancelOrder'])->name('admin.order.cancel');
    Route::post('/admin/logout', [AdminLoginController::class, 'logout'])->name('admin.logout');

});

Route::middleware('guest')->group(function () {
    Route::get('/admin/login', [AdminLoginController::class, 'showLoginForm'])->name('admin.login');
    Route::post('/admin/login', [AdminLoginController::class, 'login']);
    Route::get('/admin/forget-password', [AdminLoginController::class, 'showForgetPasswordForm'])->name('admin.forget-password');
    Route::post('/admin/forget-password', [AdminLoginController::class, 'forgetPassword'])->name('admin.forget-password.submit');
    
    Route::get('/admin/update-password', [AdminLoginController::class, 'showUpdatePasswordForm'])->name('admin.update-password');
    Route::post('/admin/update-password', [AdminLoginController::class, 'updatePassword'])->name('admin.update-password.submit');

    Route::get('/user/login', [LoginController::class, 'showLoginForm'])->name('user.login');
    Route::post('/user/login', [LoginController::class, 'login']);
    Route::get('/user/register', [LoginController::class, 'showRegisterForm'])->name('user.register');
    Route::post('/user/register', [LoginController::class, 'register']);
    
    Route::get('/user/forget-password', [LoginController::class, 'showForgetPasswordForm'])->name('user.forget-password');
    Route::post('/user/forget-password', [LoginController::class, 'forgetPassword'])->name('user.forget-password.submit');
    
    Route::get('/user/update-password', [LoginController::class, 'showUpdatePasswordForm'])->name('user.update-password');
    Route::post('/user/update-password', [LoginController::class, 'updatePassword'])->name('user.update-password.submit');

});


