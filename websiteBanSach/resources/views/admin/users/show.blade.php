@extends('admin.layout.AdminLayout')

@section('content')
<div class="container mt-4">
    <h2>Chi tiết người dùng</h2>

    <table class="table table-bordered">
        <tr>
            <th>ID</th>
            <td>{{ $user->id }}</td>
        </tr>
        <tr>
            <th>Tên</th>
            <td>{{ $user->name }}</td>
        </tr>
        <tr>
            <th>Email</th>
            <td>{{ $user->email }}</td>
        </tr>
        <tr>
            <th>Số điện thoại</th>
            <td>{{ $user->phone ?? '(chưa có)' }}</td>
        </tr>
        <tr>
            <th>Địa chỉ</th>
            <td>{{ $user->address ?? '(chưa có)' }}</td>
        </tr>
        <tr>
            <th>Vai trò</th>
            <td>
                @switch($user->role)
                    @case('admin') Admin @break
                    @case('customer') Customer @break
                    @default {{ $user->role }}
                @endswitch
            </td>
        </tr>
        <tr>
            <th>Ngày tạo</th>
            <td>{{ $user->created_at }}</td>
        </tr>
        <tr>
            <th>Cập nhật lần cuối</th>
            <td>{{ $user->updated_at }}</td>
        </tr>
    </table>

    <a href="{{ route('users.edit', $user->id) }}" class="btn btn-primary">Chỉnh sửa</a>
    <a href="{{ route('users.index') }}" class="btn btn-secondary">Quay lại danh sách</a>
</div>
@endsection
