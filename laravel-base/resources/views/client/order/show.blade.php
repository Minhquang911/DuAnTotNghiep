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
                <!-- Page Heading -->
                <div class="d-sm-flex align-items-center justify-content-between mb-4">
                    <h1 class="h3">Chi tiết đơn hàng #{{ $order->order_code }}</h1>
                    <a href="{{ route('orders.index') }}" class="btn btn-secondary">
                        <i class="fas fa-arrow-left"></i> Quay lại
                    </a>
                </div>

                <div class="row">
                    <!-- Thông tin đơn hàng -->
                    <div class="col-xl-8 col-lg-7">
                        <div class="card shadow mb-4">
                            <div class="card-header py-3 d-flex justify-content-between align-items-center">
                                <h6 class="m-0 font-weight-bold text-primary">Thông tin đơn hàng</h6>
                                <div>
                                    @if (
                                        $order->status !== 'finished' &&
                                            $order->status !== 'cancelled' &&
                                            in_array($order->status, ['pending', 'processing']))
                                        <button type="button" class="btn btn-danger btn-sm" data-bs-toggle="modal"
                                            data-bs-target="#cancelOrderModal">
                                            <i class="fas fa-times"></i> Hủy đơn hàng
                                        </button>
                                    @endif



                                    @if ($order->status === 'completed')
                                        <button id="btn-confirm-received" type="button" class="btn btn-success btn-sm">
                                            <i class="fas fa-check"></i> Xác nhận nhận hàng
                                        </button>
                                    @endif

                                    @if ($order->payment_status === \App\Models\Order::PAYMENT_STATUS_UNPAID && $order->payment_method === 'bank_transfer')
                                        <button id="btn-continue-payment" class="btn btn-primary btn-sm"
                                            data-order-id="{{ $order->id }}">Tiếp tục thanh toán</button>
                                        <form action="{{ route('orders.continue-payment', $order->id) }}" method="POST"
                                            style="display:inline;">
                                            @csrf
                                            <button type="submit" class="btn btn-primary btn-sm"></button>
                                        </form>
                                    @endif
                                </div>
                            </div>
                            <div class="card-body">
                                <div class="row mb-4">
                                    <div class="col-md-6">
                                        <h6 class="font-weight-bold">Trạng thái đơn hàng</h6>
                                        <span class="badge bg-{{ $order->status_color }}">{{ $order->status_text }}</span>
                                    </div>
                                    <div class="col-md-6">
                                        <h6 class="font-weight-bold">Trạng thái thanh toán</h6>
                                        <span
                                            class="badge bg-{{ $order->payment_status_color }}">{{ $order->payment_status_text }}</span>
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
                                            @foreach ($order->orderItems as $item)
                                                <tr>
                                                    <td>
                                                        <div class="d-flex align-items-center">
                                                            @if ($item->product_image)
                                                                <img src="{{ asset('storage/' . $item->product_image) }}"
                                                                    alt="{{ $item->product_name }}"
                                                                    class="img-thumbnail me-2"
                                                                    style="width: 50px; object-fit: cover;">
                                                            @endif
                                                            <div>
                                                                <div class="fw-bold">
                                                                    {{ $item->product_name }}
                                                                </div>
                                                                @if ($item->product_variant_name)
                                                                    <small class="text-muted">
                                                                        Phân loại: {{ $item->product_variant_name }}
                                                                    </small>
                                                                @endif
                                                                @if ($order->status === 'finished' && !$item->is_rated)
                                                                    <div class="mt-2">
                                                                        <button
                                                                            class="btn btn-warning btn-sm btn-rate-product"
                                                                            data-product-id="{{ $item->product_id }}"
                                                                            data-product-variant-id="{{ $item->product_variant_id }}"
                                                                            data-order-item-id="{{ $item->id }}">
                                                                            <i class="fas fa-star"></i> Đánh giá
                                                                        </button>
                                                                    </div>
                                                                @endif
                                                            </div>
                                                        </div>
                                                    </td>
                                                    <td class="text-center">{{ $item->quantity }}</td>
                                                    <td class="text-end">{{ number_format($item->price) }} VNĐ</td>
                                                    <td class="text-end">
                                                        {{ number_format($item->quantity * $item->price) }} VNĐ</td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                        <tfoot>
                                            <tr class="fw-bold">
                                                <td colspan="3" class="text-end">Tạm tính:</td>
                                                <td class="text-end">{{ number_format($order->total_amount) }} VNĐ</td>
                                            </tr>
                                            @if ($order->shipping_fee > 0)
                                                <tr>
                                                    <td colspan="3" class="text-end">Phí vận chuyển:</td>
                                                    <td class="text-end">{{ number_format($order->shipping_fee) }} VNĐ</td>
                                                </tr>
                                            @else
                                                <tr>
                                                    <td colspan="3" class="text-end">Phí vận chuyển:</td>
                                                    <td class="text-end">Free Ship</td>
                                                </tr>
                                            @endif
                                            @if ($order->discount_amount > 0)
                                                <tr>
                                                    <td colspan="3" class="text-end">Giảm giá:</td>
                                                    <td class="text-end text-danger">
                                                        -{{ number_format($order->discount_amount) }} VNĐ</td>
                                                </tr>
                                            @endif
                                            <tr class="fw-bold">
                                                <td colspan="3" class="text-end">Tổng cộng:</td>
                                                <td class="text-end" style="color: tomato">
                                                    {{ number_format($order->amount_due) }} VNĐ</td>
                                            </tr>
                                        </tfoot>
                                    </table>
                                </div>

                                @if ($order->note)
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
                                @if ($order->user)
                                    <div class="d-flex align-items-center mb-3">
                                        @if ($order->user->avatar)
                                            <img src="{{ asset('storage/' . $order->user->avatar) }}" alt="Avatar"
                                                class="img-thumbnail rounded-circle me-3"
                                                style="width: 64px; height: 64px; object-fit: cover;">
                                        @else
                                            <div class="avatar-circle bg-primary text-white me-3"
                                                style="width: 64px; height: 64px;">
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
                                @if ($order->paid_at)
                                    <div class="mb-2">
                                        <small class="text-muted d-block">Ngày thanh toán:</small>
                                        <strong>{{ $order->paid_at->format('d/m/Y H:i') }}</strong>
                                    </div>
                                @endif
                                <div class="mb-2">
                                    <small class="text-muted d-block">Phương thức thanh toán:</small>
                                    <strong>{{ $order->payment_method_text }}</strong>
                                </div>
                                @if ($order->coupon_code)
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

                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Modal Hủy đơn hàng -->
    <div class="modal fade" id="cancelOrderModal" tabindex="-1" aria-labelledby="cancelOrderModalLabel"
        aria-hidden="true">
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
    <!-- Modal Đánh giá sản phẩm -->
    <div class="modal fade" id="rateProductModal" tabindex="-1" aria-labelledby="rateProductModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" style="max-width: 500px;">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="rateProductModalLabel">Đánh giá sản phẩm</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Đóng"></button>
                </div>
                <div class="modal-body p-3">
                    <form id="rateProductForm">
                        <input type="hidden" name="product_id" id="rate_product_id">
                        <input type="hidden" name="product_variant_id" id="rate_product_variant_id">
                        <input type="hidden" name="order_item_id" id="rate_order_item_id">
                        <div class="mb-3">
                            <label class="form-label">Đánh giá của bạn</label>
                            <div id="rate-stars" class="mb-2">
                                @for ($i = 1; $i <= 5; $i++)
                                    <i class="fa-star fa-regular rate-star" data-value="{{ $i }}"
                                        style="font-size: 1.5rem; color: #ffc107; cursor: pointer;"></i>
                                @endfor
                            </div>
                            <input type="hidden" name="rating" id="rate_rating" value="0">
                        </div>
                        <div class="mb-3">
                            <label for="rate_comment" class="form-label">Nhận xét</label>
                            <textarea class="form-control" id="rate_comment" name="comment" rows="3"
                                placeholder="Nhập nhận xét của bạn..."></textarea>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Đóng</button>
                    <button type="button" class="btn btn-primary" id="btn-submit-rating">Gửi đánh giá</button>
                </div>
            </div>
        </div>
    </div>
    <style>
        .avatar-circle {
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.5rem;
            font-weight: bold;
        }
    </style>
@endsection

@push('scripts')
    <script>
        function confirmCancelOrder() {
            const cancelReason = document.getElementById('cancel_reason').value.trim();
            if (!cancelReason) {
                toastr.error('Vui lòng nhập lý do hủy đơn hàng');
                return;
            }

            fetch(`{{ route('orders.cancel', $order) }}`, {
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

        $(document).ready(function() {
            $('#btn-confirm-received').on('click', function() {
                if (!confirm('Bạn có chắc chắn đã nhận được hàng? Hành động này không thể hoàn tác.'))
                    return;
                $.ajax({
                    url: '{{ route('orders.confirm-received', $order->id) }}',
                    type: 'POST',
                    data: {
                        _token: '{{ csrf_token() }}'
                    },
                    success: function(response) {
                        if (response.success) {
                            toastr.success(response.message);
                            $('#btn-confirm-received').remove();
                            // Có thể reload hoặc cập nhật trạng thái giao diện tại đây
                            location.reload();
                        } else {
                            toastr.error(response.message);
                        }
                    },
                    error: function(xhr) {
                        let msg = xhr.responseJSON && xhr.responseJSON.message ? xhr
                            .responseJSON.message : 'Đã có lỗi xảy ra.';
                        toastr.error(msg);
                    }
                });
            });

            // Hiển thị modal đánh giá khi bấm nút
            $('.btn-rate-product').on('click', function() {
                const productId = $(this).data('product-id');
                const productVariantId = $(this).data('product-variant-id');
                const orderItemId = $(this).data('order-item-id');
                $('#rate_product_id').val(productId);
                $('#rate_product_variant_id').val(productVariantId);
                $('#rate_order_item_id').val(orderItemId);
                $('#rate_rating').val(0);
                $('#rate-stars .rate-star').removeClass('fa-solid').addClass('fa-regular');
                $('#rate_comment').val('');
                $('#rateProductModal').modal('show');
            });
            // Chọn số sao
            $('#rate-stars .rate-star').on('mouseenter click', function() {
                const val = $(this).data('value');
                $('#rate-stars .rate-star').each(function(idx) {
                    if (idx < val) {
                        $(this).removeClass('fa-regular').addClass('fa-solid');
                    } else {
                        $(this).removeClass('fa-solid').addClass('fa-regular');
                    }
                });
                $('#rate_rating').val(val);
            });
            // Khi rời chuột khỏi vùng sao, giữ lại số sao đã chọn
            $('#rate-stars').on('mouseleave', function() {
                const val = $('#rate_rating').val();
                $('#rate-stars .rate-star').each(function(idx) {
                    if (idx < val) {
                        $(this).removeClass('fa-regular').addClass('fa-solid');
                    } else {
                        $(this).removeClass('fa-solid').addClass('fa-regular');
                    }
                });
            });

            // Gửi đánh giá khi bấm nút
            $('#btn-submit-rating').on('click', function() {
                var data = {
                    product_id: $('#rate_product_id').val(),
                    product_variant_id: $('#rate_product_variant_id').val(),
                    order_item_id: $('#rate_order_item_id').val(),
                    rating: $('#rate_rating').val(),
                    comment: $('#rate_comment').val(),
                    _token: '{{ csrf_token() }}'
                };
                if (data.rating < 1 || data.rating > 5) {
                    toastr.error('Vui lòng chọn số sao đánh giá!');
                    return;
                }
                $.ajax({
                    url: '{{ route('client.products.store-rating') }}',
                    type: 'POST',
                    data: data,
                    success: function(response) {
                        if (response.success) {
                            toastr.success(response.message);
                            $('#rateProductModal').modal('hide');
                            setTimeout(function() {
                                location.reload();
                            }, 1000);
                        } else {
                            toastr.error(response.message);
                        }
                    },
                    error: function(xhr) {
                        let msg = xhr.responseJSON && xhr.responseJSON.message ? xhr
                            .responseJSON.message : 'Đã có lỗi xảy ra.';
                        toastr.error(msg);
                    }
                });
            });

            // Xử lý tiếp tụ thanh toán online
            $('#btn-continue-payment').on('click', function(e) {
                e.preventDefault();
                var orderId = $(this).data('order-id');
                var $btn = $(this);
                $btn.prop('disabled', true);

                $.ajax({
                    url: '/orders/' + orderId + '/continue-payment',
                    type: 'POST',
                    data: {
                        _token: '{{ csrf_token() }}'
                    },
                    success: function(response) {
                        if (response.success && response.redirect_url) {
                            window.location.href = response.redirect_url;
                        } else if (response.success === false && response.message) {
                            toastr.error(response.message);
                        } else {
                            toastr.error('Đã có lỗi xảy ra.');
                        }
                    },
                    error: function(xhr) {
                        let msg = xhr.responseJSON && xhr.responseJSON.message ? xhr
                            .responseJSON.message : 'Đã có lỗi xảy ra.';
                        toastr.error(msg);
                    },
                    complete: function() {
                        $btn.prop('disabled', false);
                    }
                });
            });
        });
    </script>
@endpush