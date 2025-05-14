@extends('admin.layout.AdminLayout')
@section('title', 'Quản lý mã khuyến mãi')

@section('content')
<h3>Quản lý mã khuyến mãi</h3>

@if(session('success')) <div class="alert alert-success">{{ session('success') }}</div> @endif
@if(session('error')) <div class="alert alert-danger">{{ session('error') }}</div> @endif

<form method="POST" action="{{ isset($promotion) ? route('promotions.update', $promotion->id) : route('promotions.store') }}">
    @csrf
    @if(isset($promotion)) @method('PUT') @endif
    <div class="row g-2 mb-3">
        <div class="col-md-2"><input name="code" class="form-control" placeholder="Mã" value="{{ $promotion->code ?? '' }}" required></div>
        <div class="col-md-2"><select name="discount_type" class="form-select">
            <option value="percent" {{ (isset($promotion) && $promotion->discount_type == 'percent') ? 'selected' : '' }}>%</option>
            <option value="fixed" {{ (isset($promotion) && $promotion->discount_type == 'fixed') ? 'selected' : '' }}>VNĐ</option>
        </select></div>
        <div class="col-md-2"><input name="discount_value" type="number" step="0.01" class="form-control" placeholder="Giá trị" value="{{ $promotion->discount_value ?? '' }}" required></div>
        <div class="col-md-2"><input name="min_order_amount" type="number" step="0.01" class="form-control" placeholder="Tối thiểu" value="{{ $promotion->min_order_amount ?? '' }}"></div>
        <div class="col-md-2"><input name="max_usage" type="number" class="form-control" placeholder="Lượt dùng" value="{{ $promotion->max_usage ?? '' }}"></div>
        <div class="col-md-2">
            <button class="btn btn-{{ isset($promotion) ? 'warning' : 'primary' }} w-100">
                {{ isset($promotion) ? 'Cập nhật' : 'Thêm mã' }}
            </button>
        </div>
    </div>
</form>

<table class="table table-bordered">
    <thead>
        <tr>
            <th>Mã</th><th>Loại</th><th>Giá trị</th><th>Tối thiểu</th><th>Lượt dùng</th><th>Đã dùng</th><th>Trạng thái</th><th>Hành động</th>
        </tr>
    </thead>
    <tbody>
        @foreach($promotions as $promo)
        <tr>
            <td>{{ $promo->code }}</td>
            <td>{{ $promo->discount_type }}</td>
            <td>{{ $promo->discount_value }}</td>
            <td>{{ $promo->min_order_amount }}</td>
            <td>{{ $promo->max_usage }}</td>
            <td>{{ $promo->usage_count }}</td>
            <td>{{ $promo->is_active ? '✔️' : '❌' }}</td>
            <td class="d-flex gap-1">
                <a href="{{ route('promotions.edit', $promo->id) }}" class="btn btn-sm btn-warning">Sửa</a>
                <form method="POST" action="{{ route('promotions.destroy', $promo->id) }}" onsubmit="return confirm('Xoá?')">
                    @csrf @method('DELETE')
                    <button class="btn btn-sm btn-danger">Xoá</button>
                </form>
            </td>
        </tr>
        @endforeach
    </tbody>
</table>
@endsection
