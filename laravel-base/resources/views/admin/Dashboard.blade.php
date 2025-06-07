@extends('layouts.admin.AdminLayout')

@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Tổng quan về tình hình kinh doanh cửa hàng sách</h3>
                </div>
                <div class="card-body">
                    <!-- Bộ lọc thời gian -->
                    <form action="{{ route('admin.dashboard') }}" method="GET" class="mb-4">
                        <div class="row g-3 align-items-center">
                            <div class="col-md-3">
                                <label for="start_date" class="form-label">Từ ngày</label>
                                <input type="date" class="form-control" id="start_date" name="start_date"
                                    value="{{ $startDate->format('Y-m-d') }}">
                            </div>
                            <div class="col-md-3">
                                <label for="end_date" class="form-label">Đến ngày</label>
                                <input type="date" class="form-control" id="end_date" name="end_date"
                                    value="{{ $endDate->format('Y-m-d') }}">
                            </div>
                            <div class="col-md-2 d-flex align-items-end">
                                <button type="submit" class="btn btn-primary w-100">
                                    <i class="fas fa-filter me-1"></i> Lọc
                                </button>
                            </div>
                        </div>
                    </form>

                    <!-- Thống kê tổng quan -->
                    <div class="row">
                        <!-- Doanh thu hôm nay -->
                        <div class="col-md-4 mb-4">
                            <div class="card border-left-primary shadow h-100 py-2">
                                <div class="card-body">
                                    <div class="row no-gutters align-items-center">
                                        <div class="col mr-2">
                                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                                Doanh thu hôm nay</div>
                                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                                {{ number_format($todayRevenue) }} VNĐ
                                            </div>
                                        </div>
                                        <div class="col-auto">
                                            <i class="fas fa-calendar-day fa-2x text-gray-300"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Doanh thu tháng -->
                        <div class="col-md-4 mb-4">
                            <div class="card border-left-success shadow h-100 py-2">
                                <div class="card-body">
                                    <div class="row no-gutters align-items-center">
                                        <div class="col mr-2">
                                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                                Doanh thu tháng {{ now()->month }}</div>
                                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                                {{ number_format($monthRevenue) }} VNĐ
                                            </div>
                                        </div>
                                        <div class="col-auto">
                                            <i class="fas fa-calendar-alt fa-2x text-gray-300"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Tổng khách hàng -->
                        <div class="col-md-4 mb-4">
                            <div class="card border-left-warning shadow h-100 py-2">
                                <div class="card-body">
                                    <div class="row no-gutters align-items-center">
                                        <div class="col mr-2">
                                            <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                                                Tổng số khách hàng</div>
                                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $totalCustomers }}</div>
                                        </div>
                                        <div class="col-auto">
                                            <i class="fas fa-users fa-2x text-gray-300"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <!-- Cột trái: Đơn hàng mới và Tổng doanh thu -->
                        <div class="col-xl-4 d-flex flex-column">
                            <!-- Đơn hàng mới (card trên) -->
                            <div class="mb-4 flex-fill" style="max-height: 145px">
                                <div class="card border-left-info shadow h-100 py-2">
                                    <div class="card-body">
                                        <div class="row no-gutters align-items-center">
                                            <div class="col mr-2">
                                                <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                                                    Tổng số đơn hàng
                                                </div>
                                                <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $newOrders }}
                                                </div>
                                                <div class="text-xs mt-2">
                                                    <i class="fas fa-check-circle text-success"></i> {{ $finishedOrders }}
                                                    đơn hoàn thành
                                                </div>
                                            </div>
                                            <div class="col-auto">
                                                <i class="fas fa-shopping-cart fa-2x text-gray-300"></i>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- Tổng doanh thu (card dưới) -->
                            <div class="mb-4 flex-fill" style="max-height: 140px">
                                <div class="card border-left-success shadow h-100 py-2">
                                    <div class="card-body">
                                        <div class="row no-gutters align-items-center">
                                            <div class="col mr-2">
                                                <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                                    Doanh thu
                                                </div>
                                                <div class="h5 mb-0 font-weight-bold text-gray-800">
                                                    {{ number_format($totalRevenue) }} VNĐ</div>
                                            </div>
                                            <div class="col-auto">
                                                <i class="fas fa-money-bill-wave fa-2x text-gray-300"></i>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-xl-8 mb-4">
                            <div class="card shadow mb-0 ">
                                <div class="card-header py-3 d-flex justify-content-between align-items-center">
                                    <h6 class="m-0 font-weight-bold text-primary">Danh sách đơn hàng chờ xác nhận</h6>
                                    <a href="" class="btn btn-sm btn-primary">
                                        Xem tất cả
                                    </a>
                                </div>
                                <div class="card-body">
                                    <div class="table-responsive">
                                        <table class="table table-hover">
                                            <thead>
                                                <tr>
                                                    <th>Mã đơn</th>
                                                    <th>Khách hàng</th>
                                                    <th>Tổng tiền</th>
                                                    <th>Ngày đặt</th>
                                                    <th>Trạng thái</th>
                                                    <th>Thao tác</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @forelse($latestOrders as $order)
                                                    <tr>
                                                        <td>#{{ $order->order_code }}</td>
                                                        <td>
                                                            <div class="d-flex align-items-center">
                                                                @if ($order->user->avatar)
                                                                    <div class="avatar-wrapper">
                                                                        <img src="{{ asset('storage/' . $order->user->avatar) }}"
                                                                            alt="Avatar"
                                                                            class="img-thumbnail avatar-image">
                                                                    </div>
                                                                @else
                                                                    <div class="avatar-circle bg-primary text-white me-2">
                                                                        {{ strtoupper(substr($order->user->name, 0, 1)) }}
                                                                    </div>
                                                                @endif
                                                                <div>
                                                                    <div class="fw-bold">{{ $order->user->name }}</div>
                                                                    <div class="text-muted small">{{ $order->user->email }}
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </td>
                                                        <td>{{ number_format($order->total_amount) }} VNĐ</td>
                                                        <td>{{ $order->created_at->format('d/m/Y H:i') }}</td>
                                                        <td>
                                                            <span class="badge bg-warning">
                                                                Chờ xác nhận
                                                            </span>
                                                        </td>
                                                        <td>
                                                            <a href="" class="btn btn-sm btn-info"
                                                                data-bs-toggle="tooltip" title="Xem chi tiết">
                                                                <i class="fas fa-eye"></i>
                                                            </a>
                                                        </td>
                                                    </tr>
                                                @empty
                                                    <tr>
                                                        <td colspan="6" class="text-center">Không có đơn hàng nào</td>
                                                    </tr>
                                                @endforelse
                                            </tbody>
                                        </table>
                                    </div>

                                    <!-- Phân trang -->
                                    <div class="d-flex justify-content-end mt-4">
                                        {{ $latestOrders->links('pagination::bootstrap-5') }}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>


                    <!-- Biểu đồ và thống kê -->
                    <div class="row">
                        <div class="col-xl-4">
                            <div class="card shadow mb-4">
                                <div class="card-header py-3">
                                    <h6 class="m-0 font-weight-bold text-primary">Trạng thái đơn hàng</h6>
                                </div>
                                <div class="card-body">
                                    <canvas id="orderStatusChart" width="300" height="400"></canvas>
                                </div>
                            </div>
                        </div>

                        <div class="col-xl-8">
                            <div class="card shadow mb-4 ">
                                <div class="card-header py-3">
                                    <h6 class="m-0 font-weight-bold text-primary">Doanh thu theo thời gian</h6>
                                </div>
                                <div class="card-body">
                                    <canvas id="revenueChart" height="400"></canvas>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Top sản phẩm và khách hàng -->
                    <div class="row">
                        <!-- Top sản phẩm bán chạy -->
                        <div class="col-xl-6 col-lg-6">
                            <div class="card shadow mb-4">
                                <div class="card-header py-3">
                                    <h6 class="m-0 font-weight-bold text-primary">Top sản phẩm bán chạy</h6>
                                </div>
                                <div class="card-body">
                                    <div class="table-responsive">
                                        <table class="table">
                                            <thead>
                                                <tr>
                                                    <th>Sản phẩm</th>
                                                    <th>Số lượng bán</th>
                                                    <th>Doanh thu</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @forelse($topSellingProducts as $product)
                                                    <tr>
                                                        <td>
                                                            <div class="d-flex align-items-center">
                                                                @if ($product->cover_image_url)
                                                                    <img src="{{ $product->cover_image_url }}"
                                                                        alt="{{ $product->title }}"
                                                                        class="img-thumbnail me-2"
                                                                        style="width: 40px; height: 60px; object-fit: cover;">
                                                                @endif
                                                                <div>
                                                                    <div class="fw-bold">{{ $product->title }}</div>
                                                                    <div class="text-muted small">{{ $product->author }}
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </td>
                                                        <td>{{ $product->finished_quantity ?? 0 }}</td>
                                                        <td>{{ number_format($product->finished_quantity * $product->min_price) }}
                                                            VNĐ</td>
                                                    </tr>
                                                @empty
                                                    <tr>
                                                        <td colspan="3" class="text-center">Không có dữ liệu</td>
                                                    </tr>
                                                @endforelse
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Top khách hàng -->
                        <div class="col-xl-6 col-lg-6">
                            <div class="card shadow mb-4">
                                <div class="card-header py-3">
                                    <h6 class="m-0 font-weight-bold text-primary">Top khách hàng tiềm năng</h6>
                                </div>
                                <div class="card-body">
                                    <div class="table-responsive">
                                        <table class="table">
                                            <thead>
                                                <tr>
                                                    <th>Khách hàng</th>
                                                    <th>Số đơn hàng hoàn thành</th>
                                                    <th>Tổng chi tiêu</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @forelse($topCustomers as $customer)
                                                    <tr>
                                                        <td>
                                                            <div class="d-flex align-items-center">
                                                                @if ($customer->avatar)
                                                                    <div class="avatar-wrapper">
                                                                        <img src="{{ asset('storage/' . $customer->avatar) }}"
                                                                            alt="Avatar"
                                                                            class="img-thumbnail avatar-image">
                                                                    </div>
                                                                @else
                                                                    <div class="avatar-circle bg-primary text-white me-2">
                                                                        {{ strtoupper(substr($customer->name, 0, 1)) }}
                                                                    </div>
                                                                @endif
                                                                <div>
                                                                    <div class="fw-bold">{{ $customer->name }}</div>
                                                                    <div class="text-muted small">{{ $customer->email }}
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </td>
                                                        <td class="text-center">{{ $customer->finished_orders_count }}
                                                        </td>
                                                        <td>{{ number_format($customer->finished_orders_sum_total_amount) }}
                                                            VNĐ
                                                        </td>
                                                    </tr>
                                                @empty
                                                    <tr>
                                                        <td colspan="3" class="text-center">Không có dữ liệu</td>
                                                    </tr>
                                                @endforelse
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Sách đánh giá tốt và sắp hết hàng -->
                    <div class="row">
                        <!-- Sách đánh giá tốt -->
                        <div class="col-xl-6 col-lg-6">
                            <div class="card shadow mb-4">
                                <div class="card-header py-3">
                                    <h6 class="m-0 font-weight-bold text-primary">Sách đánh giá tốt</h6>
                                </div>
                                <div class="card-body">
                                    <div class="table-responsive">
                                        <table class="table">
                                            <thead>
                                                <tr>
                                                    <th>Sách</th>
                                                    <th>Đánh giá</th>
                                                    <th>Số lượt đánh giá</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @forelse($topRatedBooks as $book)
                                                    <tr>
                                                        <td>
                                                            <div class="d-flex align-items-center">
                                                                @if ($book->cover_image_url)
                                                                    <img src="{{ $book->cover_image_url }}"
                                                                        alt="{{ $book->title }}"
                                                                        class="img-thumbnail me-2"
                                                                        style="width: 40px; height: 60px; object-fit: cover;">
                                                                @endif
                                                                <div>
                                                                    <div class="fw-bold">{{ $book->title }}</div>
                                                                    <div class="text-muted small">{{ $book->author }}
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </td>
                                                        <td>
                                                            <div class="d-flex align-items-center">
                                                                <div class="text-warning me-1">
                                                                    @for ($i = 1; $i <= 5; $i++)
                                                                        <i
                                                                            class="fas fa-star{{ $i <= round($book->ratings_avg_rating) ? '' : '-o' }}"></i>
                                                                    @endfor
                                                                </div>
                                                                <span
                                                                    class="ms-1">{{ number_format($book->ratings_avg_rating, 1) }}</span>
                                                            </div>
                                                        </td>
                                                        <td class="text-center">{{ $book->ratings_count }}</td>
                                                    </tr>
                                                @empty
                                                    <tr>
                                                        <td colspan="3" class="text-center">Không có dữ liệu</td>
                                                    </tr>
                                                @endforelse
                                            </tbody>
                                        </table>
                                    </div>

                                    <!-- Phân trang -->
                                    <div class="d-flex justify-content-end mt-4">
                                        {{ $topRatedBooks->links('pagination::bootstrap-5') }}
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Sản phẩm sắp hết hàng -->
                        <div class="col-xl-6 col-lg-6">
                            <div class="card shadow mb-4">
                                <div class="card-header py-3">
                                    <h6 class="m-0 font-weight-bold text-primary">Sản phẩm sắp hết hàng</h6>
                                </div>
                                <div class="card-body">
                                    <div class="table-responsive">
                                        <table class="table">
                                            <thead>
                                                <tr>
                                                    <th>Sản phẩm</th>
                                                    <th>Số lượng còn lại</th>
                                                    <th>Trạng thái</th>
                                                    <th>Thao tác</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @forelse($lowStockProducts as $product)
                                                    @php
                                                        $minStock = $product->variants->min('stock');
                                                        $statusClass = $minStock <= 5 ? 'danger' : 'warning';
                                                        $statusText = $minStock <= 5 ? 'Cần nhập thêm' : 'Sắp hết';
                                                    @endphp
                                                    <tr>
                                                        <td>
                                                            <div class="d-flex align-items-center">
                                                                @if ($product->cover_image_url)
                                                                    <img src="{{ $product->cover_image_url }}"
                                                                        alt="{{ $product->title }}"
                                                                        class="img-thumbnail me-2"
                                                                        style="width: 40px; height: 60px; object-fit: cover;">
                                                                @endif
                                                                <div>
                                                                    <div class="fw-bold">{{ $product->title }}</div>
                                                                    <div class="text-muted small">{{ $product->author }}
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </td>
                                                        <td class="text-center">{{ $minStock }}</td>
                                                        <td>
                                                            <span
                                                                class="badge bg-{{ $statusClass }}">{{ $statusText }}</span>
                                                        </td>
                                                        <td>
                                                            <a href="{{ route('admin.products.edit', $product->id) }}"
                                                                class="btn btn-sm btn-info">
                                                                <i class="fas fa-edit"></i> Cập nhật
                                                            </a>
                                                        </td>
                                                    </tr>
                                                @empty
                                                    <tr>
                                                        <td colspan="4" class="text-center">Không có sản phẩm nào sắp
                                                            hết hàng</td>
                                                    </tr>
                                                @endforelse
                                            </tbody>
                                        </table>
                                    </div>

                                    <!-- Phân trang -->
                                    <div class="d-flex justify-content-end mt-4">
                                        {{ $lowStockProducts->links('pagination::bootstrap-5') }}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('styles')
    <style>
        .border-left-primary {
            border-left: 0.25rem solid #4e73df !important;
        }

        .border-left-success {
            border-left: 0.25rem solid #1cc88a !important;
        }

        .border-left-info {
            border-left: 0.25rem solid #36b9cc !important;
        }

        .border-left-warning {
            border-left: 0.25rem solid #f6c23e !important;
        }

        .progress {
            height: 0.5rem;
        }

        .card {
            margin-bottom: 1.5rem;
        }

        .card-header {
            background-color: #f8f9fc;
            border-bottom: 1px solid #e3e6f0;
        }

        .table> :not(caption)>*>* {
            padding: 0.75rem;
        }

        .badge {
            padding: 0.5em 0.75em;
        }

        .avatar-circle {
            width: 32px;
            height: 32px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: bold;
        }

        .avatar-wrapper {
            width: 32px;
            height: 32px;
            overflow: hidden;
            border-radius: 50%;
        }

        .avatar-image {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: transform 0.3s ease;
        }

        .avatar-image:hover {
            transform: scale(1.1);
        }

        .img-thumbnail {
            border-radius: 0.25rem;
            border: 1px solid #dee2e6;
            background: #fff;
        }
    </style>
@endpush

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // // Biểu đồ doanh thu
            const revenueCtx = document.getElementById('revenueChart');
            if (revenueCtx) {
                const revenueData = {!! json_encode($revenueByDate) !!};
                const labels = revenueData.map(item => {
                    const date = new Date(item.date);
                    return date.toLocaleDateString('vi-VN', {
                        day: '2-digit',
                        month: '2-digit'
                    });
                });
                const data = revenueData.map(item => item.total);

                new Chart(revenueCtx, {
                    type: 'horizontalBar',
                    data: {
                        labels: labels,
                        datasets: [{
                            label: 'Doanh thu',
                            data: data,
                            backgroundColor: 'rgba(78, 115, 223, 0.5)',
                            borderColor: 'rgba(78, 115, 223, 1)',
                            borderWidth: 1
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        scales: {
                            y: {
                                beginAtZero: true,
                                ticks: {
                                    callback: function(value) {
                                        return value.toLocaleString('vi-VN') + ' VNĐ';
                                    }
                                }
                            }
                        },
                        plugins: {
                            tooltip: {
                                callbacks: {
                                    label: function(context) {
                                        return context.dataset.label + ': ' +
                                            context.raw.toLocaleString('vi-VN') + ' VNĐ';
                                    }
                                }
                            }
                        }
                    }
                });
            }

            // Biểu đồ trạng thái đơn hàng
            const statusCtx = document.getElementById('orderStatusChart');
            if (statusCtx) {
                const statusData = {!! json_encode($orderStatusStats) !!};
                console.log('statusData:', statusData);
                const labels = Object.keys(statusData);
                const data = Object.values(statusData);

                new Chart(statusCtx, {
                    type: 'doughnut',
                    data: {
                        labels: labels,
                        datasets: [{
                            data: data,
                            backgroundColor: [
                                'rgba(255, 193, 7, 0.8)', // Chờ xác nhận
                                'rgba(54, 185, 204, 0.8)', // Đang xử lý
                                'rgba(78, 115, 223, 0.8)', // Đang giao
                                'rgba(28, 200, 138, 0.8)', // Đã giao
                                'rgba(40, 167, 69, 0.8)', // Hoàn thành
                                'rgba(231, 74, 59, 0.8)', // Đã hủy
                                'rgba(136, 136, 136, 0.8)' // Thất bại
                            ],
                            borderColor: [
                                'rgba(255, 193, 7, 1)',
                                'rgba(54, 185, 204, 1)',
                                'rgba(78, 115, 223, 1)',
                                'rgba(28, 200, 138, 1)',
                                'rgba(40, 167, 69, 1)',
                                'rgba(231, 74, 59, 1)',
                                'rgba(136, 136, 136, 1)'
                            ],
                            borderWidth: 1
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            legend: {
                                position: 'bottom',
                                labels: {
                                    boxWidth: 12,
                                    padding: 15,
                                    font: {
                                        size: 11
                                    }
                                }
                            },
                            tooltip: {
                                enabled: true,
                                callbacks: {
                                    label: function(context) {
                                        // Debug thử callback có chạy không
                                        console.log('Tooltip context:', context);
                                        const label = context.label || '';
                                        const value = context.raw || 0;
                                        const total = context.dataset.data.reduce((a, b) => a + b, 0);
                                        const percentage = total === 0 ? 0 : Math.round((value /
                                            total) * 100);
                                        return `${label}: ${value} đơn (${percentage}%)`;
                                    }
                                }
                            }
                        }
                    }
                });
            }
        });
    </script>
@endpush