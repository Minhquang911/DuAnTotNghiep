<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Banner;
use App\Models\Category;
use App\Models\Post;
use App\Models\Promotion;
use App\Models\Publisher;
use App\Models\Product;
use App\Models\Order;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        // $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $title = 'Trang chủ';

        // 1. Danh sách 10 sản phẩm hot, nhiều lượt xem nhất (6 tháng qua)
        $hotPromotionProducts = Product::where('is_hot', true)
            ->where('created_at', '>=', now()->subMonths(6))
            ->orderByDesc('view_count')
            ->take(10)
            ->get();

        // 2. Danh sách 5 sản phẩm nổi bật (mới thêm gần đây)
        $featuredProducts = Product::where('is_featured', true)
            ->orderByDesc('created_at')
            ->take(5)
            ->get();

        // 3. Danh sách 3 sản phẩm bán chạy nhất trong đơn hàng hoàn thành
        $bestSellerProducts = Product::where('is_best_seller', true)
            ->whereHas('orderItems', function($query) {
                $query->whereHas('order', function($q) {
                    $q->where('status', 'finished');
                });
            })
            ->withCount(['orderItems' => function($query) {
                $query->whereHas('order', function($q) {
                    $q->where('status', 'finished');
                });
            }])
            ->orderByDesc('order_items_count')
            ->take(3)
            ->get();

        // 4. Danh sách 5 sản phẩm mới nhất
        $newProducts = Product::where('is_new', true)
            ->orderByDesc('created_at')
            ->take(5)
            ->get();

        // 5. Danh sách 10 sản phẩm dành cho bạn
        $recommendedProducts = Product::where('is_recommended', true)
            ->orderByDesc('created_at')
            ->take(10)
            ->get();

        $categories = Category::withCount('products')->where('is_active', 1)->get();

        $categoriesFootrer = Category::withCount('products')
            ->where('is_active', 1)
            ->orderByDesc('products_count')
            ->take(5)
            ->get();

        $publishers = Publisher::withCount('products')->where('is_active', 1)->get();

        $publishersFootrer = Publisher::withCount('products')
            ->where('is_active', 1)
            ->orderByDesc('products_count')
            ->take(5)
            ->get();

        $banners = Banner::where('is_active', 1)->get();

        $promotions = Promotion::where('is_active', 1)
            ->where('start_date', '<=', Carbon::now())
            ->where('end_date', '>=', Carbon::now())
            ->get();

        $posts = Post::where('is_published', 1)
            ->orderByDesc('created_at')
            ->take(4)
            ->get();

        return view('home', compact(
            'title',
            'categories',
            'publishers',
            'categoriesFootrer',
            'publishersFootrer',
            'banners',
            'promotions',
            'posts',
            'hotPromotionProducts',
            'featuredProducts',
            'bestSellerProducts',
            'newProducts',
            'recommendedProducts'
        ));
    }
}