<?php

use App\Http\Controllers\Admin\AuthController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\OrderController as AdminOrderController;
use App\Http\Controllers\Admin\ProductController as AdminProductController;
use App\Http\Controllers\Admin\ProductVariantController as AdminProductVariantController;
use App\Http\Controllers\Admin\ProductVariantOptionController as AdminProductVariantOptionController;
use App\Http\Controllers\Admin\SettingController;
use App\Http\Controllers\CatalogController;
use App\Http\Controllers\OrderController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Customer (publik)
|--------------------------------------------------------------------------
*/
Route::get('/', [CatalogController::class, 'index'])->name('catalog.index');
Route::get('/produk/{product}', [CatalogController::class, 'show'])->name('catalog.show');
Route::get('/checkout/{option}', [CatalogController::class, 'checkout'])->name('catalog.checkout');
Route::post('/order', [OrderController::class, 'store'])->name('order.store');
Route::get('/pembayaran/{order}', [OrderController::class, 'payment'])->name('payment.show');
Route::post('/pembayaran/{order}/konfirmasi', [OrderController::class, 'confirm'])->name('payment.confirm');

/*
|--------------------------------------------------------------------------
| Admin
|--------------------------------------------------------------------------
*/
Route::prefix('admin')->name('admin.')->group(function () {
    // Auth
    Route::get('login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('login', [AuthController::class, 'login'])->name('login.attempt');
    Route::post('logout', [AuthController::class, 'logout'])->name('logout');

    // Area terproteksi
    Route::middleware('auth')->group(function () {
        Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

        // Pesanan
        Route::get('orders', [AdminOrderController::class, 'index'])->name('orders.index');
        Route::get('orders/{order}', [AdminOrderController::class, 'show'])->name('orders.show');
        Route::patch('orders/{order}/status', [AdminOrderController::class, 'updateStatus'])->name('orders.status');

        // Produk
        Route::resource('products', AdminProductController::class)->except('show');
        Route::patch('products/{product}/toggle', [AdminProductController::class, 'toggle'])->name('products.toggle');

        // Jenis (per produk) & Durasi (per jenis)
        Route::resource('products.variants', AdminProductVariantController::class)->shallow()->except('show');
        Route::patch('variants/{variant}/toggle', [AdminProductVariantController::class, 'toggle'])->name('variants.toggle');

        Route::resource('variants.options', AdminProductVariantOptionController::class)->shallow()->except('show');
        Route::patch('options/{option}/toggle', [AdminProductVariantOptionController::class, 'toggle'])->name('options.toggle');

        // Pengaturan
        Route::get('settings', [SettingController::class, 'edit'])->name('settings.edit');
        Route::put('settings', [SettingController::class, 'update'])->name('settings.update');
    });
});
