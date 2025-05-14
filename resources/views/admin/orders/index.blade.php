@extends('admin.layout.AdminLayout')
@section('content')
    <div class="container">
        <h2>Quản lý đơn hàng</h2>

        <form action="{{ route('admin.orders.search') }}" method="GET">
            <input type="text" name="query" placeholder="Tìm kiếm..." value="{{ request('query') }}">
            <button type="submit">Tìm kiếm</button>
        </form>


        <table class="table">
            <thead>
            <tr>
                <th>ID</th>
                <th>Tên KH</th>
                <th>Email</th>
                <th>Số điện thoại</th>
                <th>Tổng tiền</th>
                <th>Trạng thái</th>
                <th>Ngày tạo</th>
                <th>Hành động</th>
            </tr>
            </thead>
            <tbody>
            @foreach($orders as $order)
                <tr>
                    <td>{{ $order->id }}</td>
                    <td>{{ $order->user_name }}</td>
                    <td>{{ $order->user_email }}</td>
                    <td>{{ $order->user_phone }}</td>
                    <td>{{ $order->final_amount }}</td>
                    <td>{{ $order->status_id }}</td>
                    <td>{{ $order->created_at }}</td>
                    <td>
                        <a href="{{ route('admin.orders.show', $order) }}">Xem</a> |
                        <a href="{{ route('admin.orders.edit', $order) }}">Sửa</a>
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>

        {{ $orders->links() }}
    </div>
@endsection
