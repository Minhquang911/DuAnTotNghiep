<?php

use App\Http\Controllers\CategoryController;
use App\Http\Controllers\PromotionController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\BookController;
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
    Route::patch('/promotions/{id}/toggle', [PromotionController::class, 'toggle'])->name('promotions.toggle');
    Route::post('/promotions/apply', [PromotionController::class, 'apply'])->name('promotions.apply');

    Route::get('/users', [UserController::class, 'index'])->name('users.index');
    Route::get('/users/create', [UserController::class, 'create'])->name('users.create');
    Route::post('/users', [UserController::class, 'store'])->name('users.store');
    Route::get('/users/{id}/edit', [UserController::class, 'edit'])->name('users.edit');
    Route::put('/users/{id}', [UserController::class, 'update'])->name('users.update');
    Route::get('/users/{id}', [UserController::class, 'show'])->name('users.show');
    Route::delete('/users/{id}', [UserController::class, 'destroy'])->name('users.destroy');
    Route::patch('/admin/users/{id}/toggle-block', [UserController::class, 'toggleBlock'])->name('users.toggleBlock');
    Route::patch('/admin/users/{id}/reset-password', [UserController::class, 'resetPassword'])->name('users.resetPassword');

    Route::get('/categories', [CategoryController::class, 'index'])->name('categories.index');
    Route::get('/categories/create', [CategoryController::class, 'create'])->name('categories.create');
    Route::post('/categories', [CategoryController::class, 'store'])->name('categories.store');
    Route::get('/categories/{id}/edit', [CategoryController::class, 'edit'])->name('categories.edit');
    Route::put('/categories/{id}', [CategoryController::class, 'update'])->name('categories.update');
    Route::delete('/categories/{id}', [CategoryController::class, 'destroy'])->name('categories.destroy');

    // Routes cho quản lý sách
    Route::get('/books', [BookController::class, 'index'])->name('books.index');
    Route::get('/books/create', [BookController::class, 'create'])->name('books.create');
    Route::post('/books', [BookController::class, 'store'])->name('books.store');
    Route::get('/books/{book}/edit', [BookController::class, 'edit'])->name('books.edit');
    Route::put('/books/{book}', [BookController::class, 'update'])->name('books.update');
    Route::get('/books/{book}', [BookController::class, 'show'])->name('books.show');
    Route::delete('/books/{book}', [BookController::class, 'destroy'])->name('books.destroy');

    // Routes cho quản lý trạng thái đơn hàng
    Route::get('/order-statuses', [OrderStatusController::class, 'index'])->name('order_statuses.index');
    Route::get('/order-statuses/create', [OrderStatusController::class, 'create'])->name('order_statuses.create');
    Route::post('/order-statuses', [OrderStatusController::class, 'store'])->name('order_statuses.store');
    Route::get('/order-statuses/{id}/edit', [OrderStatusController::class, 'edit'])->name('order_statuses.edit');
    Route::put('/order-statuses/{id}', [OrderStatusController::class, 'update'])->name('order_statuses.update');
    Route::delete('/order-statuses/{id}', [OrderStatusController::class, 'destroy'])->name('order_statuses.destroy');
});
