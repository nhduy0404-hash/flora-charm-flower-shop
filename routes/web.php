<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\FlowerController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\OrderTrackingController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\Admin\AdminDashboardController;
use App\Http\Controllers\Admin\AdminProductController;
use App\Http\Controllers\Admin\AdminOrderController;

/*
|--------------------------------------------------------------------------
| Web Routes - Website Bán Hoa Tươi (Flower Shop)
|--------------------------------------------------------------------------
*/

// 1. Trang Chủ & Sản Phẩm Hoa
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/flowers', [FlowerController::class, 'index'])->name('flowers.index');
Route::get('/flowers/{slug}', [FlowerController::class, 'show'])->name('flowers.show');
Route::get('/api/search-flowers', [FlowerController::class, 'searchApi'])->name('flowers.search.api');

// 2. Quản Lý Giỏ Hàng
Route::prefix('cart')->name('cart.')->group(function () {
    Route::get('/', [CartController::class, 'index'])->name('index');
    Route::post('/add', [CartController::class, 'add'])->name('add');
    Route::patch('/update/{id}', [CartController::class, 'update'])->name('update');
    Route::delete('/remove/{id}', [CartController::class, 'remove'])->name('remove');
});

// 3. Đặt Hàng & Thanh Toán
Route::prefix('checkout')->name('checkout.')->group(function () {
    Route::get('/', [CheckoutController::class, 'index'])->name('index');
    Route::post('/', [CheckoutController::class, 'store'])->name('store');
    Route::post('/check-coupon', [CheckoutController::class, 'checkCoupon'])->name('checkCoupon');
    Route::get('/success/{orderNumber}', [CheckoutController::class, 'success'])->name('success');
});

// 4. Tra Cứu Tiến Độ & Lịch Sử Đơn Hàng
Route::get('/track-order', [OrderTrackingController::class, 'index'])->name('orders.track');
Route::get('/my-orders', [OrderTrackingController::class, 'history'])->name('orders.history')->middleware('auth');

// 5. Xác Thực (Authentication)
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('login.post');
    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register'])->name('register.post');
});
Route::post('/logout', [AuthController::class, 'logout'])->name('logout')->middleware('auth');

// 6. Phân Hệ Quản Trị Viên (Admin Portal)
Route::prefix('admin')->name('admin.')->middleware(['auth', \App\Http\Middleware\AdminMiddleware::class])->group(function () {
    Route::get('/', [AdminDashboardController::class, 'index'])->name('dashboard');

    // Quản lý hoa
    Route::get('/products', [AdminProductController::class, 'index'])->name('products.index');
    Route::get('/products/create', [AdminProductController::class, 'create'])->name('products.create');
    Route::post('/products', [AdminProductController::class, 'store'])->name('products.store');
    Route::delete('/products/{id}', [AdminProductController::class, 'destroy'])->name('products.destroy');

    // Quản lý đơn hàng
    Route::get('/orders', [AdminOrderController::class, 'index'])->name('orders.index');
    Route::get('/orders/{id}', [AdminOrderController::class, 'show'])->name('orders.show');
    Route::patch('/orders/{id}/status', [AdminOrderController::class, 'updateStatus'])->name('orders.updateStatus');
});
