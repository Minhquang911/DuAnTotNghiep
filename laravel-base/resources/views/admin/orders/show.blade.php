@extends('layouts.admin.AdminLayout')

@section('title', 'Chi tiết đơn hàng #' . $order->order_code)

@section('content')
<div class="container-fluid">
    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Chi tiết đơn hàng #{{ $order->order_code }}</h1>
        <a href="{{ route('admin.orders.index') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Quay lại
        </a>
    </div>

    <div class="row">
        <!-- Thông tin đơn hàng -->
        <div class="col-xl-8 col-lg-7">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Thông tin đơn hàng</h6>
                </div>
                <div class="card-body">
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <h6 class="font-weight-bold">Trạng thái đơn hàng</h6>
                            <span class="badge bg-{{ $order->status_color }}">{{ $order->status_text }}</span>
                        </div>
                        <div class="col-md-6">
                            <h6 class="font-weight-bold">Trạng thái thanh toán</h6>
                            <span class="badge bg-{{ $order->payment_status_color }}">{{ $order->payment_status_text }}</span>
                        </div>
                    </div>

                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <thead class="bg-light">
                                <tr>
                                    <th>Sản phẩm</th>
                                    <th class="text-center">Số lượng</th>
                                    <th class="text-end">Đơn giá</th>
                                    <th class="text-end">Thành tiền</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($order->orderItems as $item)
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            @if($item->product_image)
                                                <img src="{{ asset('storage/' . $item->product_image) }}" 
                                                     alt="{{ $item->product_name }}" 
                                                     class="img-thumbnail me-2" 
                                                     style="width: 50px; height: 50px; object-fit: cover;">
                                            @endif
                                            <div>
                                                <div class="fw-bold">{{ $item->product_name}}</div>
                                                @if($item->product_variant_name)
                                                    <small class="text-muted">
                                                        Phân loại: {{ $item->product_variant_name }}
                                                    </small>
                                                @endif
                                            </div>
                                        </div>
                                    </td>
                                    <td class="text-center">{{ $item->quantity }}</td>
                                    <td class="text-end">{{ number_format($item->price) }} VNĐ</td>
                                    <td class="text-end">{{ number_format($item->quantity * $item->price) }} VNĐ</td>
                                </tr>
                                @endforeach
                            </tbody>
                            <tfoot>
                                <tr class="fw-bold">
                                    <td colspan="3" class="text-end">Tạm tính:</td>
                                    <td class="text-end">{{ number_format($order->total_amount) }} VNĐ</td>
                                </tr>
                                @if($order->shipping_fee > 0)
                                <tr>
                                    <td colspan="3" class="text-end">Phí vận chuyển:</td>
                                    <td class="text-end">{{ number_format($order->shipping_fee) }} VNĐ</td>
                                </tr>
                                @endif
                                @if($order->discount_amount > 0)
                                <tr>
                                    <td colspan="3" class="text-end">Giảm giá:</td>
                                    <td class="text-end text-danger">-{{ number_format($order->discount_amount) }} VNĐ</td>
                                </tr>
                                @endif
                                <tr class="fw-bold">
                                    <td colspan="3" class="text-end">Tổng cộng:</td>
                                    <td class="text-end">{{ number_format($order->amount_due) }} VNĐ</td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>

                    @if($order->note)
                    <div class="mt-3">
                        <h6 class="font-weight-bold">Ghi chú đơn hàng:</h6>
                        <p class="mb-0">{{ $order->note }}</p>
                    </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Thông tin khách hàng -->
        <div class="col-xl-4 col-lg-5">
            <!-- Thông tin người đặt hàng -->
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Thông tin người đặt hàng</h6>
                </div>
                <div class="card-body">
                    @if($order->user)
                    <div class="d-flex align-items-center mb-3">
                        @if($order->user->avatar)
                            <img src="{{ asset('storage/' . $order->user->avatar) }}" 
                                 alt="Avatar" 
                                 class="img-thumbnail rounded-circle me-3" 
                                 style="width: 64px; height: 64px; object-fit: cover;">
                        @else
                            <div class="avatar-circle bg-primary text-white me-3" style="width: 64px; height: 64px;">
                                {{ strtoupper(substr($order->user->name, 0, 1)) }}
                            </div>
                        @endif
                        <div>
                            <h6 class="mb-1">{{ $order->user->name }}</h6>
                            <p class="mb-0 text-muted">{{ $order->user->email }}</p>
                        </div>
                    </div>
                    @endif
                    <div class="mb-2">
                        <small class="text-muted d-block">Mã đơn hàng:</small>
                        <strong>{{ $order->order_code }}</strong>
                    </div>
                    <div class="mb-2">
                        <small class="text-muted d-block">Ngày đặt hàng:</small>
                        <strong>{{ $order->ordered_at->format('d/m/Y H:i') }}</strong>
                    </div>
                    @if($order->paid_at)
                    <div class="mb-2">
                        <small class="text-muted d-block">Ngày thanh toán:</small>
                        <strong>{{ $order->paid_at->format('d/m/Y H:i') }}</strong>
                    </div>
                    @endif
                    <div class="mb-2">
                        <small class="text-muted d-block">Phương thức thanh toán:</small>
                        <strong>{{ $order->payment_method_text }}</strong>
                    </div>
                    @if($order->coupon_code)
                    <div class="mb-2">
                        <small class="text-muted d-block">Mã giảm giá:</small>
                        <strong>{{ $order->coupon_code }}</strong>
                    </div>
                    @endif
                </div>
            </div>

            <!-- Thông tin người nhận hàng -->
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Thông tin người nhận hàng</h6>
                </div>
                <div class="card-body">
                    <div class="mb-2">
                        <small class="text-muted d-block">Họ tên:</small>
                        <strong>{{ $order->customer_name }}</strong>
                    </div>
                    <div class="mb-2">
                        <small class="text-muted d-block">Email:</small>
                        <strong>{{ $order->customer_email }}</strong>
                    </div>
                    <div class="mb-2">
                        <small class="text-muted d-block">Số điện thoại:</small>
                        <strong>{{ $order->customer_phone }}</strong>
                    </div>
                    <div class="mb-2">
                        <small class="text-muted d-block">Địa chỉ:</small>
                        <strong>
                            {{ $order->customer_address }}, 
                            {{ $order->customer_ward }}, 
                            {{ $order->customer_district }}, 
                            {{ $order->customer_province }}
                        </strong>
                    </div>
                </div>
            </div>

            <!-- Cập nhật trạng thái -->
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Cập nhật trạng thái</h6>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <label class="form-label">Trạng thái hiện tại</label>
                        <div class="d-flex align-items-center">
                            <span class="badge bg-{{ $order->status_color }} me-2">{{ $order->status_text }}</span>
                            @if($order->payment_status === 'paid')
                                <span class="badge bg-success">Đã thanh toán</span>
                            @endif
                        </div>
                    </div>

                    @if($order->status !== 'finished' && $order->status !== 'cancelled')
                        <div class="mb-3">
                            <label for="status" class="form-label">Cập nhật trạng thái</label>
                            <select id="status" class="form-select">
                                <option value="">Chọn trạng thái mới</option>
                                @php
                                    $statusFlow = [
                                        'pending' => ['processing'],
                                        'processing' => ['delivering'],
                                        'delivering' => ['completed', 'failed'],
                                        'completed' => ['finished'],
                                        'failed' => ['delivering']
                                    ];
                                    $nextStatuses = $statusFlow[$order->status] ?? [];
                                @endphp
                                @foreach($nextStatuses as $status)
                                    <option value="{{ $status }}">{{ $order->status_options[$status] }}</option>
                                @endforeach
                            </select>
                        </div>

                        <button type="button" onclick="updateOrderStatus()" class="btn btn-primary w-100" id="updateStatusBtn" disabled>
                            <i class="fas fa-save"></i> Cập nhật trạng thái
                        </button>
                    @else
                        <div class="alert alert-info mb-0">
                            @if($order->status === 'finished')
                                Đơn hàng đã hoàn thành và không thể cập nhật trạng thái.
                            @else
                                Đơn hàng đã bị hủy và không thể cập nhật trạng thái.
                            @endif
                        </div>
                    @endif

                    @if($order->payment_status === 'unpaid' && $order->payment_method === 'bank_transfer')
                        <hr>
                        <div class="mb-3">
                            <label for="payment_status" class="form-label">Cập nhật trạng thái thanh toán</label>
                            <select id="payment_status" class="form-select">
                                <option value="">Chọn trạng thái thanh toán</option>
                                <option value="paid">Đã thanh toán</option>
                            </select>
                        </div>
                        <button type="button" onclick="updatePaymentStatus()" class="btn btn-success w-100" id="updatePaymentBtn" disabled>
                            <i class="fas fa-money-bill-wave"></i> Cập nhật thanh toán
                        </button>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

@push('styles')
<style>
    .avatar-circle {
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.5rem;
        font-weight: bold;
    }
</style>
@endpush
@endsection

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

        // Xử lý select trạng thái
        document.getElementById('status')?.addEventListener('change', function() {
            document.getElementById('updateStatusBtn').disabled = !this.value;
        });

        // Xử lý select trạng thái thanh toán
        document.getElementById('payment_status')?.addEventListener('change', function() {
            document.getElementById('updatePaymentBtn').disabled = !this.value;
        });

        // Hàm cập nhật trạng thái đơn hàng
        function updateOrderStatus() {
            const status = document.getElementById('status').value;
            if (!status) return;

            if (confirm('Bạn có chắc chắn muốn cập nhật trạng thái đơn hàng?')) {
                fetch(`{{ route('admin.orders.update-status', $order) }}`, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Accept': 'application/json',
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({ status: status })
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

        // Hàm cập nhật trạng thái thanh toán
        function updatePaymentStatus() {
            const paymentStatus = document.getElementById('payment_status').value;
            if (!paymentStatus) return;

            if (confirm('Bạn có chắc chắn muốn cập nhật trạng thái thanh toán?')) {
                fetch(`{{ route('admin.orders.update-status', $order) }}`, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Accept': 'application/json',
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({ 
                        payment_status: paymentStatus,
                        paid_at: paymentStatus === 'paid' ? new Date().toISOString() : null
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
                    toastr.error('Có lỗi xảy ra khi cập nhật trạng thái thanh toán');
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