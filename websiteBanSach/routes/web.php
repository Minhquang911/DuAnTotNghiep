<?php

use App\Http\Controllers\CategoryController;
use App\Http\Controllers\PromotionController;
use App\Http\Controllers\UserController;
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

    Route::get('/users', [UserController::class, 'index'])->name('users.index');         // Danh sách
    Route::get('/users/create', [UserController::class, 'create'])->name('users.create'); // Form tạo mới
    Route::post('/users', [UserController::class, 'store'])->name('users.store');         // Xử lý tạo mới
    Route::get('/users/{id}/edit', [UserController::class, 'edit'])->name('users.edit');  // Form sửa
    Route::put('/users/{id}', [UserController::class, 'update'])->name('users.update');   // Xử lý cập nhật
    Route::get('/users/{id}', [UserController::class, 'show'])->name('users.show');
    Route::delete('/users/{id}', [UserController::class, 'destroy'])->name('users.destroy'); // Xóa

    Route::get('/categories', [CategoryController::class, 'index'])->name('categories.index');       
    Route::get('/categories/create', [CategoryController::class, 'create'])->name('categories.create');       
    Route::post('/categories', [CategoryController::class, 'store'])->name('categories.store');       
    Route::get('/categories/{id}/edit', [CategoryController::class, 'edit'])->name('categories.edit');       
    Route::put('/categories/{id}', [CategoryController::class, 'update'])->name('categories.update');       
    Route::delete('/categories/{id}', [CategoryController::class, 'destroy'])->name('categories.destroy');       
});