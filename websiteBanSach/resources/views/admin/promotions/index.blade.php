{{-- resources/views/admin/promotions/index.blade.php --}}
@extends('admin.layout.AdminLayout')
@section('title', 'Quản lý mã khuyến mãi')

@section('content')
<h3>Danh sách mã khuyến mãi</h3>

<a href="{{ route('promotions.create') }}" class="btn btn-success mb-3">+ Thêm mới</a>

@if(session('success')) <div class="alert alert-success">{{ session('success') }}</div> @endif
@if(session('error')) <div class="alert alert-danger">{{ session('error') }}</div> @endif

<table class="table table-bordered">
    <thead>
        <tr>
            <th>Mã</th><th>Loại</th><th>Giá trị</th><th>Tối thiểu</th><th>Lượt dùng</th><th>Đã dùng</th><th>Thời hạn</th><th>Trạng thái</th><th>Hành động</th>
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
        <td>
            @if($promo->start_date || $promo->end_date)
                {{ $promo->start_date ? \Carbon\Carbon::parse($promo->start_date)->format('d/m/Y') : '---' }}
                —
                {{ $promo->end_date ? \Carbon\Carbon::parse($promo->end_date)->format('d/m/Y') : '---' }}
            @else
                ---
            @endif
        </td>
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
