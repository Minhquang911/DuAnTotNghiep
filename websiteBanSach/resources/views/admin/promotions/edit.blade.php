@extends('admin.layout.AdminLayout')
@section('title', 'Chỉnh sửa mã khuyến mãi')

@section('content')
<h3 class="mb-4">Chỉnh sửa mã khuyến mãi</h3>

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

<form method="POST" action="{{ route('promotions.update', $promotion->id) }}">
    @csrf
    @method('PUT')
    <div class="row g-3">
        <div class="col-md-4">
            <label class="form-label">Mã khuyến mãi *</label>
            <input name="code" class="form-control" value="{{ old('code', $promotion->code) }}" required>
        </div>

        <div class="col-md-4">
            <label class="form-label">Loại giảm *</label>
            <select name="discount_type" class="form-select" required>
                <option value="percent" {{ old('discount_type', $promotion->discount_type) == 'percent' ? 'selected' : '' }}>Phần trăm (%)</option>
                <option value="fixed" {{ old('discount_type', $promotion->discount_type) == 'fixed' ? 'selected' : '' }}>VNĐ cố định</option>
            </select>
        </div>

        <div class="col-md-4">
            <label class="form-label">Giá trị giảm *</label>
            <input name="discount_value" type="number" step="0.01" class="form-control" value="{{ old('discount_value', $promotion->discount_value) }}" required>
        </div>

        <div class="col-md-4">
            <label class="form-label">Giảm tối đa (cho %)</label>
            <input name="max_discount_amount" type="number" step="0.01" class="form-control" value="{{ old('max_discount_amount', $promotion->max_discount_amount) }}">
        </div>

        <div class="col-md-4">
            <label class="form-label">Đơn tối thiểu</label>
            <input name="min_order_amount" type="number" step="0.01" class="form-control" value="{{ old('min_order_amount', $promotion->min_order_amount) }}">
        </div>

        <div class="col-md-4">
            <label class="form-label">Số lượt sử dụng</label>
            <input name="max_usage" type="number" class="form-control" value="{{ old('max_usage', $promotion->max_usage) }}">
        </div>

        <div class="col-md-6">
            <label class="form-label">Ngày bắt đầu</label>
            <input name="start_date" type="date" class="form-control" value="{{ old('start_date', $promotion->start_date) }}">
        </div>

        <div class="col-md-6">
            <label class="form-label">Ngày kết thúc</label>
            <input name="end_date" type="date" class="form-control" value="{{ old('end_date', $promotion->end_date) }}">
        </div>

        <div class="col-md-12">
            <label class="form-label">Mô tả</label>
            <textarea name="description" class="form-control">{{ old('description', $promotion->description) }}</textarea>
        </div>

        <div class="col-md-4">
            <label class="form-label">Trạng thái</label>
            <select name="is_active" class="form-select">
                <option value="1" {{ old('is_active', $promotion->is_active) ? 'selected' : '' }}>Kích hoạt</option>
                <option value="0" {{ !old('is_active', $promotion->is_active) ? 'selected' : '' }}>Vô hiệu hoá</option>
            </select>
        </div>

        <div class="col-md-12 text-end">
            <button class="btn btn-warning px-4">Cập nhật mã</button>
        </div>
    </div>
</form>
@endsection
