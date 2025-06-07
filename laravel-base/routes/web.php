<?php

use App\Http\Middleware\CheckRole;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;

use App\Http\Controllers\Admin\PostController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\BannerController;
use App\Http\Controllers\Admin\FormatController;
use App\Http\Controllers\Admin\RatingController;
use App\Http\Controllers\Admin\CommentController;
use App\Http\Controllers\Admin\ContactController;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\LanguageController;
use App\Http\Controllers\Admin\PromotionController;
use App\Http\Controllers\Admin\PublisherController;
use App\Http\Controllers\Admin\ProductVariantController;
use App\Http\Controllers\Admin\AlbumController;

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();
Route::get('/home', [HomeController::class, 'index'])->name('home');

// Các route cho admin
Route::middleware(['auth', CheckRole::class . ':admin'])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {
        Route::get('/', [AdminController::class, 'index'])->name('dashboard');

        // Quản lý tài khoản người dùng
        Route::resource('users', UserController::class);
        Route::post('/users/{user}/toggle-status', [UserController::class, 'toggleStatus'])->name('admin.users.toggle-status');

        // Quản lý danh mục
        Route::resource('categories', CategoryController::class);
        Route::post('/categories/{category}/toggle-status', [CategoryController::class, 'toggleStatus'])->name('admin.categories.toggle-status');

        // Quản lý nhà xuất bản
        Route::resource('publishers', PublisherController::class);
        Route::post('/publishers/{publisher}/toggle-status', [PublisherController::class, 'toggleStatus'])->name('admin.publishers.toggle-status');

        // Quản lý banner
        Route::resource('banners', BannerController::class);
        Route::post('/banners/{banner}/toggle-status', [BannerController::class, 'toggleStatus'])->name('admin.banners.toggle-status');

        // Quản lý mã khuyến mãi
        Route::resource('promotions', PromotionController::class);
        Route::post('/promotions/{promotion}/toggle-status', [PromotionController::class, 'toggleStatus'])->name('admin.promotions.toggle-status');

        // Quản lý định dạng sách
        Route::resource('formats', FormatController::class);
        Route::post('/formats/{format}/toggle-status', [FormatController::class, 'toggleStatus'])->name('admin.formats.toggle-status');

        // Quản lý ngôn ngữ
        Route::resource('languages', LanguageController::class);
        Route::post('/languages/{language}/toggle-status', [LanguageController::class, 'toggleStatus'])->name('admin.languages.toggle-status');

        // Nhóm các route phụ trợ của sản phẩm
        Route::prefix('products')->name('products.')->group(function () {
            // Đổi trạng thái hoạt động
            Route::post('{product}/toggle-status', [ProductController::class, 'toggleStatus'])->name('toggle-status');

            // Quản lý album ảnh sản phẩm
            Route::post('{product}/upload-images', [AlbumController::class, 'upload'])->name('upload-images');
            Route::delete('images/{album}', [AlbumController::class, 'destroy'])->name('albums.destroy');
        });
        // Quản lý sản phẩm (CRUD chính)
        Route::resource('products', ProductController::class);

        // Quản lý biến thể sản phẩm
        Route::resource('product-variants', ProductVariantController::class);
        Route::post('/product-variants/{productVariant}/toggle-status', [ProductVariantController::class, 'toggleStatus'])->name('admin.product-variants.toggle-status');

        // Quản lý bình luận
        Route::resource('comments', CommentController::class)->except(['show']);
        Route::prefix('comments')->name('comments.')->group(function () {
            Route::post('{comment}/approve', [CommentController::class, 'approve'])->name('approve');
            Route::post('{comment}/reject', [CommentController::class, 'reject'])->name('reject');

            Route::get('trashed', [CommentController::class, 'trashed'])->name('trashed');
            Route::post('{id}/restore', [CommentController::class, 'restore'])->name('restore');
            Route::delete('{id}/force-delete', [CommentController::class, 'forceDelete'])->name('forceDelete');
        });

        // Quản lý đánh giá
        Route::resource('ratings', RatingController::class)->except(['show']);
        Route::prefix('ratings')->name('ratings.')->group(function () {
            Route::post('{rating}/approve', [RatingController::class, 'approve'])->name('approve');
            Route::post('{rating}/reject', [RatingController::class, 'reject'])->name('reject');
            Route::post('{rating}/reply', [RatingController::class, 'reply'])->name('reply');

            Route::get('trashed', [RatingController::class, 'trashed'])->name('trashed');
            Route::post('{id}/restore', [RatingController::class, 'restore'])->name('restore');
            Route::delete('{id}/force-delete', [RatingController::class, 'forceDelete'])->name('forceDelete');
        });

        // Quản lý liên hệ
        Route::resource('contacts', ContactController::class);
        Route::post('/contacts/{contact}/approve', [ContactController::class, 'approve'])->name('admin.contacts.approve');
        Route::post('/contacts/{contact}/reject', [ContactController::class, 'reject'])->name('admin.contacts.reject');

        // Quản lý bài viết
        Route::resource('posts', PostController::class);
        Route::post('/posts/{post}/toggle-status', [PostController::class, 'toggleStatus'])->name('admin.posts.toggle-status');
    });

// Các route cho user thường
Route::middleware(['auth', CheckRole::class . ':user'])
    ->prefix('user')
    ->name('user.')
    ->group(function () {
        Route::get('/', function () {
            return 'Đây là user dashboard';
        })->name('dashboard');
    });