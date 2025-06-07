<?php

namespace App\Http\Controllers\Admin;

use App\Models\Order;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

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
            ->when($request->filled('status'), function ($q) use ($request) {
                $q->where('status', $request->status);
            })
            ->when($request->filled('payment_status'), function ($q) use ($request) {
                $q->where('payment_status', $request->payment_status);
            })
            ->when($request->filled('payment_method'), function ($q) use ($request) {
                $q->where('payment_method', $request->payment_method);
            })
            ->when($request->filled('date_from'), function ($q) use ($request) {
                $q->whereDate('created_at', '>=', $request->date_from);
            })
            ->when($request->filled('date_to'), function ($q) use ($request) {
                $q->whereDate('created_at', '<=', $request->date_to);
            })
            ->latest();

        $orders = $query->paginate(10)->withQueryString();

        $orderStats = [
            'total' => Order::count(),
            'pending' => Order::where('status', 'pending')->count(),
            'processing' => Order::where('status', 'processing')->count(),
            'delivering' => Order::where('status', 'delivering')->count(),
            'completed' => Order::where('status', 'completed')->count(),
            'finished' => Order::where('status', 'finished')->count(),
            'cancelled' => Order::where('status', 'cancelled')->count(),
            'failed' => Order::where('status', 'failed')->count(),
            'pending_paid' => Order::where('status', 'pending')
                ->where('payment_status', 'paid')
                ->count(),
            'pending_unpaid' => Order::where('status', 'pending')
                ->where('payment_status', 'unpaid')
                ->count(),
        ];

        return view('admin.orders.index', compact('orders', 'orderStats'));
    }

    public function confirm(Order $order)
    {
        try {
            DB::beginTransaction();
            
            // Kiểm tra trạng thái hiện tại
            if ($order->status !== 'pending') {
                throw new \Exception('Chỉ có thể xác nhận đơn hàng đang ở trạng thái chờ xác nhận');
            }

            // Cập nhật trạng thái đơn hàng
            $order->update([
                'status' => 'processing',
                // 'processed_at' => now() // Sử dụng để truy vết
            ]);

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
            if (!in_array($order->status, ['pending', 'processing'])) {
                throw new \Exception('Không thể hủy đơn hàng ở trạng thái này');
            }

            // Cập nhật trạng thái đơn hàng
            $order->update([
                'status' => 'cancelled',
                // 'cancelled_at' => now() // Sử dụng để truy vết
            ]);

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
                    'failed' => ['delivering'] // Thêm khả năng chuyển từ failed về delivering
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
} 