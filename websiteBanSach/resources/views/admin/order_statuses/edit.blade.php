
@extends('admin.layout.AdminLayout')

@section('content')
    <div class="container mt-4">
        <h2>Cập nhật trạng thái đơn hàng</h2>
        <form action="{{ route('order_statuses.update', $status->id) }}" method="POST">
            @csrf
            @method('PUT')
            <div class="mb-3">
                <label for="status_name" class="form-label">Tên trạng thái</label>
                <input type="text" name="status_name" class="form-control" value="{{ $status->status_name }}" required>
            </div>
            <div class="mb-3">
                <label for="display_order" class="form-label">Thứ tự hiển thị</label>
                <input type="number" name="display_order" class="form-control" value="{{ $status->display_order }}" required>
            </div>
            <button type="submit" class="btn btn-primary">Cập nhật</button>
            <a href="{{ route('order_statuses.index') }}" class="btn btn-secondary">Quay lại</a>
        </form>
    </div>
@endsection
