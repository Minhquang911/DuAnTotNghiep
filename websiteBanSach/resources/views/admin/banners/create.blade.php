@extends('admin.layout.AdminLayout')
@section('title', 'Thêm banner')

@section('content')
<h3>Thêm banner mới</h3>

<form method="POST" action="{{ route('banners.store') }}" enctype="multipart/form-data">
    @csrf
    <div class="row g-2 mb-3 align-items-center">
        <div class="col-md-2">
            <input name="title" class="form-control" placeholder="Tiêu đề">
        </div>
        <div class="col-md-3">
            <input type="file" name="image_path" class="form-control" required>
        </div>
        <div class="col-md-2">
            <input name="link_url" type="url" class="form-control" placeholder="URL">
        </div>
        <div class="col-md-2">
            <input name="position" class="form-control" placeholder="Vị trí">
        </div>
        <div class="col-md-1 text-center">
            <input type="checkbox" name="is_active" value="1" checked>
            <small>Hiển thị</small>
        </div>
        <div class="col-md-2">
            <button class="btn btn-primary w-100">Thêm banner</button>
        </div>
    </div>
</form>

<a href="{{ route('banners.index') }}" class="btn btn-secondary">← Quay lại danh sách</a>
@endsection
