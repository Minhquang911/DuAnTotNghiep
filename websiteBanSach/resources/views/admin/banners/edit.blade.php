@extends('admin.layout.AdminLayout')
@section('title', 'Chỉnh sửa banner')

@section('content')
<h3>Chỉnh sửa banner</h3>

@if(session('success')) <div class="alert alert-success">{{ session('success') }}</div> @endif
@if(session('error')) <div class="alert alert-danger">{{ session('error') }}</div> @endif

<form method="POST" action="{{ route('banners.update', $banner->id) }}" enctype="multipart/form-data">
    @csrf
    @method('PUT')
    <div class="row g-2 mb-3 align-items-center">
        <div class="col-md-2">
            <input name="title" class="form-control" placeholder="Tiêu đề" value="{{ $banner->title }}">
        </div>
        <div class="col-md-3">
            @if($banner->image_path)
                <img src="{{ asset('storage/' . $banner->image_path) }}" width="100" class="mb-1">
            @endif
            <input type="file" name="image_path" class="form-control">
        </div>
        <div class="col-md-2">
            <input name="link_url" type="url" class="form-control" placeholder="URL" value="{{ $banner->link_url }}">
        </div>
        <div class="col-md-2">
            <input name="position" class="form-control" placeholder="Vị trí" value="{{ $banner->position }}">
        </div>
        <div class="col-md-1 text-center">
            <input type="checkbox" name="is_active" value="1" {{ $banner->is_active ? 'checked' : '' }}>
            <small>Hiển thị</small>
        </div>
        <div class="col-md-2">
            <button class="btn btn-warning w-100">Cập nhật banner</button>
        </div>
    </div>
</form>

<a href="{{ route('banners.index') }}" class="btn btn-secondary">← Quay lại danh sách</a>
@endsection

