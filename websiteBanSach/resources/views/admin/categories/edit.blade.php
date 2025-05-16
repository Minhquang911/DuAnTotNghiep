@extends('admin.layout.AdminLayout')
@section('title', 'Sửa danh mục')

@section('content')
<div class="container">
    <h3 class="mb-4">Sửa danh mục</h3>

    @if ($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">@foreach ($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul>
        </div>
    @endif

    <form action="{{ route('categories.update', $category->id) }}" method="POST">
        @csrf
        @method('PUT')
    
        <div class="mb-3">
            <label for="name" class="form-label">Tên danh mục</label>
            <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name', $category->name) }}">
            @error('name')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
    
        <button type="submit" class="btn btn-warning">Cập nhật</button>
        <a href="{{ route('categories.index') }}" class="btn btn-secondary">Quay lại</a>
    </form>
</div>
@endsection