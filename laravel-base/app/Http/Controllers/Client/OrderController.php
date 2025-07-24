<?php

namespace App\Http\Controllers\Client;

use App\Models\Cart;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Promotion;
use App\Services\ZaloPayService;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Mail\OrderSuccessMail;
use App\Jobs\SendOrderStatusEmail;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class OrderController extends Controller
{
    public function index()
    {
        $orders = Order::where('user_id', Auth::id())
            ->orderBy('ordered_at', 'desc')
            ->paginate(5);

        return view('client.order.index', compact('orders'));
    }

    public function show(Order $order)
    {
        $order->load(['user', 'orderItems.product', 'orderItems.productVariant']);
        return view('client.order.show', compact('order'));
    }

    public function cancel(Order $order)
    {
        try {
            DB::beginTransaction();

            // Kiểm tra trạng thái hiện tại
            if (!in_array($order->status, ['pending', 'processing'])) {
                throw new \Exception('Không thể hủy đơn hàng ở trạng thái này');
            }

            // Validate lý do hủy
            $cancelReason = request('cancel_reason');
            if (empty($cancelReason)) {
                throw new \Exception('Vui lòng nhập lý do hủy đơn hàng');
            }

            $oldStatus = $order->status;

            // Cộng lại số lượng tồn kho cho từng sản phẩm trong đơn hàng
            foreach ($order->orderItems as $item) {
                if ($item->productVariant) {
                    $item->productVariant->stock += $item->quantity;
                    $item->productVariant->save();
                }
            }

            // Nếu có mã giảm giá, trừ lượt dùng
            if ($order->coupon_code) {
                $promotion = \App\Models\Promotion::where('code', $order->coupon_code)->first();
                if ($promotion && $promotion->used_count > 0) {
                    $promotion->used_count -= 1;
                    $promotion->save();
                }
            }

            // Cập nhật trạng thái đơn hàng
            $order->update([
                'status' => 'cancelled',
                'cancel_reason' => $cancelReason,
                'cancelled_at' => now()
            ]);

            // Gửi email thông báo
            SendOrderStatusEmail::dispatch($order, $oldStatus, 'cancelled');

            DB::commit();
            return response()->json([
                'success' => true,
                'message' => 'Hủy đơn hàng thành công'
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 400);
        }
    }

    /**
     * Xác nhận đã nhận hàng - chỉ cho phép khi trạng thái là 'completed'
     */
    public function confirmReceived(Order $order)
    {
        if (!in_array($order->status, ['completed'])) {
            return response()->json([
                'success' => false,
                'message' => 'Chỉ có thể xác nhận khi đơn hàng ở trạng thái đã giao.'
            ], 400);
        }
        $order->status = 'finished';
        $order->save();
        // Có thể gửi email thông báo ở đây nếu cần
        return response()->json([
            'success' => true,
            'message' => 'Xác nhận nhận hàng thành công!'
        ]);
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
        $discount = 0;
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

            // Tính số tiền giảm
            if ($promotion->discount_type === 'percent') {
                $discount = $totalPrice * ($promotion->discount_value / 100);
                if ($promotion->max_discount_value && $discount > $promotion->max_discount_value) {
                    $discount = $promotion->max_discount_value;
                }
            } else { // fixed
                $discount = $promotion->discount_value;
                if ($promotion->max_discount_value && $discount > $promotion->max_discount_value) {
                    $discount = $promotion->max_discount_value;
                }
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

        // Tính toán phí vận chuyển (nếu có)
        $shippingFee = 0; // Có thể tính dựa trên địa chỉ

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
                'shipping_fee'      => $shippingFee,
                'discount_amount'   => $discount,
                'amount_due'        => $request->input('amount_due') - $discount,
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
                    'order_id'              => $order->id,
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

            // Xóa các sản phẩm đã mua khỏi giỏ hàng
            if (!empty($selectedItems)) {
                $cart->items()->whereIn('id', $selectedItems)->delete();
            }

            DB::commit();

            // Xử lý thanh toán theo phương thức
            if ($request->input('payment_method') === 'bank_transfer') {
                try {
                    Log::info('Starting ZaloPay payment process');
                    
                    // Sử dụng ZaloPayService
                    $zaloPayService = new ZaloPayService();
                    Log::info('ZaloPayService created successfully');
                    
                    // Chuẩn bị dữ liệu cho ZaloPay theo đúng format
                    $orderData = [
                        'amount' => $order->amount_due,
                        'description' => "Thanh toán đơn hàng #{$order->order_code}",
                        'items' => $order->orderItems->map(function($item) {
                            return [
                                'itemid' => (string)$item->product_id,
                                'itemname' => $item->product_name,
                                'itemprice' => (int)$item->price, // ZaloPay yêu cầu integer
                                'itemquantity' => (int)$item->quantity
                            ];
                        })->toArray()
                    ];
                    
                    Log::info('Order data prepared for ZaloPay:', $orderData);
                    
                    $result = $zaloPayService->createPaymentUrl($orderData);
                    Log::info('ZaloPay createPaymentUrl result:', $result);

                    // Nếu ZaloPay trả về lỗi
                    if (!$result['success']) {
                        Log::error('ZaloPay Create Order Failed:', $result);
                        return response()->json([
                            'success' => false,
                            'message' => 'Lỗi tạo thanh toán ZaloPay: ' . ($result['message'] ?? 'Không xác định')
                        ]);
                    }
                    
                    // Lưu thông tin ZaloPay vào order
                    $order->update([
                        'zalopay_app_trans_id' => $result['app_trans_id'],
                        'zalopay_zp_trans_token' => $result['zp_trans_token'] ?? null
                    ]);
                    
                    // Lưu user session để preserve sau khi thanh toán
                    if (Auth::check()) {
                        session(['payment_user_id' => Auth::id()]);
                        session(['payment_order_id' => $order->id]);
                        session()->save(); // Force save session
                    }
                    
                    return response()->json([
                        'success' => true,
                        'redirect_url' => $result['payment_url']
                    ]);
                } catch (\Exception $e) {
                    Log::error('ZaloPay Exception:', [
                        'message' => $e->getMessage(),
                        'trace' => $e->getTraceAsString()
                    ]);
                    return response()->json([
                        'success' => false,
                        'message' => 'Có lỗi xảy ra khi xử lý thanh toán: ' . $e->getMessage()
                    ]);
                }
            } else {
                // COD - Gửi mail thông báo đặt hàng thành công
                Mail::to($order->customer_email)->queue(new OrderSuccessMail($order));

                if ($request->ajax()) {
                    return response()->json(['success' => true, 'redirect_url' => route('orders.success')]);
                }
                return redirect()->route('orders.success');
            }
        } catch (\Exception $e) {
            DB::rollback();
            if ($request->ajax()) {
                return response()->json(['success' => false, 'message' => 'Đặt hàng thất bại: ' . $e->getMessage()]);
            }
            return redirect()->back()->with('error', 'Đặt hàng thất bại: ' . $e->getMessage());
        }
    }

    public function continuePayment(Order $order)
    {
        if (
            $order->payment_status !== Order::PAYMENT_STATUS_UNPAID ||
            $order->payment_method !== 'bank_transfer'
        ) {
            return response()->json(['success' => false, 'message' => 'Không thể thanh toán lại đơn hàng này.']);
        }

        try {
            $zaloPayService = new ZaloPayService();
            
            // Chuẩn bị dữ liệu cho ZaloPay theo đúng format
            $orderData = [
                'amount' => $order->amount_due,
                'description' => "Thanh toán lại đơn hàng #{$order->order_code}",
                'items' => $order->orderItems->map(function($item) {
                    return [
                        'itemid' => (string)$item->product_id,
                        'itemname' => $item->product_name,
                        'itemprice' => (int)$item->price,
                        'itemquantity' => (int)$item->quantity
                    ];
                })->toArray()
            ];
            
            $result = $zaloPayService->createPaymentUrl($orderData);
            
            if ($result['success']) {
                // Cập nhật thông tin ZaloPay mới vào order
                $order->update([
                    'zalopay_app_trans_id' => $result['app_trans_id'],
                    'zalopay_zp_trans_token' => $result['zp_trans_token'] ?? null
                ]);
                
                // Lưu user session để preserve sau khi thanh toán
                if (Auth::check()) {
                    session(['payment_user_id' => Auth::id()]);
                    session(['payment_order_id' => $order->id]);
                    session()->save(); // Force save session
                }
                
                return response()->json([
                    'success' => true,
                    'redirect_url' => $result['payment_url']
                ]);
            } else {
                return response()->json([
                    'success' => false, 
                    'message' => 'Lỗi tạo thanh toán ZaloPay: ' . $result['message']
                ]);
            }
        } catch (\Exception $e) {
            Log::error('Continue Payment Exception:', [
                'order_id' => $order->id,
                'message' => $e->getMessage()
            ]);
            
            return response()->json([
                'success' => false,
                'message' => 'Có lỗi xảy ra khi tạo thanh toán: ' . $e->getMessage()
            ]);
        }
    }

    public function handleZaloPayReturn(Request $request)
    {
        // ZaloPay trả về với parameters: amount, appid, apptransid, pmcid, bankcode, status, checksum
        $appTransId = $request->get('apptransid');
        $status = $request->get('status');
        $amount = $request->get('amount');
        $checksum = $request->get('checksum');

        // Debug: Log all request data
        Log::info('ZaloPay Return Request:', $request->all());
        Log::info('ZaloPay Return - App Trans ID:', ['app_trans_id' => $appTransId]);
        
        // Find order by zalopay_app_trans_id
        $order = Order::where('zalopay_app_trans_id', $appTransId)->first();
        
        if (!$order) {
            Log::error('ZaloPay Return - Order not found:', ['app_trans_id' => $appTransId]);
            return redirect()->route('home')->with('error', 'Không tìm thấy đơn hàng!');
        }

        // Restore user session nếu bị mất do redirect
        $paymentUserId = session('payment_user_id');
        $paymentOrderId = session('payment_order_id');
        
        if ($paymentUserId && $paymentOrderId && $paymentOrderId == $order->id && !Auth::check()) {
            // User session bị mất, cần restore
            Log::info('ZaloPay Return - Restoring user session', [
                'user_id' => $paymentUserId,
                'order_id' => $paymentOrderId
            ]);
            
            $user = User::find($paymentUserId);
            if ($user) {
                Auth::login($user, true); // Login với remember = true
                Log::info('ZaloPay Return - User session restored successfully');
            }
        }

        // Query order status from ZaloPay để đảm bảo chính xác
        $zaloPayService = new ZaloPayService();
        $queryResult = $zaloPayService->queryOrder($appTransId);

        try {
            DB::beginTransaction();

            if ($queryResult['success'] && $queryResult['data']['return_code'] == 1) {
                // Payment successful
                $order->update([
                    'payment_status' => Order::PAYMENT_STATUS_PAID,
                    'zalopay_response_code' => '1',
                    'paid_at' => now()
                ]);

                // Gửi mail thông báo đặt hàng thành công
                Mail::to($order->customer_email)->queue(new OrderSuccessMail($order));

                DB::commit();
                
                // Cleanup session data sau khi thanh toán thành công
                session()->forget(['payment_user_id', 'payment_order_id']);

                // Kiểm tra xem người dùng có đăng nhập không
                if (Auth::check() && Auth::id() == $order->user_id) {
                    Log::info('ZaloPay Return - Redirecting to order show page (authenticated user)');
                    return redirect()->route('orders.show', $order->id)->with('success', 'Thanh toán thành công!');
                } else {
                    Log::info('ZaloPay Return - Redirecting to home (guest user)');
                    return redirect()->route('home')->with('success', 'Thanh toán thành công! Vui lòng đăng nhập để xem chi tiết đơn hàng.');
                }
            } else {
                // Payment failed or pending  
                $returnCode = $queryResult['data']['return_code'] ?? '-1';
                $order->update([
                    'payment_status' => Order::PAYMENT_STATUS_UNPAID,
                    'zalopay_response_code' => $returnCode
                ]);

                DB::commit();
                
                // Cleanup session data
                session()->forget(['payment_user_id', 'payment_order_id']);

                // Kiểm tra xem người dùng có đăng nhập không
                if (Auth::check() && Auth::id() == $order->user_id) {
                    Log::info('ZaloPay Return - Payment failed, redirecting to order show page (authenticated user)');
                    return redirect()->route('orders.show', $order->id)->with('error', 'Thanh toán thất bại: ' . $zaloPayService->getErrorMessage($returnCode));
                } else {
                    Log::info('ZaloPay Return - Payment failed, redirecting to home (guest user)');
                    return redirect()->route('home')->with('error', 'Thanh toán thất bại: ' . $zaloPayService->getErrorMessage($returnCode));
                }
            }
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('ZaloPay Return Exception:', [
                'message' => $e->getMessage(),
                'app_trans_id' => $appTransId
            ]);
            
            if (Auth::check() && Auth::id() == $order->user_id) {
                return redirect()->route('orders.show', $order->id)->with('error', 'Có lỗi xảy ra khi xử lý thanh toán!');
            } else {
                return redirect()->route('home')->with('error', 'Có lỗi xảy ra khi xử lý thanh toán!');
            }
        }
    }

    public function handleZaloPayCallback(Request $request)
    {
        $zaloPayService = new ZaloPayService();
        
        // Debug: Log callback data
        Log::info('ZaloPay Callback Request:', $request->all());
        
        // Validate callback data
        $validation = $zaloPayService->validateCallback($request->all());
        
        if (!$validation['success']) {
            Log::error('ZaloPay Callback - Validation failed:', ['message' => $validation['message']]);
            return response()->json(['return_code' => -1, 'return_message' => 'Invalid signature']);
        }

        $callbackData = $validation['data'];
        $appTransId = $callbackData['app_trans_id'];
        $amount = $callbackData['amount'];

        Log::info('ZaloPay Callback - Processing transaction:', ['app_trans_id' => $appTransId]);

        // Find order by zalopay_app_trans_id
        $order = Order::where('zalopay_app_trans_id', $appTransId)->first();
        
        if (!$order) {
            Log::error('ZaloPay Callback - Order not found', ['app_trans_id' => $appTransId]);
            return response()->json(['return_code' => -1, 'return_message' => 'Order not found']);
        }

        // Check amount
        if ($order->amount_due != $amount) {
            Log::error('ZaloPay Callback - Amount mismatch', [
                'expected' => $order->amount_due,
                'received' => $amount
            ]);
            return response()->json(['return_code' => -1, 'return_message' => 'Invalid amount']);
        }

        // Check if already processed
        if ($order->payment_status === Order::PAYMENT_STATUS_PAID) {
            Log::info('ZaloPay Callback - Order already paid', ['order_id' => $order->id]);
            return response()->json(['return_code' => 1, 'return_message' => 'Order already confirmed']);
        }

        try {
            // Update order status to paid
            $order->update([
                'payment_status' => Order::PAYMENT_STATUS_PAID,
                'zalopay_response_code' => '1',
                'paid_at' => now(),
                'zalopay_embed_data' => json_encode($callbackData)
            ]);

            Log::info('ZaloPay Callback - Order status updated to PAID', ['order_id' => $order->id]);

            // Gửi mail thông báo đặt hàng thành công
            Mail::to($order->customer_email)->queue(new OrderSuccessMail($order));

            Log::info('ZaloPay Callback - Success email queued', ['order_id' => $order->id]);

            return response()->json(['return_code' => 1, 'return_message' => 'success']);
        } catch (\Exception $e) {
            Log::error('ZaloPay Callback - Error updating order status', [
                'order_id' => $order->id,
                'error' => $e->getMessage()
            ]);
            return response()->json(['return_code' => -1, 'return_message' => 'Unknown error']);
        }
    }


}