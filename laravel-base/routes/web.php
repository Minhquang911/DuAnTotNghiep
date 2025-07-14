<?php

use App\Http\Middleware\CheckRole;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;

use App\Http\Controllers\Admin\PostController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\AlbumController;
use App\Http\Controllers\Admin\OrderController;
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
use App\Http\Controllers\Auth\GoogleLoginController;
use App\Http\Controllers\Admin\ProductVariantController;
use App\Http\Controllers\Client\UserProfileController;
use App\Http\Controllers\Client\CartController;
use App\Http\Controllers\Client\OrderController as ClientOrderController;
use App\Http\Controllers\Client\ProductController as ClientProductController;
use App\Http\Controllers\Client\PostController as ClientPostController;
use App\Http\Controllers\Client\ContactController as ClientContactController;

// Route::get('/', function () {
//     return view('welcome');
// });

Auth::routes();
// Đăng nhập bằng google
Route::get('/login/google', [GoogleLoginController::class, 'redirectToGoogle'])->name('login.google');
Route::get('/login/google/callback', [GoogleLoginController::class, 'handleGoogleCallback']);

// Các route cho khách hàng chưa đăng nhập
Route::get('/', [HomeController::class, 'index'])->name('home');

Route::prefix('products')->name('client.products.')->controller(ClientProductController::class)->group(function () {
    Route::get('/', 'index')->name('index');
    Route::get('/{slug}', 'show')->name('show');

    // AJAX routes cho comments và ratings pagination
    Route::get('/{slug}/comments', 'getComments')->name('comments');
    Route::get('/{slug}/ratings', 'getRatings')->name('ratings');
});

Route::prefix('posts')->name('client.posts.')->controller(ClientPostController::class)->group(function () {
    Route::get('/', 'index')->name('index'); // Danh sách bài viết
    Route::get('/{slug}', 'show')->name('show'); // Chi tiết bài viết theo slug
});

Route::prefix('contact')->name('client.contact.')->controller(ClientContactController::class)->group(function () {
    Route::get('/', 'index')->name('index'); 
    Route::post('/store', 'store')->middleware('auth')->name('store'); 
});

// Các route quản lý giỏ hàng
Route::middleware(['auth', CheckRole::class . ':user'])->prefix('cart')->name('cart.')->group(function () {
    Route::get('/',                 [CartController::class, 'index'])->name('index');
    Route::post('/add',             [CartController::class, 'add'])->name('add');
    Route::post('/update',          [CartController::class, 'update'])->name('update');
    Route::delete('/remove/{id}',   [CartController::class, 'remove'])->name('remove');
    Route::delete('/clear',         [CartController::class, 'clear'])->name('clear');
});

// Các route quản lý đơn hàng
Route::middleware(['auth', CheckRole::class . ':user'])->prefix('orders')->name('orders.')->group(function () {
    Route::get('/add',              [ClientOrderController::class, 'add'])->name('add');
    Route::post('/store',           [ClientOrderController::class, 'store'])->name('store'); // Thêm đơn hàng mới
    Route::get('/success', function () {
        return view('client.order.success');
    })->name('success');
});

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

            // Thùng rác sản phẩm
            Route::get('trashed', [ProductController::class, 'trashed'])->name('trashed');
            Route::post('{id}/restore', [ProductController::class, 'restore'])->name('restore');
            Route::delete('{id}/force-delete', [ProductController::class, 'forceDelete'])->name('force-delete');
            Route::post('restore-all', [ProductController::class, 'restoreAll'])->name('restore-all');
            Route::delete('force-delete-all', [ProductController::class, 'forceDeleteAll'])->name('force-delete-all');
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

        // Quản lý đơn hàng
        Route::prefix('orders')->name('orders.')->group(function () {
            Route::get('/', [OrderController::class, 'index'])->name('index');
            Route::get('/{order}', [OrderController::class, 'show'])->name('show');
            Route::post('/{order}/confirm', [OrderController::class, 'confirm'])->name('confirm');
            Route::post('/{order}/cancel', [OrderController::class, 'cancel'])->name('cancel');
            Route::post('/{order}/update-status', [OrderController::class, 'updateStatus'])->name('update-status');
        });
    });

// Các route cho user thường
Route::middleware(['auth', CheckRole::class . ':user'])
    ->name('user.')
    ->group(function () {
        // Quản lý tài khoản cá nhân
        Route::get('/profile', [UserProfileController::class, 'profile'])->name('profile');
        Route::get('/profile/password', [UserProfileController::class, 'password'])->name('profile.password');
        Route::put('/profile', [UserProfileController::class, 'updateProfile'])->name('profile.update');
        Route::put('/profile/password', [UserProfileController::class, 'updatePassword'])->name('profile.password');
    });