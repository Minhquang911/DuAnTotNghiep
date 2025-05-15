@extends('admin.layout.AdminLayout')

@section('content')
    <div class="container">
        <h4>Thêm trạng thái đơn hàng</h4>

        <form action="{{ route('admin.order-statuses.store') }}" method="POST">
            @csrf
            <div class="mb-3">
                <label>Tên trạng thái</label>
                <input type="text" name="status_name" class="form-control" required>
            </div>

            <div class="mb-3">
                <label>Thứ tự hiển thị</label>
                <input type="number" name="display_order" class="form-control" required>
            </div>

            <button class="btn btn-primary">Lưu</button>
            <a href="{{ route('admin.order-statuses.index') }}" class="btn btn-secondary">Quay lại</a>
        </form>
    </div>
@endsection
