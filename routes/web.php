<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\OrderStatusController;



Route::prefix('admin')->name('admin.')->group(function () {

    Route::get('/', function () {
        return view('admin.home');
    })->name('dashboard');
    // Đơn hàng
    Route::get('orders/search', [OrderController::class, 'search'])->name('orders.search');
    Route::resource('orders', OrderController::class);
    // Trạng thái đơn hàng
    Route::resource('order-statuses', OrderStatusController::class);
});
