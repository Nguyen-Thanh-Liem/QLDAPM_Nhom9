<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\Users\LoginController;
use App\Http\Controllers\Admin\MainController;
use App\Http\Controllers\Admin\MenuController;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\Admin\SliderController;
use App\Http\Controllers\Admin\LogoutController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\DiscountController;
use App\Http\Controllers\OrderController;


use App\Http\Controllers\AuthController;
use App\Http\Controllers\Admin\CartController;

// Route cho đăng nhập admin
Route::get('admin/users/login', [LoginController::class, 'index'])->name('admin.login'); // Thay đổi tên route cho login
Route::post('admin/users/login/store', [LoginController::class, 'store']);

// Các route yêu cầu xác thực
Route::middleware(['auth'])->group(function () {
    // Route::get('/order-history', [OrderController::class, 'index'])->name('order.history');

    Route::prefix('admin')->group(function () {
        Route::get('/', [MainController::class, 'index'])->name('admin.dashboard'); // Đổi tên route cho dashboard admin
        Route::get('main', [MainController::class, 'index']);

        // Logout
        Route::get('/logout', [LogoutController::class, 'getLogout'])->name('admin.logout'); // Thêm tên route cho logout admin

        // Menu
        Route::prefix('menus')->group(function () {
            Route::get('add', [MenuController::class, 'create']);
            Route::post('add', [MenuController::class, 'store']);
            Route::get('list', [MenuController::class, 'index']);
            Route::get('edit/{menu}', [MenuController::class, 'show']);
            Route::post('edit/{menu}', [MenuController::class, 'update']);
            Route::DELETE('destroy', [MenuController::class, 'destroy']);
        });

        // Product
        Route::prefix('products')->group(function () {
            Route::post('admin/products/add', [ProductController::class, 'store'])->name('admin.products.store');
            Route::get('add', [ProductController::class, 'create']);
            Route::post('add', [ProductController::class, 'store']);
            Route::get('list', [ProductController::class, 'index']);
            Route::get('edit/{product}', [ProductController::class, 'show']);
            Route::post('edit/{product}', [ProductController::class, 'update']);
            Route::DELETE('destroy', [ProductController::class, 'destroy']);
        });

        // Slider
        Route::prefix('sliders')->group(function () {
            Route::get('add', [SliderController::class, 'create']);
            Route::post('add', [SliderController::class, 'store']);
            Route::get('list', [SliderController::class, 'index']);
            Route::get('edit/{slider}', [SliderController::class, 'show']);
            Route::post('edit/{slider}', [SliderController::class, 'update']);
            Route::DELETE('destroy', [SliderController::class, 'destroy']);
        });
        // DISCOUNT
        Route::prefix('discounts')->group(function () {
            Route::get('/list', [DiscountController::class, 'index']);
            Route::get('/add', [DiscountController::class, 'create']);
            Route::post('/add', [DiscountController::class, 'store']);
            Route::get('/edit/{discount}', [DiscountController::class, 'edit']);
            Route::post('/edit/{discount}', [DiscountController::class, 'update']);
            Route::delete('destroy', [DiscountController::class, 'destroy']);
        });

        // User
        Route::prefix('users')->group(function () {
            Route::get('add', [UserController::class, 'create']);
            Route::post('add', [UserController::class, 'store']);
            Route::get('list', [UserController::class, 'index']);
            Route::get('edit/{user}', [UserController::class, 'show']);
            Route::post('edit/{user}', [UserController::class, 'update']);
            Route::DELETE('destroy', [UserController::class, 'destroy']);

            // Change password
            Route::get('change-password', [UserController::class, 'showChangePasswordForm'])->name('user.change-password');
            Route::post('change-password', [UserController::class, 'changePassword'])->name('user.change-password.post');
        });

        // Upload
        Route::post('upload/services', [\App\Http\Controllers\Admin\UploadController::class, 'store']);

        // Cart
        // chi tiết hóa đơn
        Route::get('/customer/{id}', [\App\Http\Controllers\Admin\CartController::class, 'showCustomer'])->name('admin.carts.showCustomer');
        // IN PDF
        Route::get('admin/carts/{customer}/export-pdf', [\App\Http\Controllers\Admin\CartController::class, 'exportPdf'])->name('admin.carts.exportPdf');

        Route::get('customers', [\App\Http\Controllers\Admin\CartController::class, 'index']);
        Route::get('customers/view/{customer}', [\App\Http\Controllers\Admin\CartController::class, 'show']);
    });
});

// Public Routes
Route::get('/', [App\Http\Controllers\MainController::class, 'index']);
Route::post('/services/load-product', [App\Http\Controllers\MainController::class, 'loadProduct']);
Route::get('danh-muc/{id}-{slug}.html', [App\Http\Controllers\MenuController::class, 'index']);
Route::get('san-pham/{id}-{slug}.html', [App\Http\Controllers\ProductController::class, 'index']);
Route::post('add-cart', [App\Http\Controllers\CartController::class, 'index']);
Route::get('carts', [App\Http\Controllers\CartController::class, 'show']);
Route::post('update-cart', [App\Http\Controllers\CartController::class, 'update']);
Route::get('carts/delete/{id}', [App\Http\Controllers\CartController::class, 'remove']);
Route::post('carts', [App\Http\Controllers\CartController::class, 'addCart']);

// Statistics
Route::get('/statistics', [App\Http\Controllers\StatisticsController::class, 'index'])->name('statistics.index');
Route::get('/admin/statistics/index', [App\Http\Controllers\StatisticsController::class, 'index'])->name('admin.statistics.index');

// Order status
Route::get('/admin/carts/{customer}', [App\Http\Controllers\Admin\CartController::class, 'show'])->name('admin.carts.show');
Route::put('/admin/carts/{customer}/update-status', [App\Http\Controllers\Admin\CartController::class, 'updateStatus'])->name('admin.carts.updateStatus');

// XỬ LÝ ĐĂNG KÍ, ĐĂNG NHẬP, ĐĂNG XUẤT USER
Route::middleware(['guest'])->group(function () {
    // Đăng ký
    Route::get('/register', [AuthController::class, 'showRegisterForm'])->name('register');
    Route::post('/register', [AuthController::class, 'register']);

    // Đăng nhập
    Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
});

Route::middleware(['auth'])->group(function () {
    // Đăng xuất
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
    // Lsử đơn hàng frontend
    Route::get('/order-history', [OrderController::class, 'history'])->name('order.history');

    // Dashboard
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');
});
Route::get('/', [App\Http\Controllers\MainController::class, 'index'])->name('home');
// thêm mã giảm giá frontend
Route::post('/apply-discount', [App\Http\Controllers\CartController::class, 'applyDiscount']);
// // Google OAuth
Route::get('/login/google', [AuthController::class, 'getGoogleLogin'])->name('google.login');
Route::get('/login/google/callback', [AuthController::class, 'getGoogleCallback'])->name('google.callback');
