@extends('admin.layout.AdminLayout')
@section('title', 'Chi tiết Banner')

@section('content')
<h3>Chi tiết Banner</h3>

<div class="card p-3">
    <div class="mb-3">
        <strong>Tiêu đề:</strong> {{ $banner->title ?? 'Không có' }}
    </div>

    <div class="mb-3">
        <strong>Ảnh:</strong><br>
        @if($banner->image_path)
            <img src="{{ asset('storage/' . $banner->image_path) }}" height="200">
        @else
            Không có ảnh
        @endif
    </div>

    <div class="mb-3">
        <strong>Link URL:</strong> 
        <a href="{{ $banner->link_url }}" target="_blank">{{ $banner->link_url }}</a>
    </div>

    <div class="mb-3">
        <strong>Vị trí:</strong> {{ $banner->position ?? 'Không có' }}
    </div>

    <a href="{{ route('banners.index') }}" class="btn btn-secondary">Quay lại</a>
    <a href="{{ route('banners.edit', $banner->id) }}" class="btn btn-warning">Chỉnh sửa</a>
</div>
@endsection
