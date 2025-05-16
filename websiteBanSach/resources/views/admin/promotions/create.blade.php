@extends('admin.layout.AdminLayout')
@section('title', 'Thêm mã khuyến mãi')

@section('content')
<h3 class="mb-4">Thêm mã khuyến mãi</h3>

@if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
@endif
@if(session('error'))
    <div class="alert alert-danger">{{ session('error') }}</div>
@endif

@if ($errors->any())
    <div class="alert alert-danger">
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<form method="POST" action="{{ route('promotions.store') }}">
    @csrf
    <div class="row g-3">
        <div class="col-md-4">
            <label class="form-label">Mã khuyến mãi *</label>
            <input name="code" class="form-control" placeholder="Nhập mã" required value="{{ old('code') }}">
        </div>

        <div class="col-md-4">
            <label class="form-label">Loại giảm *</label>
            <select name="discount_type" class="form-select" required>
                <option value="percent" {{ old('discount_type') == 'percent' ? 'selected' : '' }}>Phần trăm (%)</option>
                <option value="fixed" {{ old('discount_type') == 'fixed' ? 'selected' : '' }}>VNĐ cố định</option>
            </select>
        </div>

        <div class="col-md-4">
            <label class="form-label">Giá trị giảm *</label>
            <input name="discount_value" type="number" step="0.01" class="form-control" placeholder="Ví dụ: 10 hoặc 50000" required value="{{ old('discount_value') }}">
        </div>

        <div class="col-md-4">
            <label class="form-label">Giảm tối đa (áp dụng cho %)</label>
            <input name="max_discount_amount" type="number" step="0.01" class="form-control" placeholder="Ví dụ: 100000" value="{{ old('max_discount_amount') }}">
        </div>

        <div class="col-md-4">
            <label class="form-label">Giá trị đơn tối thiểu</label>
            <input name="min_order_amount" type="number" step="0.01" class="form-control" placeholder="Ví dụ: 200000" value="{{ old('min_order_amount') }}">
        </div>

        <div class="col-md-4">
            <label class="form-label">Số lượt sử dụng tối đa</label>
            <input name="max_usage" type="number" class="form-control" placeholder="Để trống nếu không giới hạn" value="{{ old('max_usage') }}">
        </div>

        <div class="col-md-6">
            <label class="form-label">Ngày bắt đầu</label>
            <input name="start_date" type="date" class="form-control" value="{{ old('start_date') }}">
        </div>

        <div class="col-md-6">
            <label class="form-label">Ngày kết thúc</label>
            <input name="end_date" type="date" class="form-control" value="{{ old('end_date') }}">
        </div>

        <div class="col-md-12">
            <label class="form-label">Mô tả</label>
            <textarea name="description" class="form-control" placeholder="Chi tiết về mã khuyến mãi">{{ old('description') }}</textarea>
        </div>

        <div class="col-md-12 text-end">
            <button class="btn btn-primary px-4">Thêm mã khuyến mãi</button>
        </div>
    </div>
</form>
@endsection
