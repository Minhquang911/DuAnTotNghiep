<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ProductVariant;
use App\Models\Product;
use App\Models\Format;
use App\Models\Language;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ProductVariantController extends Controller
{
    public function index(Request $request)
    {
        $query = ProductVariant::with(['product', 'format', 'language']);

        // Search
        if ($request->has('search')) {
            $search = $request->get('search');
            $query->where(function($q) use ($search) {
                $q->where('sku', 'like', "%{$search}%")
                  ->orWhereHas('product', function($q) use ($search) {
                      $q->where('title', 'like', "%{$search}%")
                        ->orWhere('author', 'like', "%{$search}%");
                  });
            });
        }

        // Filter by product
        if ($request->has('product_id')) {
            $query->where('product_id', $request->get('product_id'));
        }

        // Filter by format
        if ($request->has('format_id')) {
            $query->where('format_id', $request->get('format_id'));
        }

        // Filter by language
        if ($request->has('language_id')) {
            $query->where('language_id', $request->get('language_id'));
        }

        // Filter by status
        if ($request->has('status')) {
            $status = $request->get('status');
            if ($status === 'active') {
                $query->where('is_active', true);
            } elseif ($status === 'inactive') {
                $query->where('is_active', false);
            }
        }

        // Filter by stock
        if ($request->has('stock_status')) {
            $stockStatus = $request->get('stock_status');
            if ($stockStatus === 'in_stock') {
                $query->where('stock', '>', 0);
            } elseif ($stockStatus === 'out_of_stock') {
                $query->where('stock', 0);
            }
        }

        // Filter by price range
        if ($request->has('min_price')) {
            $query->where('price', '>=', $request->get('min_price'));
        }
        if ($request->has('max_price')) {
            $query->where('price', '<=', $request->get('max_price'));
        }

        // Filter by promotion
        if ($request->has('has_promotion')) {
            if ($request->get('has_promotion') === 'yes') {
                $query->whereNotNull('promotion_price')
                      ->where('promotion_price', '<', DB::raw('price'));
            }
        }

        // Sort
        $sort = $request->get('sort', 'latest');
        switch ($sort) {
            case 'sku_asc':
                $query->orderBy('sku', 'asc');
                break;
            case 'sku_desc':
                $query->orderBy('sku', 'desc');
                break;
            case 'price_asc':
                $query->orderBy('price', 'asc');
                break;
            case 'price_desc':
                $query->orderBy('price', 'desc');
                break;
            case 'stock_asc':
                $query->orderBy('stock', 'asc');
                break;
            case 'stock_desc':
                $query->orderBy('stock', 'desc');
                break;
            case 'oldest':
                $query->oldest();
                break;
            default:
                $query->latest();
        }

        $variants = $query->paginate(10)->withQueryString();

        // Get filter options
        $products = Product::active()->get();
        $formats = Format::active()->get();
        $languages = Language::active()->get();
        
        return view('admin.product-variants.index', compact(
            'variants',
            'products',
            'formats',
            'languages'
        ));
    }

    public function create()
    {
        $products = Product::active()->get();
        $formats = Format::active()->get();
        $languages = Language::active()->get();
        
        return view('admin.product-variants.create', compact('products', 'formats', 'languages'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'product_id' => 'required|exists:products,id',
            'format_id' => 'required|exists:formats,id',
            'language_id' => 'required|exists:languages,id',
            'sku' => 'required|string|max:50|unique:product_variants',
            'price' => 'required|numeric|min:0',
            'promotion_price' => 'nullable|numeric|min:0|lt:price',
            'stock' => 'required|integer|min:0',
            'is_active' => 'boolean',
        ]);

        // Check if variant already exists
        $exists = ProductVariant::where('product_id', $validated['product_id'])
            ->where('format_id', $validated['format_id'])
            ->where('language_id', $validated['language_id'])
            ->exists();

        if ($exists) {
            return back()
                ->withInput()
                ->withErrors(['error' => 'Biến thể sản phẩm này đã tồn tại.']);
        }

        ProductVariant::create($validated);

        return redirect()
            ->route('admin.product-variants.index')
            ->with('success', 'Biến thể sản phẩm đã được tạo thành công.');
    }

    public function edit(ProductVariant $productVariant)
    {
        $products = Product::active()->get();
        $formats = Format::active()->get();
        $languages = Language::active()->get();
        
        return view('admin.product-variants.edit', compact('productVariant', 'products', 'formats', 'languages'));
    }

    public function update(Request $request, ProductVariant $productVariant)
    {
        $validated = $request->validate([
            'product_id' => 'required|exists:products,id',
            'format_id' => 'required|exists:formats,id',
            'language_id' => 'required|exists:languages,id',
            'sku' => 'required|string|max:50|unique:product_variants,sku,' . $productVariant->id,
            'price' => 'required|numeric|min:0',
            'promotion_price' => 'nullable|numeric|min:0|lt:price',
            'stock' => 'required|integer|min:0',
            'is_active' => 'boolean',
        ]);

        // Check if variant already exists (excluding current variant)
        $exists = ProductVariant::where('product_id', $validated['product_id'])
            ->where('format_id', $validated['format_id'])
            ->where('language_id', $validated['language_id'])
            ->where('id', '!=', $productVariant->id)
            ->exists();

        if ($exists) {
            return back()
                ->withInput()
                ->withErrors(['error' => 'Biến thể sản phẩm này đã tồn tại.']);
        }

        $productVariant->update($validated);

        return redirect()
            ->route('admin.product-variants.index')
            ->with('success', 'Biến thể sản phẩm đã được cập nhật thành công.');
    }

    public function destroy(ProductVariant $productVariant)
    {
        $productVariant->delete();

        return redirect()
            ->route('admin.product-variants.index')
            ->with('success', 'Biến thể sản phẩm đã được xóa thành công.');
    }

    public function toggleStatus(ProductVariant $productVariant)
    {
        $productVariant->update(['is_active' => !$productVariant->is_active]);

        return response()->json([
            'success' => true,
            'message' => 'Trạng thái đã được cập nhật thành công.',
            'is_active' => $productVariant->is_active
        ]);
    }
} 