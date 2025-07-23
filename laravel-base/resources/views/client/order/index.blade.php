@extends('layouts.client.ClientLayout')

@section('title')
    {{ isset($title) ? $title . ' - Book360 Store WooCommerce' : 'Book360 Store WooCommerce' }}
@endsection

@section('banner')
    <div class="breadcrumb-wrapper">
        <div class="book1">
            <img src="{{ asset('client/img/hero/book1.png') }}" alt="book">
        </div>
        <div class="book2">
            <img src="{{ asset('client/img/hero/book2.png') }}" alt="book">
        </div>
        <div class="container">
            <div class="page-heading">
                <h1>Thông tin đơn hàng</h1>
                <div class="page-header">
                    <ul class="breadcrumb-items wow fadeInUp" data-wow-delay=".3s">
                        <li>
                            <a href="{{ route('home') }}">
                                Trang chủ
                            </a>
                        </li>
                        <li>
                            <i class="fa-solid fa-chevron-right"></i>
                        </li>
                        <li>
                            Đơn hàng của tôi
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('content')
    <div class="container py-5">
        <div class="row">
            <div class="col-md-3">
                <div class="card">
                    <div class="card-body">
                        <div class="text-center mb-4">
                            <img src="{{ auth()->user()->avatar ? asset('storage/' . auth()->user()->avatar) : asset('client/img/default-avatar.jpg') }}"
                                alt="Avatar" class="rounded-circle"
                                style="width: 150px; height: 150px; object-fit: cover;">
                        </div>
                        <div class="list-group">
                            <a href="{{ route('user.profile') }}" class="list-group-item list-group-item-action">
                                <i class="fas fa-user me-2"></i> Thông tin cá nhân
                            </a>
                            <a href="{{ route('user.profile.password') }}" class="list-group-item list-group-item-action">
                                <i class="fas fa-lock me-2"></i> Đổi mật khẩu
                            </a>
                            <a href="{{ route('orders.index') }}" class="list-group-item list-group-item-action active">
                                <i class="fas fa-history me-2"></i> Lịch sử mua hàng
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-9">
                <!-- Danh sách đơn hàng -->
                <div class="card shadow mb-4">
                    <div class="card-body">
                        <h4 class="card-title mb-4">Lịch sử mua hàng</h4>
                        <div class="table-responsive">
                            <table class="table table-hover table-striped align-middle">
                                <thead>
                                    <tr>
                                        <th>Mã đơn</th>
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
                                            <td>
                                                <a href="{{ route('orders.show', $order->id) }}">
                                                    #{{ $order->order_code }}
                                                </a>
                                            </td>
                                            <td>{{ number_format($order->amount_due) }} VNĐ</td>
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
                                                            {{ $order->paid_at ? $order->paid_at->format('d/m/Y H:i') : '' }}
                                                        </small>
                                                    @endif
                                                </div>
                                            </td>
                                            <td>
                                                <div class="btn-group">
                                                    <a href="{{ route('orders.show', $order->id) }}"
                                                        class="btn btn-sm btn-info" data-bs-toggle="tooltip"
                                                        title="Xem chi tiết">
                                                        <i class="fas fa-eye"></i>
                                                    </a>

                                                    @if ($order->status === 'pending')
                                                        <button type="button" class="btn btn-sm btn-danger"
                                                            data-bs-toggle="tooltip" title="Hủy đơn hàng"
                                                            onclick="cancelOrder({{ $order->id }})">
                                                            <i class="fas fa-times"></i>
                                                        </button>
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
                            {{ $orders->links('pagination::bootstrap-5') }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Modal Hủy đơn hàng -->
    <div class="modal fade" id="cancelOrderModal" tabindex="-1" aria-labelledby="cancelOrderModalLabel" aria-hidden="true">
        <div class="modal-dialog" style="max-width: 500px;">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="cancelOrderModalLabel">Hủy đơn hàng</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body p-3">
                    <div class="alert alert-warning">
                        <i class="fas fa-exclamation-triangle"></i>
                        Bạn có chắc chắn muốn hủy đơn hàng này? Hành động này không thể hoàn tác.
                    </div>
                    <div class="mb-3">
                        <label for="cancel_reason" class="form-label">Lý do hủy đơn hàng <span
                                class="text-danger">*</span></label>
                        <textarea class="form-control" id="cancel_reason" rows="3" required
                            placeholder="Vui lòng nhập lý do hủy đơn hàng"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Đóng</button>
                    <button type="button" class="btn btn-danger" onclick="confirmCancelOrder()">
                        <i class="fas fa-times"></i> Xác nhận hủy
                    </button>
                </div>
            </div>
        </div>
    </div>

@endsection

@push('scripts')
    <script>
        let currentOrderId = null;

        function cancelOrder(orderId) {
            currentOrderId = orderId;
            const modal = new bootstrap.Modal(document.getElementById('cancelOrderModal'));
            modal.show();
        }

        function confirmCancelOrder() {
            const cancelReason = document.getElementById('cancel_reason').value.trim();
            if (!cancelReason) {
                toastr.error('Vui lòng nhập lý do hủy đơn hàng');
                return;
            }

            fetch(`/orders/${currentOrderId}/cancel`, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Accept': 'application/json',
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({
                        cancel_reason: cancelReason
                    })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        toastr.success(data.message);
                        // Đóng modal
                        const modal = bootstrap.Modal.getInstance(document.getElementById('cancelOrderModal'));
                        modal.hide();
                        // Reset form
                        document.getElementById('cancel_reason').value = '';
                        // Reload trang
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
    </script>
@endpush