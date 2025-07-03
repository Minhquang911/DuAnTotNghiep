<?php

namespace App\Http\Controllers\Client;

use App\Models\Product;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class CartController extends Controller
{
    // Hiển thị giỏ hàng
    public function index()
    {
        return view('client.cart.index');
    }

   
}