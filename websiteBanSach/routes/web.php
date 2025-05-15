<?php

use App\Http\Controllers\CategoryController;
use App\Http\Controllers\PromotionController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('admin.home');
});
Route::prefix('admin')->group(function () {
    Route::get('/promotions', [PromotionController::class, 'index'])->name('promotions.index');
    Route::post('/promotions', [PromotionController::class, 'store'])->name('promotions.store');
    Route::get('/promotions/{id}/edit', [PromotionController::class, 'edit'])->name('promotions.edit');
    Route::put('/promotions/{id}', [PromotionController::class, 'update'])->name('promotions.update');
    Route::delete('/promotions/{id}', [PromotionController::class, 'destroy'])->name('promotions.destroy');
    Route::post('/promotions/apply', [PromotionController::class, 'apply'])->name('promotions.apply');
});

Route::prefix('admin')->name('admin.')->group(function () {
    Route::resource('categories', CategoryController::class);
});