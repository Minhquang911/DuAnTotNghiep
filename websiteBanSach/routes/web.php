<?php

use App\Http\Controllers\CategoryController;
use App\Http\Controllers\PromotionController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\OrderStatusController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('admin.home');
});
Route::prefix('admin')->group(function () {
    Route::get('/promotions', [PromotionController::class, 'index'])->name('promotions.index');
    Route::get('/promotions/create', [PromotionController::class, 'create'])->name('promotions.create');
    Route::post('/promotions', [PromotionController::class, 'store'])->name('promotions.store');
    Route::get('/promotions/{id}/edit', [PromotionController::class, 'edit'])->name('promotions.edit');
    Route::put('/promotions/{id}', [PromotionController::class, 'update'])->name('promotions.update');
    Route::delete('/promotions/{id}', [PromotionController::class, 'destroy'])->name('promotions.destroy');
    Route::post('/promotions/apply', [PromotionController::class, 'apply'])->name('promotions.apply');
});

Route::prefix('admin')->name('admin.')->group(function () {
    Route::resource('categories', CategoryController::class);

        Route::get('orders/search', [OrderController::class, 'search'])->name('orders.search');
        Route::resource('orders', OrderController::class);
        Route::resource('order-statuses', OrderStatusController::class);

});
