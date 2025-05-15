{{-- resources/views/admin/promotions/edit.blade.php --}}
@extends('admin.layout.AdminLayout')
@section('title', 'Chỉnh sửa mã khuyến mãi')

@section('content')
<h3>Chỉnh sửa mã khuyến mãi</h3>

@if(session('success')) <div class="alert alert-success">{{ session('success') }}</div> @endif
@if(session('error')) <div class="alert alert-danger">{{ session('error') }}</div> @endif

<form method="POST" action="{{ route('promotions.update', $promotion->id) }}">
    @csrf
    @method('PUT')
    <div class="row g-2 mb-3">
        <div class="col-md-2"><input name="code" class="form-control" placeholder="Mã" value="{{ $promotion->code }}" required></div>
        <div class="col-md-2">
            <select name="discount_type" class="form-select">
                <option value="percent" {{ $promotion->discount_type == 'percent' ? 'selected' : '' }}>%</option>
                <option value="fixed" {{ $promotion->discount_type == 'fixed' ? 'selected' : '' }}>VNĐ</option>
            </select>
        </div>
        <div class="col-md-2"><input name="discount_value" type="number" step="0.01" class="form-control" placeholder="Giá trị" value="{{ $promotion->discount_value }}" required></div>
        <div class="col-md-2"><input name="min_order_amount" type="number" step="0.01" class="form-control" placeholder="Tối thiểu" value="{{ $promotion->min_order_amount }}"></div>
        <div class="col-md-2"><input name="max_usage" type="number" class="form-control" placeholder="Lượt dùng" value="{{ $promotion->max_usage }}"></div>
        <div class="col-md-2">
            <button class="btn btn-warning w-100">Cập nhật</button>
        </div>
    </div>
</form>
@endsection
