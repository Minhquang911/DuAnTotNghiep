<?php

namespace App\Http\Controllers\Client;

use App\Models\Cart;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Promotion;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Mail\OrderSuccessMail;
use App\Jobs\SendOrderStatusEmail;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;

class OrderController extends Controller
{
    public function index()
    {
        $orders = Order::where('user_id', Auth::id())
                        ->orderBy('ordered_at', 'desc')
                        ->paginate(5);

        return view('client.order.index', compact('orders'));
    }

    public function add(Request $request)
    {
        $user = Auth::user();
        $selectedItems = json_decode($request->input('selected_items_json', '[]'), true);

        $cart = Cart::where('user_id', $user->id)
            ->with(['items' => function ($q) use ($selectedItems) {
                if (!empty($selectedItems)) {
                    $q->whereIn('id', $selectedItems);
                }
            }, 'items.productVariant.product'])
            ->first();
        // Tính lại tổng tiền chỉ dựa trên các item đã chọn
        $totalPrice = 0;
        if ($cart && $cart->items) {
            foreach ($cart->items as $item) {
                $price = $item->productVariant->promotion_price ?? $item->productVariant->price;
                $totalPrice += $price * $item->quantity;
            }
        }

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

        $selectedItems = $request->input('product_variant_id');

        // Lấy giỏ hàng
        $cart = Cart::where('user_id', $user->id)
            ->with(['items' => function ($q) use ($selectedItems) {
                if (!empty($selectedItems)) {
                    $q->whereIn('id', $selectedItems);
                }
            }, 'items.productVariant.product'])
            ->first();

        if (!$cart || $cart->items->isEmpty()) {
            return redirect()->back()->with('error', 'Giỏ hàng trống.');
        }

        // 1. Kiểm tra số lượng tồn kho
        foreach ($cart->items as $item) {
            if ($item->quantity > $item->productVariant->stock) {
                $msg = 'Sản phẩm "'
                    . $item->productVariant->product->title . ' - '
                    . $item->productVariant->format->name . ' - '
                    . $item->productVariant->language->name
                    . '" không đủ số lượng trong kho!';
                if ($request->ajax()) {
                    return response()->json(['success' => false, 'message' => $msg]);
                }
                return redirect()->back()->with('error', $msg);
            }
        }

        // 2. Kiểm tra điều kiện khuyến mãi (nếu có)
        $promotionCode = $request->input('coupon_code');
        if ($promotionCode) {
            $promotion = \App\Models\Promotion::where('code', $promotionCode)->first();
            $totalPrice = $cart->items->sum(function ($item) {
                return ($item->productVariant->promotion_price ?? $item->productVariant->price) * $item->quantity;
            });

            if (!$promotion) {
                $msg = 'Mã khuyến mãi không tồn tại!';
            } elseif (!$promotion->is_active) {
                $msg = 'Mã khuyến mãi không còn hiệu lực!';
            } elseif ($promotion->start_date && $promotion->start_date->isFuture()) {
                $msg = 'Mã khuyến mãi chưa bắt đầu!';
            } elseif ($promotion->end_date && $promotion->end_date->isPast()) {
                $msg = 'Mã khuyến mãi đã hết hạn!';
            } elseif ($promotion->usage_limit !== null && $promotion->used_count >= $promotion->usage_limit) {
                $msg = 'Mã khuyến mãi đã hết lượt sử dụng!';
            } elseif ($promotion->min_order_value && $totalPrice < $promotion->min_order_value) {
                $msg = 'Đơn hàng chưa đủ điều kiện áp dụng mã khuyến mãi!';
            }

            if (isset($msg)) {
                if ($request->ajax()) {
                    return response()->json(['success' => false, 'message' => $msg]);
                }
                return redirect()->back()->with('error', $msg);
            }
        }

        $request->validate([
            'customer_name' => 'required|string|max:255',
            'customer_email' => 'required|email',
            'customer_phone' => 'required',
            'customer_province' => 'required',
            'customer_district' => 'required',
            'customer_ward' => 'required',
            'customer_address' => 'required',
        ], [
            'customer_name.required' => 'Vui lòng nhập họ tên',
            'customer_email.required' => 'Vui lòng nhập email',
            'customer_email.email' => 'Email không đúng định dạng',
            'customer_phone.required' => 'Vui lòng nhập số điện thoại',
            'customer_province.required' => 'Vui lòng chọn Tỉnh/Thành phố',
            'customer_district.required' => 'Vui lòng chọn Quận/Huyện',
            'customer_ward.required' => 'Vui lòng chọn Phường/Xã',
            'customer_address.required' => 'Vui lòng nhập địa chỉ chi tiết',
        ]);

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
                    'order_id'              => $order->id ?? null,
                    'product_id'            => $item->productVariant->product->id,
                    'product_variant_id'    => $item->variant_id,
                    'quantity'              => $item->quantity,
                    'price'                 => $item->productVariant->promotion_price ?? $item->productVariant->price,
                    'sku'                   => $item->productVariant->sku,
                    'product_name'          => $item->productVariant->product->title,
                    'product_variant_name'  => $item->productVariant->format->name . ' - ' . $item->productVariant->language->name,
                    'product_image'         => $item->productVariant->product->cover_image ?? null,
                    'total'                 => ($item->productVariant->promotion_price ?? $item->productVariant->price) * $item->quantity,
                ];
                OrderItem::create($orderItem);

                // Trừ số lượng tồn kho của variant
                $productVariant = $item->productVariant;
                $productVariant->stock = max(0, $productVariant->stock - $item->quantity);
                $productVariant->save();
            }
            // Nếu có mã khuyến mãi, tăng used_count
            if (!empty($promotionCode) && isset($promotion)) {
                $promotion->used_count = ($promotion->used_count ?? 0) + 1;
                $promotion->save();
            }
            // Xóa giỏ hàng sau khi đặt hàng
            // Xóa các sản phẩm đã mua khỏi giỏ hàng
            if (!empty($selectedItems)) {
                $cart->items()->whereIn('id', $selectedItems)->delete();
            }

            // Gửi mail thông báo đặt hàng thành công qua queue
            Mail::to($order->customer_email)->queue(new OrderSuccessMail($order));

            DB::commit();

            if ($request->ajax()) {
                return response()->json(['success' => true, 'redirect_url' => route('orders.success')]);
            }
            return redirect()->route('orders.success');
        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->back()->with('error', 'Đặt hàng thất bại: ' . $e->getMessage());
        }
    }
}