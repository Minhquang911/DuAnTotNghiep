@extends('admin.layout.AdminLayout')
@section('title', 'Thêm mã khuyến mãi')

@section('content')
<h3>Thêm mã khuyến mãi</h3>

@if(session('success')) <div class="alert alert-success">{{ session('success') }}</div> @endif
@if(session('error')) <div class="alert alert-danger">{{ session('error') }}</div> @endif

<form method="POST" action="{{ route('promotions.store') }}">
    @csrf
    <div class="row g-2 mb-3">
        <div class="col-md-2"><input name="code" class="form-control" placeholder="Mã" required></div>
        <div class="col-md-2">
            <select name="discount_type" class="form-select">
                <option value="percent">%</option>
                <option value="fixed">VNĐ</option>
            </select>
        </div>
        <div class="col-md-2"><input name="discount_value" type="number" step="0.01" class="form-control" placeholder="Giá trị" required></div>
        <div class="col-md-2"><input name="min_order_amount" type="number" step="0.01" class="form-control" placeholder="Tối thiểu"></div>
        <div class="col-md-2"><input name="max_usage" type="number" class="form-control" placeholder="Lượt dùng"></div>
        <div class="col-md-2"><input name="start_date" type="date" class="form-control" placeholder="Bắt đầu"></div>
        <div class="col-md-2"><input name="end_date" type="date" class="form-control" placeholder="Kết thúc"></div>
        <div class="col-md-2">
            <button class="btn btn-primary w-100">Thêm mã</button>
        </div>
    </div>
</form>
@endsection
