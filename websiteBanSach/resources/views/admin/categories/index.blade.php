@extends('admin.layout.AdminLayout')
@section('title', 'Quản lý danh mục')

@section('content')
<h3>Quản lý danh mục</h3>

@if(session('success')) <div class="alert alert-success">{{ session('success') }}</div> @endif
@if(session('error')) <div class="alert alert-danger">{{ session('error') }}</div> @endif

<form method="GET" class="row g-2 mb-3">
    <div class="col-md-3">
        <input type="text" name="name" value="{{ request('name') }}" class="form-control" placeholder="Tìm theo tên...">
    </div>
    <div class="col-md-3">
        <button class="btn btn-primary w-100">Tìm</button>
    </div>
    <div class="col-md-3 text-end">
        <a href="{{ route('categories.create') }}" class="btn btn-success w-100">Thêm danh mục</a>
    </div>
</form>

<table class="table table-bordered">
    <thead>
        <tr>
            <th>ID</th>
            <th>Tên danh mục</th>
            <th>Hành động</th>
        </tr>
    </thead>
    <tbody>
        @forelse($categories as $category)
        <tr>
            <td>{{ $category->category_id }}</td>
            <td>{{ $category->name }}</td>
            <td class="d-flex gap-1">
                <a href="{{ route('categories.edit', $category->category_id) }}" class="btn btn-warning btn-sm">Sửa</a>
                <form action="{{ route('categories.destroy', $category->category_id) }}" method="POST" class="d-inline-block" onsubmit="return confirm('Xác nhận xoá?')">
                    @csrf @method('DELETE')
                    <button class="btn btn-danger btn-sm">Xoá</button>
                </form>
            </td>
        </tr>
        @empty
        <tr><td colspan="3">Không có dữ liệu</td></tr>
        @endforelse
    </tbody>
</table>

{{ $categories->withQueryString()->links() }}
@endsection