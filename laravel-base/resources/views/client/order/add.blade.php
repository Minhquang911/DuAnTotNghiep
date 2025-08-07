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
                <h1>Thanh toán</h1>
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
                            Thanh toán
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('content')
    <style>
        .nice-select ul {
            background: aliceblue !important;
        }

        .nice-select ul li {
            border: none !important;
        }
    </style>
    <!-- Checkout Section Start -->
    <section class="checkout-section fix section-padding">
        <div class="container">
            <form action="{{ route('orders.store') }}" method="POST">
                @csrf
                <div class="row g-5">
                    <div class="col-lg-9">
                        <div class="checkout-single-wrapper">
                            <div class="checkout-single boxshado-single">
                                <h4>Thông tin nhận hàng</h4>
                                <div class="checkout-single-form">
                                    <div class="row g-4">
                                        <div class="col-lg-12">
                                            <div class="input-single">
                                                <span>Họ tên</span><span class="text-danger">*</span>
                                                <input type="text" name="customer_name" id="customer_name"
                                                    value="{{ $user->name ?? '' }}" placeholder="Nhập họ tên">
                                            </div>
                                        </div>

                                        <div class="col-lg-6">
                                            <div class="input-single">
                                                <span>Email</span><span class="text-danger">*</span>
                                                <input type="email" name="customer_email" id="customer_email"
                                                    value="{{ $user->email ?? '' }}" placeholder="Nhập email">
                                            </div>
                                        </div>

                                        <div class="col-lg-6">
                                            <div class="input-single">
                                                <span>Số điện thoại</span><span class="text-danger">*</span>
                                                <input type="text" name="customer_phone" id="customer_phone"
                                                    value="{{ $user->phone ?? '' }}" placeholder="Nhập số điện thoại">
                                            </div>
                                        </div>

                                        <div class="col-lg-4">
                                            <div class="input-single">
                                                <span>Thành phố/Tỉnh</span><span class="text-danger">*</span>
                                                <input type="hidden" name="customer_province_name"
                                                    id="customer_province_name">
                                                <select name="customer_province" id="customer_province">
                                                    <option value="">-- Chọn Thành phố/Tỉnh --</option>
                                                </select>
                                            </div>
                                        </div>

                                        <div class="col-lg-4">
                                            <div class="input-single">
                                                <span>Quận/Huyện</span><span class="text-danger">*</span>
                                                <input type="hidden" name="customer_district_name"
                                                    id="customer_district_name">
                                                <select name="customer_district" id="customer_district">
                                                    <option value="">-- Chọn Quận/Huyện --</option>
                                                </select>
                                            </div>
                                        </div>

                                        <div class="col-lg-4">
                                            <div class="input-single">
                                                <span>Phường/Xã</span><span class="text-danger">*</span>
                                                <input type="hidden" name="customer_ward_name" id="customer_ward_name">
                                                <select name="customer_ward" id="customer_ward">
                                                    <option value="">-- Chọn Phường/Xã --</option>
                                                </select>
                                            </div>
                                        </div>

                                        <div class="col-lg-12">
                                            <div class="input-single">
                                                <span>Địa chỉ chi tiết</span><span class="text-danger">*</span>
                                                <input type="text" name="customer_address" id="customer_address"
                                                    placeholder="Nhập địa chỉ chi tiết">
                                            </div>
                                        </div>

                                        <div class="col-lg-12">
                                            <div class="input-single">
                                                <span>Ghi chú (Tùy chọn)</span>
                                                <textarea name="notes" id="notes"
                                                    placeholder="Ghi chú về đơn hàng của bạn, ví dụ ghi chú đặc biệt về việc giao hàng."></textarea>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-3">
                        <div class="checkout-order-area">
                            <h3>Đơn hàng của bạn</h3>
                            <div class="product-checout-area">

                                <div class="checkout-item d-flex align-items-center justify-content-between">
                                    <p>Sản phẩm</p>
                                    <p>Tổng</p>
                                </div>
                                @if ($cart && $cart->items->count())
                                    @foreach ($cart->items as $item)
                                        <input type="hidden" name="product_variant_id[]" value="{{ $item->id }}">
                                        <div class="checkout-item d-flex align-items-center justify-content-between">
                                            <div class="align-items-center justify-content-between">
                                                <p>{{ $item->productVariant->product->title }} x {{ $item->quantity }}</p>
                                                <small>
                                                    @if ($item->productVariant)
                                                        Loại: {{ $item->productVariant->format->name ?? '' }} -
                                                        {{ $item->productVariant->language->name ?? '' }}
                                                    @endif
                                                </small>
                                            </div>
                                            @if ($item->productVariant->promotion_price)
                                                {{ number_format(($item->productVariant->promotion_price ?? 0) * $item->quantity) }}₫
                                            @else
                                                {{ number_format(($item->productVariant->price ?? 0) * $item->quantity) }}₫
                                            @endif
                                        </div>
                                    @endforeach
                                @else
                                    <div class="checkout-item">
                                        <p>Giỏ hàng trống.</p>
                                    </div>
                                @endif
                                <div class="checkout-item d-flex justify-content-between">
                                    <p>Tạm tính</p>
                                    <div class="shopping-items">
                                        <div class="form-check d-flex align-items-center from-customradio">
                                            <label class="form-check-label">
                                                <input type="hidden" name="total_amount" value="{{ $totalPrice }}">
                                                Tổng tiền: {{ number_format($totalPrice) }}₫
                                            </label>
                                        </div>
                                        @if ($promotion)
                                            <div class="form-check d-flex align-items-center from-customradio">
                                                <label class="form-check-label">
                                                    <input type="hidden" name=""
                                                        value="{{ request('promotion_code') }}">
                                                    <input type="hidden" name=""
                                                        value="{{ $discountAmount }}">
                                                    Khuyến mãi: {{ number_format($discountAmount) }}₫
                                                </label>
                                            </div>
                                        @endif
                                        <div class="form-check d-flex align-items-center from-customradio">
                                            <label class="form-check-label">
                                                Free Shipping
                                            </label>
                                        </div>
                                    </div>
                                </div>
                                <div class="checkout-item d-flex align-items-center justify-content-between">
                                    <input type="hidden" name="amount_due" value="{{ $finalTotal }}">
                                    <p>Thanh toán</p>
                                    <b style="color: tomato">{{ number_format($finalTotal) }}₫</b>
                                </div>
                                <div class="checkout-item-2">
                                    <div class="form-check-3 d-flex align-items-center from-customradio-2 mt-3">
                                        <input class="form-check-input" type="radio" name="payment_method"
                                            id="cod" value="cod" checked>
                                        <label class="form-check-label" for="cod">
                                            Thanh toán khi nhận hàng
                                        </label>
                                    </div>
                                    <div class="form-check-3 d-flex align-items-center from-customradio-2 mt-3">
                                        <input class="form-check-input" type="radio" name="payment_method"
                                            id="bank_transfer" value="bank_transfer">
                                        <label class="form-check-label" for="bank_transfer">
                                            Thanh toán online
                                        </label>
                                    </div>
                                </div>
                                <button type="submit" class="theme-btn w-100">Xác nhận đặt hàng</button>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </section>
@endsection

@push('scripts')
    <script>
        $(document).ready(function() {
            $('#customer_province').niceSelect();
            $('#customer_district').niceSelect();
            $('#customer_ward').niceSelect();

            // Load tỉnh
            fetch('https://provinces.open-api.vn/api/?depth=1')
                .then(res => res.json())
                .then(provinces => {
                    const provinceSelect = document.getElementById('customer_province');
                    provinceSelect.innerHTML = '<option value="">-- Chọn Thành phố/Tỉnh --</option>';
                    provinces.forEach(province => {
                        const option = document.createElement('option');
                        option.value = province.code;
                        option.textContent = province.name;
                        provinceSelect.appendChild(option);
                    });
                    $('#customer_province').niceSelect('update');
                });

            // Khi chọn tỉnh
            $('#customer_province').on('change', function() {
                const selectedText = $('#customer_province option:selected').text();
                $('#customer_province_name').val(selectedText);
                const provinceCode = $(this).val();
                const districtSelect = document.getElementById('customer_district');
                const wardSelect = document.getElementById('customer_ward');
                districtSelect.innerHTML = '<option value="">-- Chọn Quận/Huyện --</option>';
                wardSelect.innerHTML = '<option value="">-- Chọn Phường/Xã --</option>';
                $('#customer_district').niceSelect('update');
                $('#customer_ward').niceSelect('update');
                if (!provinceCode) return;
                fetch(`https://provinces.open-api.vn/api/p/${provinceCode}?depth=2`)
                    .then(res => res.json())
                    .then(data => {
                        if (!data.districts) return;
                        data.districts.forEach(district => {
                            const option = document.createElement('option');
                            option.value = district.code;
                            option.textContent = district.name;
                            districtSelect.appendChild(option);
                        });
                        $('#customer_district').niceSelect('update');
                    });
            });

            // Khi chọn quận
            $('#customer_district').on('change', function() {
                const selectedText = $('#customer_district option:selected').text();
                $('#customer_district_name').val(selectedText);
                const districtCode = $(this).val();
                const wardSelect = document.getElementById('customer_ward');
                wardSelect.innerHTML = '<option value="">-- Chọn Phường/Xã --</option>';
                $('#customer_ward').niceSelect('update');
                if (!districtCode) return;
                fetch(`https://provinces.open-api.vn/api/d/${districtCode}?depth=2`)
                    .then(res => res.json())
                    .then(data => {
                        if (!data.wards) return;
                        data.wards.forEach(ward => {
                            const option = document.createElement('option');
                            option.value = ward.code;
                            option.textContent = ward.name;
                            wardSelect.appendChild(option);
                        });
                        $('#customer_ward').niceSelect('update');
                    });
            });

            $('#customer_ward').on('change', function() {
                const selectedText = $('#customer_ward option:selected').text();
                $('#customer_ward_name').val(selectedText);
            });

            $('form[action="{{ route('orders.store') }}"]').on('submit', function(e) {
                e.preventDefault();
                var form = $(this);
                $.ajax({
                    url: form.attr('action'),
                    method: 'POST',
                    data: form.serialize(),
                    success: function(res) {
                        if (res.success === false) {
                            toastr.error(res.message);
                        } else {
                            // Thành công, chuyển trang hoặc hiển thị thông báo thành công
                            window.location.href = res.redirect_url || '/';
                        }
                    },
                    error: function(xhr) {
                        // Xử lý lỗi validate của Laravel (422)
                        if (xhr.status === 422) {
                            var errors = xhr.responseJSON.errors;
                            Object.values(errors).forEach(function(msgArr) {
                                toastr.error(msgArr[0]);
                            });
                        } else {
                            toastr.error('Có lỗi xảy ra!');
                        }
                    }
                });
            });

            function validateEmail(email) {
                // Định dạng email chuẩn
                var re = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
                return re.test(email);
            }
        });
    </script>
@endpush