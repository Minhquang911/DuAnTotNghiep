@extends('admin.layout.AdminLayout')

@section('content')
    <div class="container">
        <h4>Cập nhật trạng thái</h4>

        <form action="{{ route('admin.order-statuses.update', $orderStatus) }}" method="POST">
            @csrf @method('PUT')

            <div class="mb-3">
                <label>Tên trạng thái</label>
                <input type="text" name="status_name" class="form-control" value="{{ $orderStatus->status_name }}"
                       required>
            </div>

            <div class="mb-3">
                <label>Thứ tự hiển thị</label>
                <input type="number" name="display_order" class="form-control" value="{{ $orderStatus->display_order }}"
                       required>
            </div>

            <button class="btn btn-success">Cập nhật</button>
            <a href="{{ route('admin.order-statuses.index') }}" class="btn btn-secondary">Quay lại</a>
        </form>
    </div>
@endsection
