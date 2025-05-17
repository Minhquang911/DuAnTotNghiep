@extends('admin.layout.AdminLayout')

@section('content')
    <div class="container mt-4">
        <h2>Danh sách trạng thái đơn hàng</h2>
        <a href="{{ route('order_statuses.create') }}" class="btn btn-success mb-3">+ Thêm mới</a>

        @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        <table class="table table-bordered table-hover">
            <thead>
            <tr>
                <th>ID</th>
                <th>Tên trạng thái</th>
                <th>Thứ tự hiển thị</th>
                <th>Ngày tạo</th>
                <th>Hành động</th>
            </tr>
            </thead>
            <tbody>
            @foreach ($statuses as $status)
                <tr>
                    <td>{{ $status->id }}</td>
                    <td>{{ $status->status_name }}</td>
                    <td>{{ $status->display_order }}</td>
                    <td>{{ $status->created_at }}</td>
                    <td>
                        <a href="{{ route('order_statuses.edit', $status->id) }}" class="btn btn-warning btn-sm">Sửa</a>
                        <form action="{{ route('order_statuses.destroy', $status->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Bạn có chắc muốn xoá?')">
                            @csrf
                            @method('DELETE')
                            <button class="btn btn-danger btn-sm">Xoá</button>
                        </form>
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>
@endsection
