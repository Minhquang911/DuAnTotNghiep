<?php

namespace App\Http\Controllers\Client;

use App\Models\Cart;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Promotion;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class OrderController extends Controller
{
    public function add(Request $request)
    {
        $user = Auth::user();
        $cart = Cart::where('user_id', $user->id)->with('items.productVariant.product')->first();
        $totalPrice = $cart ? $cart->totalPrice() : 0;

        $promotionCode = $request->get('promotion_code');
        $promotion = null;
        $discountAmount = 0;

        if ($promotionCode) {
            $promotion = Promotion::where('code', $promotionCode)->first();
            $discountAmount = 0; // Mặc định là 0

            if (
                !$promotion
                || !$promotion->is_active
                || ($promotion->start_date && $promotion->start_date->isFuture())
                || ($promotion->end_date && $promotion->end_date->isPast())
                || ($promotion->usage_limit !== null && $promotion->used_count >= $promotion->usage_limit)
                || ($promotion->min_order_value && $totalPrice < $promotion->min_order_value)
            ) {
                $discountAmount = 0;
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

        return view('client.order.add', compact('user', 'cart', 'totalPrice', 'discountAmount', 'finalTotal', 'promotion'));
    }

    public function store(Request $request)
    {
        // Lấy người dùng hiện tại
        $user = Auth::user();

        $coupon_code = $request->get('promotion_code');

        // Lấy giỏ hàng
        $cart = Cart::where('user_id', $user->id)->with('items.productVariant.product')->first();

        if (!$cart || $cart->items->isEmpty()) {
            return redirect()->back()->with('error', 'Giỏ hàng trống.');
        }

        DB::beginTransaction();
        try {
            $orderCreate = [
                'order_code'        => 'ORD-' . Str::upper(Str::random(12)),
                'user_id'           => Auth::id(),
                'customer_name'     => $request->input('customer_name'),
                'customer_email'    => $request->input('customer_email'),
                'customer_phone'    => $request->input('customer_phone'),
                'customer_address'  => $request->input('customer_address'),
                'customer_province' => $request->input('customer_province_name'),
                'customer_district' => $request->input('customer_district_name'),
                'customer_ward'     => $request->input('customer_ward_name'),
                'total_amount'      => $request->input('total_amount'),
                'shipping_fee'      => $shippingFee ?? 0,
                'discount_amount'   => $request->input('discount_amount') ?? 0,
                'amount_due'        => $request->input('amount_due') - ($discount ?? 0),
                'payment_method'    => $request->input('payment_method'),
                'payment_status'    => Order::PAYMENT_STATUS_UNPAID,
                'status'            => Order::STATUS_PENDING,
                'coupon_code'       => $request->input('coupon_code') ?? null,
                'note'              => $request->input('notes') ?? null,
                'ordered_at'        => now()
            ];
            // Tạo đơn hàng
            $order = Order::create($orderCreate);
            
            // Tạo từng mục đơn hàng từ giỏ hàng
            foreach ($cart->items as $item) {
                $orderItem = [
                    'order_id' => $order->id ?? null,
                    'product_id' => $item->productVariant->id,
                    'product_variant_id' => $item->variant_id,
                    'quantity' => $item->quantity,
                    'price' => $item->productVariant->promotion_price ?? $item->productVariant->price,
                    'sku' => $item->productVariant->sku,
                    'product_name' => $item->productVariant->product->title,
                    'product_variant_name' => $item->productVariant->format->name . ' - ' . $item->productVariant->language->name,
                    'product_image' => $item->productVariant->product->cover_image ?? null,
                    'total' => ($item->productVariant->promotion_price ?? $item->productVariant->price) * $item->quantity,
                ];
                OrderItem::create($orderItem);
            }
            // Xóa giỏ hàng sau khi đặt hàng
            $cart->items()->delete();

            DB::commit();

            return redirect()->route('orders.success');
        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->back()->with('error', 'Đặt hàng thất bại: ' . $e->getMessage());
        }
    }
}