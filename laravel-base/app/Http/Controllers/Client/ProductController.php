<?php

namespace App\Http\Controllers\Client;

use App\Models\Format;
use App\Models\Product;
use App\Models\Category;
use App\Models\Language;
use App\Models\Publisher;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $query = Product::where('is_active', true)->with(['category', 'publisher']);

        // Search
        if ($request->filled('search')) {
            $search = $request->get('search');
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                    ->orWhere('author', 'like', "%{$search}%")
                    ->orWhere('isbn', 'like', "%{$search}%")
                    ->orWhere('description', 'like', "%{$search}%");
            });
        }

        // Filter by category
        if ($request->filled('category_id')) {
            $query->where('category_id', $request->get('category_id'));
        }

        // Filter by publisher
        if ($request->filled('publisher_id')) {
            $query->where('publisher_id', $request->get('publisher_id'));
        }

        // Filter by format
        if ($request->filled('format_id')) {
            $query->whereHas('variants', function ($q) use ($request) {
                $q->where('format_id', $request->get('format_id'));
            });
        }

        // Filter by language
        if ($request->filled('language_id')) {
            $query->whereHas('variants', function ($q) use ($request) {
                $q->where('language_id', $request->get('language_id'));
            });
        }

        // Filter by status
        if ($request->filled('status')) {
            $status = $request->get('status');
            switch ($status) {
                case 'hot':
                    $query->where('is_hot', true);
                    break;
                case 'new':
                    $query->where('is_new', true);
                    break;
                case 'best_seller':
                    $query->where('is_best_seller', true);
                    break;
                case 'recommended':
                    $query->where('is_recommended', true);
                    break;
                case 'featured':
                    $query->where('is_featured', true);
                    break;
                case 'promotion':
                    $query->where('is_promotion', true);
                    break;
            }
        }

        // Filter by price range
        if ($request->filled('min_price') || $request->filled('max_price')) {
            $query->whereHas('variants', function ($q) use ($request) {
                if ($request->filled('min_price')) {
                    $q->where('price', '>=', $request->get('min_price'));
                }
                if ($request->filled('max_price')) {
                    $q->where('price', '<=', $request->get('max_price'));
                }
            });
        }

        // Xử lý sắp xếp
        $sort = $request->get('sort', 'default');
        switch ($sort) {
            case 'price_low_to_high':
                $query->orderByRaw('(SELECT MIN(price) FROM product_variants WHERE product_id = products.id) ASC');
                break;
            case 'price_high_to_low':
                $query->orderByRaw('(SELECT MAX(price) FROM product_variants WHERE product_id = products.id) DESC');
                break;
            case 'latest':
                $query->orderBy('created_at', 'desc');
                break;
            case 'rating':
                $query->withAvg('ratings', 'rating')
                    ->orderByDesc('ratings_avg_rating');
                break;
            default:
                $query->orderBy('id', 'desc');
                break;
        }

        $products = $query->paginate(20)->withQueryString();

        // Get filter options with counts
        $categories = Category::active()
            ->withCount(['products' => function ($query) {
                $query->where('is_active', true);
            }])
            ->get();

        $publishers = Publisher::active()
            ->withCount(['products' => function ($query) {
                $query->where('is_active', true);
            }])
            ->get();

        $formats = Format::active()
            ->withCount(['productVariants' => function ($query) {
                $query->whereHas('product', function ($q) {
                    $q->where('is_active', true);
                });
            }])
            ->get();

        $languages = Language::active()
            ->withCount(['productVariants' => function ($query) {
                $query->whereHas('product', function ($q) {
                    $q->where('is_active', true);
                });
            }])
            ->get();

        // Get status counts
        $statusCounts = [
            'hot' => Product::where('is_active', true)->where('is_hot', true)->count(),
            'new' => Product::where('is_active', true)->where('is_new', true)->count(),
            'best_seller' => Product::where('is_active', true)->where('is_best_seller', true)->count(),
            'recommended' => Product::where('is_active', true)->where('is_recommended', true)->count(),
            'featured' => Product::where('is_active', true)->where('is_featured', true)->count(),
            'promotion' => Product::where('is_active', true)->where('is_promotion', true)->count(),
        ];

        // Get price range
        $priceRange = [
            'min' => DB::table('product_variants')
                ->join('products', 'products.id', '=', 'product_variants.product_id')
                ->where('products.is_active', true)
                ->min('price') ?? 0,
            'max' => DB::table('product_variants')
                ->join('products', 'products.id', '=', 'product_variants.product_id')
                ->where('products.is_active', true)
                ->max('price') ?? 0,
        ];

        return view('client.products.index', compact(
            'products',
            'sort',
            'categories',
            'publishers',
            'formats',
            'languages',
            'statusCounts',
            'priceRange'
        ));
    }
}