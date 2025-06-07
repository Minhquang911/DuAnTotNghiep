<?php

namespace App\Http\Controllers\Admin;

use App\Models\Order;
use App\Models\User;
use App\Models\Product;
use App\Models\Category;
use App\Models\OrderItem;
use App\Models\Rating;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Carbon\Carbon;

class AdminController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        // Xử lý bộ lọc thời gian
        $startDate = $request->input('start_date')
            ? Carbon::parse($request->input('start_date'))->startOfDay()
            : Carbon::now()->startOfMonth();
        $endDate = $request->input('end_date')
            ? Carbon::parse($request->input('end_date'))->endOfDay()
            : Carbon::now()->endOfDay();

        // Thống kê tổng quan
        $todayRevenue = Order::whereDate('ordered_at', Carbon::today())
            ->where('status', 'finished')
            ->where('payment_status', 'paid')
            ->sum('total_amount');

        $monthRevenue = Order::whereMonth('ordered_at', Carbon::now()->month)
            ->whereYear('ordered_at', Carbon::now()->year)
            ->where('status', 'finished')
            ->where('payment_status', 'paid')
            ->sum('total_amount');

        $totalCustomers = User::where('role_id', 2)->count();

        $newOrders = Order::whereBetween('ordered_at', [$startDate, $endDate])->count();
        $finishedOrders = Order::whereBetween('ordered_at', [$startDate, $endDate])
            ->where('status', 'finished')
            ->where('payment_status', 'paid')
            ->count();

        $totalRevenue = Order::whereBetween('ordered_at', [$startDate, $endDate])
            ->where('status', 'finished')
            ->where('payment_status', 'paid')
            ->sum('total_amount');

        // Thống kê trạng thái đơn hàng
        $orderStatusStats = Order::whereBetween('ordered_at', [$startDate, $endDate])
            ->select('status', DB::raw('count(*) as total'))
            ->groupBy('status')
            ->get()
            ->mapWithKeys(function ($item) {
                $statusText = [
                    'pending' => 'Chờ xác nhận',
                    'processing' => 'Đang xử lý',
                    'delivering' => 'Đang giao',
                    'completed' => 'Đã giao',
                    'finished' => 'Hoàn thành',
                    'cancelled' => 'Đã hủy',
                    'failed' => 'Thất bại',
                ][$item->status] ?? 'Không xác định';
                return [$statusText => $item->total];
            });

        // Thống kê doanh thu theo ngày/tháng
        $revenueByDate = Order::whereBetween('ordered_at', [$startDate, $endDate])
            ->where('status', 'finished')
            ->where('payment_status', 'paid')
            ->select(
                DB::raw('DATE(ordered_at) as date'),
                DB::raw('SUM(total_amount) as total')
            )
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        // Top sản phẩm bán chạy
        $topSellingProducts = Product::withSum([
            'orderItems as finished_quantity' => function ($query) use ($startDate, $endDate) {
                $query->whereHas('order', function ($q) use ($startDate, $endDate) {
                    $q->where('status', 'finished')
                      ->whereBetween('ordered_at', [$startDate, $endDate]);
                });
            }
        ], 'quantity')
        ->orderByDesc('finished_quantity')
        ->take(5)
        ->get();

        // Sản phẩm sắp hết hàng
        $lowStockProducts = Product::with(['variants' => function ($query) {
            $query->where('is_active', true);
        }])
            ->whereHas('variants', function ($query) {
                $query->where('is_active', true)
                    ->where('stock', '<', 10);
            })
            ->with(['variants' => function ($query) {
                $query->where('is_active', true)
                    ->where('stock', '<', 10)
                    ->select('product_id', 'stock');
            }])
            ->paginate(5);

        // Top khách hàng
        $topCustomers = User::where('role_id', 2)
            ->withCount(['orders as finished_orders_count' => function ($query) use ($startDate, $endDate) {
                $query->whereBetween('ordered_at', [$startDate, $endDate])
                    ->where('status', 'finished');
            }])
            ->withSum(['orders as finished_orders_sum_total_amount' => function ($query) use ($startDate, $endDate) {
                $query->whereBetween('ordered_at', [$startDate, $endDate])
                    ->where('status', 'finished');
            }], 'total_amount')
            ->orderByDesc('finished_orders_sum_total_amount')
            ->take(5)
            ->get();


        // Sách đánh giá tốt
        $topRatedBooks = Product::withCount('ratings')
            ->withAvg('ratings', 'rating')
            ->having('ratings_count', '>=', 5) // Ít nhất 5 đánh giá
            ->orderByDesc('ratings_avg_rating')
            ->paginate(5);

        // Đơn hàng mới nhất (chỉ lấy đơn pending)
        $latestOrders = Order::with(['user', 'orderItems.product'])
            ->where('status', 'pending')
            // ->whereBetween('created_at', [$startDate, $endDate])
            ->latest()
            ->paginate(5);

        return view('admin.Dashboard', compact(
            'startDate',
            'endDate',
            'todayRevenue',
            'monthRevenue',
            'newOrders',
            'finishedOrders',
            'totalCustomers',
            'orderStatusStats',
            'revenueByDate',
            'topSellingProducts',
            'lowStockProducts',
            'topCustomers',
            'topRatedBooks',
            'latestOrders',
            'totalRevenue'
        ));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}