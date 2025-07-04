<?php

namespace App\Http\Controllers\Client;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Cart;
use App\Models\CartItem;
use Illuminate\Support\Facades\Auth;
use App\Models\ProductVariant;
use App\Models\Promotion;
use Illuminate\Support\Carbon;

class CartController extends Controller
{
    // Hiển thị giỏ hàng
    public function index(Request $request)
    {
        $cart = Cart::where('user_id', Auth::user()->id)->with('items.productVariant.product')->first();
        $cartItems = $cart ? $cart->items : collect();
        $totalPrice = $cart ? $cart->totalPrice() : 0;

        $promotionCode = $request->get('promotion_code');
        $promotion = null;
        $promotionError = null;
        $discountAmount = 0;

        if ($promotionCode) {
            $promotion = Promotion::where('code', $promotionCode)->first();

            if (!$promotion) {
                $promotionError = 'Mã khuyến mãi không tồn tại!';
            } elseif (!$promotion->is_active) {
                $promotionError = 'Mã khuyến mãi không còn hiệu lực!';
            } elseif ($promotion->start_date && $promotion->start_date->isFuture()) {
                $promotionError = 'Mã khuyến mãi chưa bắt đầu!';
            } elseif ($promotion->end_date && $promotion->end_date->isPast()) {
                $promotionError = 'Mã khuyến mãi đã hết hạn!';
            } elseif ($promotion->usage_limit !== null && $promotion->used_count >= $promotion->usage_limit) {
                $promotionError = 'Mã khuyến mãi đã hết lượt sử dụng!';
            } elseif ($promotion->min_order_value && $totalPrice < $promotion->min_order_value) {
                $promotionError = 'Đơn hàng chưa đủ điều kiện áp dụng mã khuyến mãi!';
            } else {
                // Tính số tiền giảm
                if ($promotion->discount_type === 'percent') {
                    $discountAmount = $totalPrice * ($promotion->discount_value / 100);
                    if ($promotion->max_discount_value && $discountAmount > $promotion->max_discount_value) {
                        $discountAmount = $promotion->max_discount_value;
                    }
                } else { // fixed
                    $discountAmount = $promotion->discount_value;
                    if ($promotion->max_discount_value && $discountAmount > $promotion->max_discount_value) {
                        $discountAmount = $promotion->max_discount_value;
                    }
                }
            }
        }

        // Tính tổng tiền cần thanh toán
        $finalTotal = max(0, $totalPrice - $discountAmount);

        return view('client.cart.index', compact(
            'cartItems',
            'totalPrice',
            'promotion',
            'promotionError',
            'discountAmount',
            'finalTotal'
        ));
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
        $variant = ProductVariant::find($request->variant_id);
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
    public function update(Request $request)
    {
        $request->validate([
            'item_id'  => 'required|exists:cart_items,id',
            'quantity' => 'required|integer|min:1',
        ]);

        $cartItem = CartItem::where('id', $request->item_id)
            ->whereHas('cart', function ($q) {
                $q->where('user_id', Auth::id());
            })->first();

        if (!$cartItem) {
            return response()->json(['success' => false, 'message' => 'Không tìm thấy sản phẩm trong giỏ hàng!']);
        }

        $variant = $cartItem->productVariant;
        if ($request->quantity > $variant->stock) {
            return response()->json(['success' => false, 'message' => 'Số lượng vượt quá tồn kho!']);
        }

        $cartItem->quantity = $request->quantity;
        $cartItem->save();

        // Tính lại tổng từng dòng và tổng giỏ hàng
        $itemTotal = ($variant->promotion_price ?? $variant->price) * $cartItem->quantity;
        $cart = $cartItem->cart;
        $cartTotal = $cart->totalPrice();

        // Tính lại giảm giá nếu có mã khuyến mãi
        $promotionCode = session('promotion_code'); // hoặc lấy từ request nếu bạn truyền lên
        $discountAmount = 0;
        $finalTotal = $cartTotal;

        if ($promotionCode) {
            $promotion = \App\Models\Promotion::where('code', $promotionCode)->first();
            if (
                $promotion && $promotion->is_active &&
                (!$promotion->start_date || $promotion->start_date->isPast()) &&
                (!$promotion->end_date || $promotion->end_date->isFuture()) &&
                ($promotion->usage_limit === null || $promotion->used_count < $promotion->usage_limit) &&
                (!$promotion->min_order_value || $cartTotal >= $promotion->min_order_value)
            ) {
                if ($promotion->discount_type === 'percent') {
                    $discountAmount = $cartTotal * ($promotion->discount_value / 100);
                    if ($promotion->max_discount_value && $discountAmount > $promotion->max_discount_value) {
                        $discountAmount = $promotion->max_discount_value;
                    }
                } else { // fixed
                    $discountAmount = $promotion->discount_value;
                    if ($promotion->max_discount_value && $discountAmount > $promotion->max_discount_value) {
                        $discountAmount = $promotion->max_discount_value;
                    }
                }
            }
        }
        $finalTotal = max(0, $cartTotal - $discountAmount);

        return response()->json([
            'success' => true,
            'message' => 'Cập nhật thành công!',
            'item_total' => number_format($itemTotal),
            'cart_total' => number_format($cartTotal),
            'final_total' => number_format($finalTotal),
        ]);
    }

    // Xóa sản phẩm khỏi giỏ
    public function remove($id)
    {
        $cart = Cart::where('user_id', Auth::id())->first();

        if (!$cart) {
            return response()->json([
                'success' => false,
                'message' => 'Giỏ hàng không tồn tại!'
            ]);
        }

        $cartItem = CartItem::where('cart_id', $cart->id)->where('id', $id)->first();

        if ($cartItem) {
            $cartItem->delete();
            return response()->json([
                'success' => true,
                'message' => 'Đã xóa sản phẩm khỏi giỏ hàng!',
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => 'Sản phẩm không tồn tại trong giỏ hàng!'
        ]);
    }


    public function clear()
    {
        $cart = Cart::where('user_id', Auth::id())->first();

        if ($cart) {
            $cart->items()->delete(); // Xóa tất cả CartItem thuộc cart
            return response()->json([
                'success' => true,
                'message' => 'Đã xóa toàn bộ giỏ hàng!'
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => 'Không tìm thấy giỏ hàng!'
        ]);
    }
}