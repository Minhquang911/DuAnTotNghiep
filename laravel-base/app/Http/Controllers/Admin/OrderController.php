<?php

namespace App\Http\Controllers\Admin;

use App\Models\Order;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use App\Jobs\SendOrderStatusEmail;

class OrderController extends Controller
{
    public function index(Request $request)
    {
        $query = Order::with(['user', 'orderItems.product'])
            ->when($request->filled('search'), function ($q) use ($request) {
                $search = $request->search;
                $q->where(function ($q) use ($search) {
                    $q->where('order_code', 'like', "%{$search}%")
                        ->orWhere('customer_name', 'like', "%{$search}%")
                        ->orWhere('customer_email', 'like', "%{$search}%")
                        ->orWhere('customer_phone', 'like', "%{$search}%");
                });
            })
            ->when($request->filled('status'), fn($q) => $q->where('status', $request->status))
            ->when($request->filled('payment_status'), fn($q) => $q->where('payment_status', $request->payment_status))
            ->when($request->filled('payment_method'), fn($q) => $q->where('payment_method', $request->payment_method))
            ->when($request->filled('date_from'), fn($q) => $q->whereDate('created_at', '>=', $request->date_from))
            ->when($request->filled('date_to'), fn($q) => $q->whereDate('created_at', '<=', $request->date_to))
            ->latest();

        $orders = $query->paginate(10)->withQueryString();

        // Áp dụng điều kiện cho thống kê
        $filteredQuery = Order::query()
            ->when($request->filled('status'), fn($q) => $q->where('status', $request->status))
            ->when($request->filled('payment_status'), fn($q) => $q->where('payment_status', $request->payment_status))
            ->when($request->filled('payment_method'), fn($q) => $q->where('payment_method', $request->payment_method))
            ->when($request->filled('date_from'), fn($q) => $q->whereDate('created_at', '>=', $request->date_from))
            ->when($request->filled('date_to'), fn($q) => $q->whereDate('created_at', '<=', $request->date_to));

        $orderStats = [
            'total' => (clone $filteredQuery)->count(),
            'pending' => (clone $filteredQuery)->where('status', 'pending')->count(),
            'processing' => (clone $filteredQuery)->where('status', 'processing')->count(),
            'delivering' => (clone $filteredQuery)->where('status', 'delivering')->count(),
            'completed' => (clone $filteredQuery)->where('status', 'completed')->count(),
            'finished' => (clone $filteredQuery)->where('status', 'finished')->count(),
            'cancelled' => (clone $filteredQuery)->where('status', 'cancelled')->count(),
            'failed' => (clone $filteredQuery)->where('status', 'failed')->count(),
            'pending_paid' => (clone $filteredQuery)->where('status', 'pending')->where('payment_status', 'paid')->count(),
            'pending_unpaid' => (clone $filteredQuery)->where('status', 'pending')->where('payment_status', 'unpaid')->count(),
        ];

        return view('admin.orders.index', compact('orders', 'orderStats'));
    }


    public function show(Order $order)
    {
        $order->load(['user', 'orderItems.product', 'orderItems.productVariant']);
        return view('admin.orders.show', compact('order'));
    }

    public function confirm(Order $order)
    {
        try {
            DB::beginTransaction();

            // Kiểm tra trạng thái hiện tại
            if ($order->status !== 'pending') {
                throw new \Exception('Chỉ có thể xác nhận đơn hàng đang ở trạng thái chờ xác nhận');
            }

            $oldStatus = $order->status;

            // Cập nhật trạng thái đơn hàng
            $order->update([
                'status' => 'processing',
            ]);

            // Gửi email thông báo
            SendOrderStatusEmail::dispatch($order, $oldStatus, 'processing');

            DB::commit();
            return response()->json([
                'success' => true,
                'message' => 'Xác nhận đơn hàng thành công'
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 400);
        }
    }

    public function cancel(Order $order)
    {
        try {
            DB::beginTransaction();

            // Kiểm tra trạng thái hiện tại
            if (!in_array($order->status, ['pending'])) {
                throw new \Exception('Chỉ có thể hủy đơn hàng ở trạng thái chờ xử lý');
            }

            // Validate lý do hủy
            $cancelReason = request('cancel_reason');
            if (empty($cancelReason)) {
                throw new \Exception('Vui lòng nhập lý do hủy đơn hàng');
            }

            $oldStatus = $order->status;

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

    public function updateStatus(Request $request, Order $order)
    {
        try {
            DB::beginTransaction();

            $oldStatus = $order->status;

            // Cập nhật trạng thái đơn hàng
            if ($request->has('status')) {
                $validStatuses = ['processing', 'delivering', 'completed', 'failed', 'finished'];
                if (!in_array($request->status, $validStatuses)) {
                    throw new \Exception('Trạng thái không hợp lệ');
                }

                // Kiểm tra luồng trạng thái
                $statusFlow = [
                    'pending' => ['processing'],
                    'processing' => ['delivering'],
                    'delivering' => ['completed', 'failed'],
                    'completed' => ['finished'],
                    'failed' => ['delivering']
                ];

                if (!isset($statusFlow[$order->status]) || !in_array($request->status, $statusFlow[$order->status])) {
                    throw new \Exception('Không thể chuyển sang trạng thái này');
                }

                $order->status = $request->status;

                // Tự động cập nhật thanh toán cho đơn COD khi hoàn thành
                if ($request->status === 'completed' && $order->payment_method === 'cod') {
                    $order->payment_status = 'paid';
                    $order->paid_at = now();
                }

                // Gửi email thông báo khi thay đổi trạng thái
                SendOrderStatusEmail::dispatch($order, $oldStatus, $request->status);
            }

            // Cập nhật trạng thái thanh toán
            if ($request->has('payment_status')) {
                if ($request->payment_status === 'paid' && $order->payment_status === 'unpaid') {
                    $order->payment_status = 'paid';
                    $order->paid_at = $request->paid_at ? Carbon::parse($request->paid_at) : now();
                } else {
                    throw new \Exception('Không thể cập nhật trạng thái thanh toán');
                }
            }

            $order->save();
            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Cập nhật trạng thái thành công'
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 400);
        }
    }

    // Thêm command để tự động hủy đơn hàng chưa thanh toán sau 24h
    public function cancelUnpaidOrders()
    {
        try {
            DB::beginTransaction();

            // Tìm các đơn hàng online (bank_transfer) chưa thanh toán và đã quá 24h
            $unpaidOrders = Order::where('payment_method', 'bank_transfer')
                ->where('payment_status', 'unpaid')
                ->where('status', 'pending')
                ->where('created_at', '<=', Carbon::now()->subHours(24))
                ->get();

            $count = 0;
            foreach ($unpaidOrders as $order) {
                $order->update([
                    'status' => 'cancelled',
                    'cancelled_at' => now()
                ]);
                $count++;
            }

            DB::commit();
            return response()->json([
                'success' => true,
                'message' => "Đã hủy {$count} đơn hàng chưa thanh toán sau 24h"
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 400);
        }
    }
}