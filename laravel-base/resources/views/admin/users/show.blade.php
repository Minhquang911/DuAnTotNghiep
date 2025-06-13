@extends('layouts.admin.AdminLayout')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <!-- Thông tin người dùng -->
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Thông tin chi tiết người dùng</h3>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-4 text-center mb-4">
                            @if($user->avatar)
                                <img src="{{ asset('storage/' . $user->avatar) }}" alt="Avatar" class="img-fluid rounded-circle" style="max-width: 200px;">
                            @else
                                <img src="{{ asset('images/default-avatar.png') }}" alt="Default Avatar" class="img-fluid rounded-circle" style="max-width: 200px;">
                            @endif
                        </div>
                        <div class="col-md-8">
                            <table class="table table-bordered">
                                <tr>
                                    <th style="width: 200px;">ID</th>
                                    <td>{{ $user->id }}</td>
                                </tr>
                                <tr>
                                    <th>Họ và tên</th>
                                    <td>{{ $user->name }}</td>
                                </tr>
                                <tr>
                                    <th>Email</th>
                                    <td>
                                        {{ $user->email }}
                                        @if($user->email_verified_at)
                                            <span class="badge bg-success">Đã xác thực</span>
                                        @else
                                            <span class="badge bg-warning">Chưa xác thực</span>
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <th>Số điện thoại</th>
                                    <td>{{ $user->phone ?? 'Chưa cập nhật' }}</td>
                                </tr>
                                <tr>
                                    <th>Giới tính</th>
                                    <td>
                                        @if($user->gender == 'male')
                                            Nam
                                        @elseif($user->gender == 'female')
                                            Nữ
                                        @else
                                            Chưa cập nhật
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <th>Ngày sinh</th>
                                    <td>{{ $user->birthday ? $user->birthday->format('d/m/Y H:i') : 'Chưa cập nhật' }}</td>
                                </tr>
                                <tr>
                                    <th>Trạng thái</th>
                                    <td>
                                        @if($user->is_active)
                                            <span class="badge bg-success">Đang hoạt động</span>
                                        @else
                                            <span class="badge bg-danger">Đã khóa</span>
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <th>Ngày tạo</th>
                                    <td>{{ $user->created_at->format('d/m/Y H:i:s') }}</td>
                                </tr>
                                <tr>
                                    <th>Cập nhật lần cuối</th>
                                    <td>{{ $user->updated_at->format('d/m/Y H:i:s') }}</td>
                                </tr>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Danh sách đơn hàng -->
            <div class="card mt-4">
                <div class="card-header">
                    <h3 class="card-title">Lịch sử đơn hàng</h3>
                </div>
                <div class="card-body">
                    @if($user->orders->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-bordered table-hover">
                                <thead>
                                    <tr>
                                        <th>Mã đơn hàng</th>
                                        <th>Ngày đặt</th>
                                        <th>Khách hàng</th>
                                        <th>Tổng tiền hàng</th>
                                        <th>Phương thức TT</th>
                                        <th>Trạng thái TT</th>
                                        <th>Trạng thái đơn</th>
                                        <th>Thao tác</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($user->orders as $order)
                                        <tr>
                                            <td>#{{ $order->order_code }}</td>
                                            <td>{{ $order->ordered_at ? $order->ordered_at->format('d/m/Y H:i') : $order->created_at->format('d/m/Y H:i:s') }}</td>
                                            <td>
                                                <strong>{{ $order->customer_name }}</strong><br>
                                                {{ $order->customer_phone }}<br>
                                                {{ $order->customer_address }}
                                            </td>
                                            <td>{{ number_format($order->total_amount) }} VNĐ</td>
                                            <td>
                                                @if($order->payment_method == 'cod')
                                                    <span class="badge bg-info">Thanh toán khi nhận hàng</span>
                                                @else
                                                    <span class="badge bg-primary">Chuyển khoản</span>
                                                @endif
                                            </td>
                                            <td>
                                                @if($order->payment_status == 'paid')
                                                    <span class="badge bg-success">Đã thanh toán</span>
                                                    @if($order->paid_at)
                                                        <br><small>{{ $order->paid_at->format('d/m/Y H:i') }}</small>
                                                    @endif
                                                @else
                                                    <span class="badge bg-warning">Chưa thanh toán</span>
                                                @endif
                                            </td>
                                            <td>
                                                @switch($order->status)
                                                    @case('pending')
                                                        <span class="badge bg-warning">Chờ xác nhận</span>
                                                        @break
                                                    @case('processing')
                                                        <span class="badge bg-info">Đang xử lý</span>
                                                        @break
                                                    @case('delivering')
                                                        <span class="badge bg-primary">Đang giao hàng</span>
                                                        @break
                                                    @case('completed')
                                                        <span class="badge bg-success">Đã giao thành công</span>
                                                        @break
                                                    @case('finished')
                                                        <span class="badge bg-success">Hoàn thành</span>
                                                        @break
                                                    @case('cancelled')
                                                        <span class="badge bg-danger">Đã hủy</span>
                                                        @break
                                                    @case('failed')
                                                        <span class="badge bg-danger">Giao hàng thất bại</span>
                                                        @break
                                                @endswitch
                                            </td>
                                            <td>
                                                <a href="{{ route('admin.orders.show', $order->id) }}" class="btn btn-sm btn-info">
                                                    <i class="fas fa-eye"></i> Chi tiết
                                                </a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="alert alert-info">
                            Người dùng chưa có đơn hàng nào.
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection