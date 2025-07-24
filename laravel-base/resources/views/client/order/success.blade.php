@extends('layouts.client.ClientLayout')

@section('title')
    {{ isset($title) ? $title . ' - Book360 Store WooCommerce' : 'Book360 Store WooCommerce' }}
@endsection

@section('banner')

@endsection

@section('content')
<div class="order-success-container" style="display: flex; justify-content: center; align-items: center; min-height: 60vh;">
    <div class="order-success-box" style="background: #fff; border-radius: 16px; box-shadow: 0 4px 24px rgba(0,0,0,0.07); padding: 40px 32px; max-width: 600px; width: 100%; text-align: center;">
        <i class="fa-regular fa-circle-check" style="font-size: 72px; color: #28a745; margin-bottom: 24px;"></i>
        <h1 style="color: #28a745; font-size: 2.2rem; font-weight: 700; margin-bottom: 12px;">Đặt hàng thành công!</h1>
        <p style="color: #444; font-size: 1.1rem; margin-bottom: 32px;">
            Cảm ơn bạn đã mua hàng tại <b>Book360 Store</b>.<br>
            Đơn hàng của bạn đã được ghi nhận và sẽ được xử lý trong thời gian sớm nhất.
        </p>
        <div style="display: flex; gap: 16px; justify-content: center;">
            <a href="{{ route('home') }}" class="btn btn-primary" style="min-width: 140px;">Về trang chủ</a>
            <a href="{{ route('orders.index') }}" class="btn btn-outline-success" style="min-width: 140px;">Xem đơn hàng của tôi</a>
        </div>
    </div>
</div>
@endsection