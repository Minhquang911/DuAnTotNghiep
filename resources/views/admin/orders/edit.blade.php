@extends('admin.layout.AdminLayout')

@section('content')
    <div class="container py-4">
        <div class="card shadow-sm">
            <div class="card-header bg-primary text-white">
                <h4>Sửa đơn hàng #{{ $order->id }}</h4>
            </div>
            <div class="card-body">
                {{-- Hiển thị lỗi nếu có --}}
                @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul class="mb-0">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form action="{{ route('admin.orders.update', $order->id) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label">Tên khách hàng</label>
                            <input type="text" class="form-control" name="user_name" value="{{ $order->user_name }}" required>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Email</label>
                            <input type="email" class="form-control" name="user_email" value="{{ $order->user_email }}" required>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Số điện thoại</label>
                            <input type="text" class="form-control" name="user_phone" value="{{ $order->user_phone }}" required>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Phương thức thanh toán</label>
                            <select name="payment_method" class="form-select" required>
                                <option value="COD" @selected($order->payment_method == 'COD')>COD</option>
                                <option value="Online" @selected($order->payment_method == 'Online')>Online</option>
                            </select>
                        </div>

                        <div class="col-md-12">
                            <label class="form-label">Địa chỉ nhận hàng</label>
                            <textarea name="shipping_address" class="form-control" required>{{ $order->shipping_address }}</textarea>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Trạng thái đơn hàng</label>
                            <select name="status_id" class="form-select" required>
                                <option value="">-- Chọn trạng thái --</option>
                                @foreach ($statuses as $status)
                                    <option value="{{ $status->id }}" {{ $order->status_id == $status->id ? 'selected' : '' }}>
                                        {{ $status->status_name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-md-4">
                            <label class="form-label">Mã khuyến mãi</label>
                            <input type="text" class="form-control" name="promotion_code" value="{{ $order->promotion_code }}">
                        </div>

                        <div class="col-md-4">
                            <label class="form-label">Giảm giá</label>
                            <input type="number" class="form-control" name="promotion_discount" value="{{ $order->promotion_discount }}">
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Phí vận chuyển</label>
                            <input type="number" class="form-control" name="shipping_fee" value="{{ $order->shipping_fee }}" required>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Tổng thanh toán</label>
                            <input type="number" class="form-control" name="final_amount" value="{{ $order->final_amount }}" required>
                        </div>
                    </div>

                    <div class="mt-4 text-end">
                        <button type="submit" class="btn btn-success">Lưu thay đổi</button>
                        <a href="{{ route('admin.orders.index') }}" class="btn btn-secondary">Quay lại</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
