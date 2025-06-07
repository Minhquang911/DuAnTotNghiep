@extends('layouts.admin.AdminLayout')

@section('content')
    <div class="container-fluid">
        <!-- Page Heading -->
        <div class="d-sm-flex align-items-center justify-content-between mb-4">
            <h1 class="h3 mb-0 text-gray-800">Quản lý đơn hàng</h1>
        </div>

        <!-- Thống kê trạng thái đơn hàng -->
        <div class="row mb-4">
            <!-- Tổng đơn hàng -->
            <div class="col-xl-3 col-md-4 mb-4">
                <div class="card border-left-primary shadow h-100 py-2">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">Tổng đơn hàng</div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $orderStats['total'] }}</div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-shopping-cart fa-2x text-gray-300"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Chờ xác nhận -->
            <div class="col-xl-3 col-md-4 mb-4">
                <div class="card border-left-warning shadow h-100 py-2">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">Chờ xác nhận</div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $orderStats['pending'] }}</div>
                                <div class="text-xs mt-2">
                                    <span class="text-success">{{ $orderStats['pending_paid'] }} đơn đã thanh
                                        toán</span><br>
                                    <span class="text-warning">{{ $orderStats['pending_unpaid'] }} đơn chưa thanh
                                        toán</span>
                                </div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-clock fa-2x text-gray-300"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Đang xử lý -->
            <div class="col-xl-3 col-md-4 mb-4">
                <div class="card border-left-info shadow h-100 py-2">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-info text-uppercase mb-1">Đang xử lý</div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $orderStats['processing'] }}</div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-cog fa-2x text-gray-300"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Đang giao -->
            <div class="col-xl-3 col-md-4 mb-4">
                <div class="card border-left-primary shadow h-100 py-2">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">Đang giao</div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $orderStats['delivering'] }}</div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-truck fa-2x text-gray-300"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Đã giao -->
            <div class="col-xl-3 col-md-4 mb-4">
                <div class="card border-left-success shadow h-100 py-2">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-success text-uppercase mb-1">Đã giao</div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $orderStats['completed'] }}</div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-check-circle fa-2x text-gray-300"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Hoàn thành -->
            <div class="col-xl-3 col-md-4 mb-4">
                <div class="card border-left-success shadow h-100 py-2">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-success text-uppercase mb-1">Hoàn thành</div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $orderStats['finished'] }}</div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-flag-checkered fa-2x text-gray-300"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Đã hủy -->
            <div class="col-xl-3 col-md-4 mb-4">
                <div class="card border-left-danger shadow h-100 py-2">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-danger text-uppercase mb-1">Đã hủy</div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $orderStats['cancelled'] }}</div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-ban fa-2x text-gray-300"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Thất bại -->
            <div class="col-xl-3 col-md-4 mb-4">
                <div class="card border-left-danger shadow h-100 py-2">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-danger text-uppercase mb-1">Thất bại</div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $orderStats['failed'] }}</div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-times-circle fa-2x text-gray-300"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Bộ lọc tìm kiếm -->
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">Tìm kiếm đơn hàng</h6>
            </div>
            <div class="card-body">
                <form action="{{ route('admin.orders.index') }}" method="GET" class="row g-3">
                    <div class="col-md-2">
                        <label for="search" class="form-label">Tìm kiếm</label>
                        <input type="text" class="form-control" id="search" name="search"
                            value="{{ request('search') }}" placeholder="Mã đơn, tên, email, SĐT...">
                    </div>
                    <div class="col-md-2">
                        <label for="status" class="form-label">Trạng thái đơn hàng</label>
                        <select class="form-select" id="status" name="status">
                            <option value="">Tất cả</option>
                            <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Chờ xác nhận
                            </option>
                            <option value="processing" {{ request('status') == 'processing' ? 'selected' : '' }}>Đang xử
                                lý</option>
                            <option value="delivering" {{ request('status') == 'delivering' ? 'selected' : '' }}>Đang giao
                            </option>
                            <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>Đã giao
                            </option>
                            <option value="finished" {{ request('status') == 'finished' ? 'selected' : '' }}>Hoàn thành
                            </option>
                            <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}>Đã hủy
                            </option>
                            <option value="failed" {{ request('status') == 'failed' ? 'selected' : '' }}>Thất bại</option>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <label for="payment_status" class="form-label">Trạng thái thanh toán</label>
                        <select class="form-select" id="payment_status" name="payment_status">
                            <option value="">Tất cả</option>
                            <option value="unpaid" {{ request('payment_status') == 'unpaid' ? 'selected' : '' }}>Chưa
                                thanh toán</option>
                            <option value="paid" {{ request('payment_status') == 'paid' ? 'selected' : '' }}>Đã thanh
                                toán</option>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <label for="payment_method" class="form-label">Phương thức thanh toán</label>
                        <select class="form-select" id="payment_method" name="payment_method">
                            <option value="">Tất cả</option>
                            <option value="cod" {{ request('payment_method') == 'cod' ? 'selected' : '' }}>Thanh toán
                                khi nhận hàng</option>
                            <option value="bank_transfer"
                                {{ request('payment_method') == 'bank_transfer' ? 'selected' : '' }}>Chuyển khoản ngân hàng
                            </option>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <label for="date_from" class="form-label">Từ ngày</label>
                        <input type="date" class="form-control" id="date_from" name="date_from"
                            value="{{ request('date_from') }}">
                    </div>
                    <div class="col-md-2">
                        <label for="date_to" class="form-label">Đến ngày</label>
                        <input type="date" class="form-control" id="date_to" name="date_to"
                            value="{{ request('date_to') }}">
                    </div>
                    <div class="col-md-3 d-flex align-items-end">
                        <button type="submit" class="btn btn-primary me-2">
                            <i class="fas fa-search me-1"></i> Tìm kiếm
                        </button>
                        <a href="{{ route('admin.orders.index') }}" class="btn btn-secondary">
                            <i class="fas fa-redo me-1"></i> Làm mới
                        </a>
                    </div>
                </form>
            </div>
        </div>

        <!-- Danh sách đơn hàng -->
        <div class="card shadow mb-4">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover table-striped align-middle">
                        <thead>
                            <tr>
                                <th>Mã đơn</th>
                                <th>Khách hàng</th>
                                <th>Tổng tiền</th>
                                <th>Ngày đặt</th>
                                <th>Trạng thái</th>
                                <th>Thanh toán</th>
                                <th>Thao tác</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($orders as $order)
                                <tr>
                                    <td>#{{ $order->order_code }}</td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            @if ($order->user->avatar)
                                                <div class="avatar-wrapper me-2">
                                                    <img src="{{ asset('storage/' . $order->user->avatar) }}" alt="Avatar"
                                                        class="img-thumbnail avatar-image">
                                                </div>
                                            @else
                                                <div class="avatar-circle bg-primary text-white me-2">
                                                    {{ strtoupper(substr($order->user->name, 0, 1)) }}
                                                </div>
                                            @endif
                                            <div>
                                                <div class="fw-bold">{{ $order->user->name }}</div>
                                                <div class="text-muted small">{{ $order->user->email }}</div>
                                                <div class="text-muted small">{{ $order->user->phone }}</div>
                                            </div>
                                        </div>
                                    </td>
                                    <td>{{ number_format($order->total_amount) }} VNĐ</td>
                                    <td>{{ $order->created_at->format('d/m/Y H:i') }}</td>
                                    <td>
                                        @php
                                            $statusClass =
                                                [
                                                    'pending' => 'warning',
                                                    'processing' => 'info',
                                                    'delivering' => 'primary',
                                                    'completed' => 'success',
                                                    'finished' => 'success',
                                                    'cancelled' => 'danger',
                                                    'failed' => 'danger',
                                                ][$order->status] ?? 'secondary';

                                            $statusText =
                                                [
                                                    'pending' => 'Chờ xác nhận',
                                                    'processing' => 'Đang xử lý',
                                                    'delivering' => 'Đang giao',
                                                    'completed' => 'Đã giao',
                                                    'finished' => 'Hoàn thành',
                                                    'cancelled' => 'Đã hủy',
                                                    'failed' => 'Thất bại',
                                                ][$order->status] ?? 'Không xác định';
                                        @endphp
                                        <span class="badge bg-{{ $statusClass }}">{{ $statusText }}</span>
                                    </td>
                                    <td>
                                        <div class="d-flex flex-column">
                                            @php
                                                $paymentStatusClass =
                                                    [
                                                        'unpaid' => 'danger',
                                                        'paid' => 'success',
                                                    ][$order->payment_status] ?? 'secondary';

                                                $paymentStatusText =
                                                    [
                                                        'unpaid' => 'Chưa thanh toán',
                                                        'paid' => 'Đã thanh toán',
                                                    ][$order->payment_status] ?? 'Không xác định';

                                                $paymentMethodText =
                                                    [
                                                        'cod' => 'Thanh toán khi nhận hàng',
                                                        'bank_transfer' => 'Chuyển khoản ngân hàng',
                                                    ][$order->payment_method] ?? 'Không xác định';
                                            @endphp
                                            <span class="text text-{{ $paymentStatusClass }} mb-1">
                                                {{ $paymentStatusText }}
                                            </span>
                                            <small class="text-muted">
                                                <i class="fas fa-credit-card me-1"></i>
                                                {{ $paymentMethodText }}
                                            </small>
                                            @if ($order->payment_status === 'paid')
                                                <small class="text-muted">
                                                    <i class="fas fa-clock me-1"></i>
                                                    {{ $order->paid_at ? $order->paid_at->format('d/m/Y') : '' }}
                                                </small>
                                            @endif
                                        </div>
                                    </td>
                                    <td>
                                        <div class="btn-group">
                                            <a href="{{ route('admin.orders.show', $order->id) }}"
                                                class="btn btn-sm btn-info" data-bs-toggle="tooltip"
                                                title="Xem chi tiết">
                                                <i class="fas fa-eye"></i>
                                            </a>

                                            @if ($order->status === 'pending')
                                                <button type="button" class="btn btn-sm btn-success"
                                                    data-bs-toggle="tooltip" title="Xác nhận đơn hàng"
                                                    onclick="confirmOrder({{ $order->id }})">
                                                    <i class="fas fa-check"></i>
                                                </button>
                                                <button type="button" class="btn btn-sm btn-danger"
                                                    data-bs-toggle="tooltip" title="Hủy đơn hàng"
                                                    onclick="cancelOrder({{ $order->id }})">
                                                    <i class="fas fa-times"></i>
                                                </button>
                                            @elseif(in_array($order->status, ['processing', 'delivering', 'completed']))
                                                <div class="dropdown">
                                                    <button class="btn btn-sm btn-primary dropdown-toggle" type="button"
                                                        data-bs-toggle="dropdown">
                                                        <i class="fas fa-arrow-right"></i>
                                                    </button>
                                                    <ul class="dropdown-menu">
                                                        @if ($order->status === 'processing')
                                                            <li>
                                                                <a class="dropdown-item" href="#"
                                                                    onclick="updateStatus({{ $order->id }}, 'delivering')">
                                                                    Chuyển sang Đang giao
                                                                </a>
                                                            </li>
                                                        @endif
                                                        @if ($order->status === 'delivering')
                                                            <li>
                                                                <a class="dropdown-item" href="#"
                                                                    onclick="updateStatus({{ $order->id }}, 'completed')">
                                                                    Chuyển sang Đã giao thành công
                                                                </a>
                                                            </li>
                                                            <li>
                                                                <a class="dropdown-item text-danger" href="#"
                                                                    onclick="updateStatus({{ $order->id }}, 'failed')">
                                                                    Chuyển sang Giao hàng thất bại
                                                                </a>
                                                            </li>
                                                        @endif
                                                        @if ($order->status === 'completed')
                                                            <li>
                                                                <a class="dropdown-item" href="#"
                                                                    onclick="updateStatus({{ $order->id }}, 'finished')">
                                                                    Hoàn thành đơn hàng
                                                                </a>
                                                            </li>
                                                        @endif
                                                    </ul>
                                                </div>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="text-center py-4">
                                        <div class="text-muted">
                                            <i class="fas fa-inbox fa-2x mb-3"></i>
                                            <p class="mb-0">Không có đơn hàng nào</p>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- Phân trang -->
                <div class="d-flex justify-content-end mt-4">
                    {{ $orders->links('pagination::bootstrap-5') }}
                </div>
            </div>
        </div>
    </div>
@endsection

@push('styles')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">
    <style>
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

        .border-left-danger {
            border-left: 0.25rem solid #e74a3b !important;
        }
    </style>
@endpush

@push('scripts')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
    <script>
        // Cấu hình toastr
        toastr.options = {
            "closeButton": true,
            "progressBar": true,
            "positionClass": "toast-top-right",
            "timeOut": "3000"
        };

        function confirmOrder(orderId) {
            if (confirm('Bạn có chắc chắn muốn xác nhận đơn hàng này?')) {
                fetch(`/admin/orders/${orderId}/confirm`, {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                            'Accept': 'application/json',
                            'Content-Type': 'application/json'
                        }
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            toastr.success(data.message);
                            setTimeout(() => window.location.reload(), 1000);
                        } else {
                            toastr.error(data.message);
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        toastr.error('Có lỗi xảy ra khi xác nhận đơn hàng');
                    });
            }
        }

        function cancelOrder(orderId) {
            if (confirm('Bạn có chắc chắn muốn hủy đơn hàng này?')) {
                fetch(`/admin/orders/${orderId}/cancel`, {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                            'Accept': 'application/json',
                            'Content-Type': 'application/json'
                        }
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            toastr.success(data.message);
                            setTimeout(() => window.location.reload(), 1000);
                        } else {
                            toastr.error(data.message);
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        toastr.error('Có lỗi xảy ra khi hủy đơn hàng');
                    });
            }
        }

        function updateStatus(orderId, status) {
            if (confirm('Bạn có chắc chắn muốn cập nhật trạng thái đơn hàng?')) {
                fetch(`/admin/orders/${orderId}/update-status`, {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                            'Accept': 'application/json',
                            'Content-Type': 'application/json'
                        },
                        body: JSON.stringify({
                            status: status
                        })
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            toastr.success(data.message);
                            setTimeout(() => window.location.reload(), 1000);
                        } else {
                            toastr.error(data.message);
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        toastr.error('Có lỗi xảy ra khi cập nhật trạng thái');
                    });
            }
        }

        // Khởi tạo tooltips
        document.addEventListener('DOMContentLoaded', function() {
            var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
            var tooltipList = tooltipTriggerList.map(function(tooltipTriggerEl) {
                return new bootstrap.Tooltip(tooltipTriggerEl);
            });
        });
    </script>
@endpush