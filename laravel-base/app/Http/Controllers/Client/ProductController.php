<?php

namespace App\Http\Controllers\Client;

use App\Models\Product;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ProductController extends Controller
{
    public function index(Request $request) 
    {
        // Lấy tất cả sản phẩm, có thể phân trang nếu muốn
        $products = Product::where('is_active', true) // chỉ lấy sản phẩm đang hoạt động
            ->orderByDesc('id')
            ->paginate(24); // 24 sản phẩm/trang

        return view('client.products.index', compact('products'));
    }
}