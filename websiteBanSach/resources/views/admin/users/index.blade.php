@extends('admin.layout.AdminLayout')

@section('content')
    <div class="container mt-4">
        <h2>Danh sách người dùng</h2>
        <a href="{{ route('users.create') }}" class="btn btn-success mb-3">Thêm người dùng</a>

        @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Tên</th>
                    <th>Email</th>
                    <th>Vai trò</th>
                    <th>Ngày tạo</th>
                    <th>Hành động</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($users as $user)
                    <tr>
                        <td>{{ $user->id }}</td>
                        <td>{{ $user->name }}</td>
                        <td>{{ $user->email }}</td>
                        <td>{{ $user->role }}</td>
                        <td>{{ $user->created_at }}</td>
                        <td>
                            <a href="{{ route('users.show', $user->id) }}" class="btn btn-primary btn-sm">Xem</a>
                            <a href="{{ route('users.edit', $user->id) }}" class="btn btn-sm btn-warning">Sửa</a>
                            <form action="{{ route('users.destroy', $user->id) }}" method="POST" class="d-inline"
                                onsubmit="return confirm('Bạn có chắc chắn muốn xóa?')">
                                @csrf
                                @method('DELETE')
                                <button class="btn btn-danger btn-sm">Xóa</button>
                            </form>
                            <form action="{{ route('users.toggleBlock', $user->id) }}" method="POST"
                                style="display:inline">
                                @csrf
                                @method('PATCH')
                                <button class="btn btn-warning btn-sm">
                                    {{ $user->is_blocked ? 'Mở khóa' : 'Khóa' }}
                                </button>
                            </form>
                            <form action="{{ route('users.resetPassword', $user->id) }}" method="POST"
                                style="display:inline">
                                @csrf
                                @method('PATCH')
                                <button class="btn btn-danger btn-sm">Reset mật khẩu</button>
                            </form>


                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        {{ $users->links() }}
    </div>
@endsection
