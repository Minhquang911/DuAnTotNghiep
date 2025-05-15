@extends('admin.layout.AdminLayout')
@section('content')
    <div class="container py-4">
        <div class="card shadow-sm">
            <div class="card-header bg-info text-white">
                <h4>Chi tiết đơn hàng #{{ $order->id }}</h4>
            </div>
            <div class="card-body">
                <ul class="list-group">
                    <li class="list-group-item"><strong>Tên KH:</strong> {{ $order->user_name }}</li>
                    <li class="list-group-item"><strong>Email:</strong> {{ $order->user_email }}</li>
                    <li class="list-group-item"><strong>SĐT:</strong> {{ $order->user_phone }}</li>
                    <li class="list-group-item"><strong>Địa chỉ:</strong> {{ $order->shipping_address }}</li>
                    <li class="list-group-item"><strong>Tổng tiền:</strong> {{ number_format($order->total_amount) }}</li>
                    <li class="list-group-item"><strong>Trạng thái:</strong> {{ $order->status_id }}</li>
                    <li class="list-group-item"><strong>Thanh toán:</strong> {{ $order->payment_method }}</li>
                    <li class="list-group-item"><strong>Mã KM:</strong> {{ $order->promotion_code ?? 'Không có' }}</li>
                    <li class="list-group-item"><strong>Giảm giá:</strong> {{ number_format($order->promotion_discount) }}</li>
                    <li class="list-group-item"><strong>Phí ship:</strong> {{ number_format($order->shipping_fee) }}</li>
                    <li class="list-group-item"><strong>Thanh toán:</strong> <span class="text-success fw-bold">{{ number_format($order->final_amount) }}</span></li>
                    <li class="list-group-item"><strong>Ngày đặt:</strong> {{ $order->created_at->format('d/m/Y H:i') }}</li>
                </ul>
            </div>
            <div class="card-footer text-end">
                <a href="{{ route('admin.orders.index') }}" class="btn btn-secondary">Quay lại</a>
            </div>
        </div>
    </div>
@endsection
