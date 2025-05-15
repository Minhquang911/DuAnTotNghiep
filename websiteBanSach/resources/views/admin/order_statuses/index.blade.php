@extends('admin.layout.AdminLayout')

@section('content')
    <div class="container">
        <h4>Danh sách trạng thái đơn hàng</h4>

        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        <a href="{{ route('admin.order-statuses.create') }}" class="btn btn-success mb-2">Thêm mới</a>

        <table class="table table-bordered">
            <thead>
            <tr>
                <th>ID</th>
                <th>Tên trạng thái</th>
                <th>Thứ tự hiển thị</th>
                <th>Hành động</th>
            </tr>
            </thead>
            <tbody>
            @foreach($statuses as $status)
                <tr>
                    <td>{{ $status->id }}</td>
                    <td>{{ $status->status_name }}</td>
                    <td>{{ $status->display_order }}</td>
                    <td>
                        <a href="{{ route('admin.order-statuses.edit', $status) }}" class="btn btn-warning btn-sm">Sửa</a>
                        <form action="{{ route('admin.order-statuses.destroy', $status) }}" method="POST" style="display:inline-block;">
                            @csrf @method('DELETE')
                            <button class="btn btn-danger btn-sm" onclick="return confirm('Xoá trạng thái này?')">Xoá</button>
                        </form>
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>
@endsection
