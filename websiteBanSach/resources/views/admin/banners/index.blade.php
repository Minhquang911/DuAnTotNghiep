@extends('admin.layout.AdminLayout')
@section('title', 'Quản lý banner')

@section('content')
<h3>Quản lý banner</h3>

@if(session('success')) <div class="alert alert-success">{{ session('success') }}</div> @endif
@if(session('error')) <div class="alert alert-danger">{{ session('error') }}</div> @endif

<form method="POST" action="{{ isset($banner) ? route('banners.update', $banner->id) : route('banners.store') }}" enctype="multipart/form-data">
    @csrf
    @if(isset($banner)) @method('PUT') @endif
    <div class="row g-2 mb-3 align-items-center">
        <div class="col-md-2">
            <input name="title" class="form-control" placeholder="Tiêu đề" value="{{ $banner->title ?? '' }}">
        </div>
        <div class="col-md-3">
            <input type="file" name="image_path" class="form-control" {{ isset($banner) ? '' : 'required' }}>
        </div>
        <div class="col-md-2">
            <input name="link_url" type="url" class="form-control" placeholder="URL" value="{{ $banner->link_url ?? '' }}">
        </div>
        <div class="col-md-2">
            <input name="position" class="form-control" placeholder="Vị trí" value="{{ $banner->position ?? '' }}">
        </div>
        <div class="col-md-1 text-center">
            <input type="checkbox" name="is_active" value="1" {{ isset($banner) && $banner->is_active ? 'checked' : (!isset($banner) ? 'checked' : '') }}>
            <small>Hiển thị</small>
        </div>
        <div class="col-md-2">
            <button class="btn btn-{{ isset($banner) ? 'warning' : 'primary' }} w-100">
                {{ isset($banner) ? 'Cập nhật' : 'Thêm banner' }}
            </button>
        </div>
    </div>
</form>

<table class="table table-bordered">
    <thead>
        <tr>
            <th>Ảnh</th>
            <th>Tiêu đề</th>
            <th>URL</th>
            <th>Vị trí</th>
            <th>Hiển thị</th>
            <th>Hành động</th>
        </tr>
    </thead>
    <tbody>
        @foreach($banners as $item)
        <tr>
            <td>
                @if($item->image_path)
                    <img src="{{ asset('storage/' . $item->image_path) }}" width="100">
                @endif
            </td>
            <td>{{ $item->title }}</td>
            <td><a href="{{ $item->link_url }}" target="_blank">{{ $item->link_url }}</a></td>
            <td>{{ $item->position }}</td>
            <td>{{ $item->is_active ? '✔️' : '❌' }}</td>
            <td class="d-flex gap-1">
                <a href="{{ route('banners.edit', $item->id) }}" class="btn btn-sm btn-warning">Sửa</a>
                <a href="{{ route('banners.show', $item->id) }}" class="btn btn-sm btn-info">Xem</a>
                <form method="POST" action="{{ route('banners.destroy', $item->id) }}" onsubmit="return confirm('Xoá banner này?')">
                    @csrf @method('DELETE')
                    <button class="btn btn-sm btn-danger">Xoá</button>
                </form>
            </td>
        </tr>
        @endforeach
    </tbody>
</table>
@endsection
