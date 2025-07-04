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
                <h1>Giỏ hàng</h1>
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
                            Giỏ hàng
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('content')
    <div class="cart-section section-padding">
        <div class="container">
            <div class="main-cart-wrapper">
                <div class="row g-5">
                    @if (session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            {{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif

                    @if (session('error'))
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            {{ session('error') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif
                    <div class="col-xl-9">
                        <div class="table-responsive">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th>
                                            <input type="checkbox" name="" data-bs-toggle="tooltip"
                                                title="Chọn tất cả">
                                        </th>
                                        <th>Sản phẩm</th>
                                        <th>Giá</th>
                                        <th>Số lượng</th>
                                        <th>Tổng</th>
                                        <th>
                                            <button type="button" class="btn-clear-cart">
                                                <img src="{{ asset('client/img/icon/icon-9.svg') }}" alt="img">
                                            </button>
                                        </th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($cartItems as $item)
                                        <tr>
                                            <td>
                                                <input type="checkbox" name="" data-bs-toggle="tooltip"
                                                    title="Chọn mua">
                                            </td>
                                            <td>
                                                <span class="d-flex gap-5 align-items-center">
                                                    <span class="cart">
                                                        @if ($item->productVariant->product->cover_image_url)
                                                            <img src="{{ $item->productVariant->product->cover_image_url }}"
                                                                alt="img" class="img-thumbnail"
                                                                style="width: 50px; height: 70px; object-fit: cover;">
                                                        @else
                                                            <img src="{{ asset('auth/img/book_defaut.png') }}"
                                                                class="img-thumbnail"
                                                                style="width: 50px; height: 70px; object-fit: cover;">
                                                        @endif
                                                    </span>
                                                    <span class="cart-title">
                                                        <a
                                                            href="{{ route('client.products.show', $item->productVariant->product->slug) }}">
                                                            {{ $item->productVariant->product->title ?? 'Sản phẩm không tồn tại' }}
                                                        </a>
                                                        <br>
                                                        <small>
                                                            @if ($item->productVariant)
                                                                Loại: {{ $item->productVariant->format->name ?? '' }} -
                                                                {{ $item->productVariant->language->name ?? '' }}
                                                            @endif
                                                        </small>
                                                    </span>
                                                </span>
                                            </td>
                                            <td>
                                                <span class="cart-price">
                                                    @if ($item->productVariant->promotion_price)
                                                        <span
                                                            style="text-decoration: line-through; color: gray; margin-right: 8px;">
                                                            {{ number_format($item->productVariant->price ?? 0) }}₫
                                                        </span>
                                                        <span>
                                                            {{ number_format($item->productVariant->promotion_price ?? 0) }}₫
                                                        </span>
                                                    @else
                                                        {{ number_format($item->productVariant->price ?? 0) }}₫
                                                    @endif
                                                </span>
                                            </td>
                                            <td>
                                                <span class="quantity-basket">
                                                    <span class="qty">
                                                        <button class="qtyminus" aria-hidden="true">−</button>
                                                        <input type="number" name="qty" id="qty2"
                                                            class="cart-qty-input" data-item-id="{{ $item->id }}"
                                                            min="1" max="{{ $item->productVariant->stock }}"
                                                            value="{{ $item->quantity }}" readonly>
                                                        <button class="qtyplus" aria-hidden="true">+</button>
                                                    </span>
                                                </span>
                                            </td>
                                            <td>
                                                <span class="subtotal-price">
                                                    @if ($item->productVariant->promotion_price)
                                                        {{ number_format(($item->productVariant->promotion_price ?? 0) * $item->quantity) }}₫
                                                    @else
                                                        {{ number_format(($item->productVariant->price ?? 0) * $item->quantity) }}₫
                                                    @endif
                                                </span>
                                            </td>
                                            <td>
                                                <button type="button" class="btn-remove-item" data-id="{{ $item->id }}">
                                                    <img src="{{ asset('client/img/icon/icon-9.svg') }}" alt="img">
                                                </button>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="4" class="text-center">Giỏ hàng trống</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                        <div class="cart-wrapper-footer">
                            <form action="{{ route('cart.index') }}" method="GET">
                                <div class="input-area">
                                    <input type="text" name="promotion_code" id="promotion_code"
                                        placeholder="Mã khuyến mãi" value="{{ request('promotion_code') }}">
                                    <button type="submit" class="theme-btn">
                                        Áp dụng
                                    </button>
                                </div>
                            </form>
                            <a href="" class="theme-btn">
                                Cập nhật
                            </a>
                        </div>
                        @if ($promotionError)
                            <div class="text-danger">{{ $promotionError }}</div>
                        @endif
                    </div>
                    <div class="col-xl-3">
                        <div class="table-responsive cart-total">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th>Tổng hợp giỏ hàng</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>
                                            <span class="d-flex gap-5 align-items-center justify-content-between">
                                                <span class="sub-title">Tạm tính:</span>
                                                <span class="sub-price">{{ number_format($totalPrice) }}₫</span>
                                            </span>
                                        </td>
                                    </tr>
                                    @if ($promotion && !$promotionError)
                                        <tr>
                                            <td>
                                                <span class="d-flex gap-5 align-items-center justify-content-between">
                                                    <span class="sub-title">Khuyến mãi:</span>
                                                    <span class="discount">{{ number_format($discountAmount) }}₫</span>
                                                </span>
                                            </td>
                                        </tr>
                                    @endif
                                    <tr>
                                        <td>
                                            <span class="d-flex gap-5 align-items-center  justify-content-between">
                                                <span class="sub-title">Phí vận chuyển:</span>
                                                <span class="sub-text">
                                                    Free
                                                </span>
                                            </span>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <span class="d-flex gap-5 align-items-center  justify-content-between">
                                                <span class="sub-title">Tổng tiền: </span>
                                                <span
                                                    class="sub-price sub-price-total">{{ number_format($finalTotal) }}₫</span>
                                            </span>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                            <a href="{{ route('orders.add', ['promotion_code' => request('promotion_code')]) }}" class="theme-btn">
                                Tiến hành thanh toán
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        $(document).ready(function() {
            // Xử lý nút +/-
            $('.qtyplus').click(function() {
                var $input = $(this).siblings('input.cart-qty-input');
                var max = parseInt($input.attr('max'));
                var val = parseInt($input.val()) || 1;
                if (val < max) $input.val(val + 1).trigger('change');
            });
            $('.qtyminus').click(function() {
                var $input = $(this).siblings('input.cart-qty-input');
                var val = parseInt($input.val()) || 1;
                if (val > 1) $input.val(val - 1).trigger('change');
            });

            // Xử lý AJAX khi thay đổi số lượng
            $('.cart-qty-input').on('change', function() {
                var $input = $(this);
                var itemId = $input.data('item-id');
                var quantity = $input.val();

                $.ajax({
                    url: '{{ route('cart.update') }}',
                    method: 'POST',
                    data: {
                        _token: '{{ csrf_token() }}',
                        item_id: itemId,
                        quantity: quantity
                    },
                    success: function(res) {
                        if (res.success) {
                            // Cập nhật lại tổng từng dòng
                            $input.closest('tr').find('.subtotal-price').text(res.item_total +
                                '₫');
                            // Cập nhật tổng giỏ hàng
                            $('.sub-price').first().text(res.cart_total + '₫');
                            // Cập nhật tổng tiền thanh toán cuối cùng
                            $('.sub-price-total').text(res.final_total + '₫');
                            toastr.success(res.message);
                        } else {
                            toastr.error(res.message);
                            // alert("Cập nhật giỏ hàng thất bại!");
                        }
                    }
                });
            });

            // Xóa từng sản phẩm
            $('.table').on('click', '.btn-remove-item', function() {
                if (!confirm('Xác nhận xóa sản phẩm trong giỏ hàng?')) return;
                var $btn = $(this);
                var itemId = $btn.data('id');
                $.ajax({
                    url: '/cart/remove/' + itemId,
                    method: 'DELETE',
                    data: {
                        _token: '{{ csrf_token() }}'
                    },
                    success: function(res) {
                        if (res.success) {
                            toastr.success(res.message);
                            // Xóa dòng sản phẩm khỏi bảng
                            $btn.closest('tr').remove();
                            setTimeout(function() {
                                location.reload();
                            }, 1000);
                        } else {
                            toastr.error(res.message || 'Xóa sản phẩm thất bại!');
                        }
                    },
                    error: function() {
                        toastr.error('Có lỗi xảy ra!');
                    }
                });
            });

            // Xóa toàn bộ giỏ hàng
            $('.btn-clear-cart').click(function() {
                if (!confirm('Xác nhận xóa toàn bộ giỏ hàng?')) return;
                $.ajax({
                    url: '{{ route('cart.clear') }}',
                    method: 'DELETE',
                    data: {
                        _token: '{{ csrf_token() }}'
                    },
                    success: function(res) {
                        toastr.success('Đã xóa toàn bộ giỏ hàng!');
                        setTimeout(function() {
                            location.reload();
                        }, 1000);
                    },
                    error: function() {
                        toastr.error('Có lỗi xảy ra!');
                    }
                });
            });
        });
    </script>
@endpush