<?php

namespace App\Http\Controllers\Client;

use App\Models\Product;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Cart;
use App\Models\CartItem;
use Illuminate\Support\Facades\Auth;
use App\Models\ProductVariant;

class CartController extends Controller
{
    // Hiển thị giỏ hàng
    public function index()
    {
        return view('client.cart.index');
    }

    // Thêm sản phẩm vào giỏ
    public function add(Request $request)
    {
        $user = Auth::user();
        if (!$user) {
            return redirect()->route('login');
        }

        $rules = [
            'variant_id' => 'required|exists:product_variants,id',
            'quantity'   => 'required|integer|min:1',
        ];
        $messages = [
            'variant_id.required' => 'Bạn chưa chọn biến thể sản phẩm!',
            'variant_id.exists'   => 'Biến thể sản phẩm không tồn tại!',
            'quantity.required'   => 'Vui lòng nhập số lượng!',
            'quantity.integer'    => 'Số lượng phải là số nguyên!',
            'quantity.min'        => 'Số lượng phải lớn hơn 0!',
        ];

        $validated = $request->validate($rules, $messages);

        // Kiểm tra tồn kho
        $variant = \App\Models\ProductVariant::find($request->variant_id);
        if ($request->quantity > $variant->stock) {
            return back()
                ->withErrors(['quantity' => 'Số lượng vượt quá số lượng trong kho!'])
                ->withInput();
        }

        $cart = Cart::firstOrCreate(['user_id' => $user->id]);
        $cartItem = CartItem::where('cart_id', $cart->id)
            ->where('variant_id', $request->variant_id)
            ->first();

        if ($cartItem) {
            $newQuantity = $cartItem->quantity + $request->quantity;
            if ($newQuantity > $variant->stock) {
                return back()
                    ->withErrors(['quantity' => 'Tổng số lượng vượt quá số lượng trong kho!'])
                    ->withInput();
            }
            $cartItem->quantity = $newQuantity;
            $cartItem->save();
        } else {
            CartItem::create([
                'cart_id'    => $cart->id,
                'variant_id' => $request->variant_id,
                'quantity'   => $request->quantity,
            ]);
        }

        return redirect()->back()->with('success', 'Đã thêm sản phẩm vào giỏ hàng!');
    }

    // Cập nhật số lượng sản phẩm
    public function update(Request $request, $id)
    {
        $cart = session()->get('cart', []);

        if (isset($cart[$id])) {
            $cart[$id]['quantity'] = $request->quantity;
            session()->put('cart', $cart);
        }

        return redirect()->route('cart.index')->with('success', 'Đã cập nhật giỏ hàng!');
    }

    // Xóa sản phẩm khỏi giỏ
    public function remove($id)
    {
        $cart = session()->get('cart', []);

        if (isset($cart[$id])) {
            unset($cart[$id]);
            session()->put('cart', $cart);
        }

        return redirect()->route('cart.index')->with('success', 'Đã xóa sản phẩm khỏi giỏ hàng!');
    }

    // Xóa toàn bộ giỏ hàng
    public function clear()
    {
        session()->forget('cart');
        return redirect()->route('cart.index')->with('success', 'Đã xóa toàn bộ giỏ hàng!');
    }
}