@extends('admin.layout.AdminLayout')
@section('title', 'Quản lý mã khuyến mãi')

@section('content')
<h3 class="mb-4">Danh sách mã khuyến mãi</h3>

<a href="{{ route('promotions.create') }}" class="btn btn-success mb-3">+ Thêm mới</a>

@if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
@endif
@if(session('error'))
    <div class="alert alert-danger">{{ session('error') }}</div>
@endif

<div class="table-responsive">
<table class="table table-bordered align-middle text-center">
    <thead class="table-light">
        <tr>
            <th>Mã</th>
            <th>Loại</th>
            <th>Giá trị</th>
            <th>Giảm tối đa</th>
            <th>Tối thiểu</th>
            <th>Lượt dùng</th>
            <th>Đã dùng</th>
            <th>Thời hạn</th>
            <th>Mô tả</th>
            <th>Trạng thái</th>
            <th>Hành động</th>
        </tr>
    </thead>
    <tbody>
    @forelse($promotions as $promo)
        <tr>
            <td><strong>{{ $promo->code }}</strong></td>
            <td>{{ $promo->discount_type == 'percent' ? 'Phần trăm' : 'VNĐ' }}</td>
            <td>
                {{ $promo->discount_type == 'percent' ? $promo->discount_value . '%' : number_format($promo->discount_value) . '₫' }}
            </td>
            <td>
                {{ $promo->discount_type == 'percent' && $promo->max_discount_amount
                    ? number_format($promo->max_discount_amount) . '₫'
                    : ($promo->discount_type == 'fixed' ? '—' : '∞') }}
            </td>
            <td>{{ $promo->min_order_amount ? number_format($promo->min_order_amount) . '₫' : '---' }}</td>
            <td>{{ $promo->max_usage ?? '∞' }}</td>
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
            <td class="text-start">{{ $promo->description ?? '---' }}</td>
             @php
                $now = \Carbon\Carbon::now();

                $isManualOff = !$promo->is_active;
                $isExpired = ($promo->end_date && $now->gt($promo->end_date));
                $isNotStarted = ($promo->start_date && $now->lt($promo->start_date));
                $isUsedOut = ($promo->max_usage && $promo->usage_count >= $promo->max_usage);

                $isTrulyActive = !$isManualOff && !$isExpired && !$isNotStarted && !$isUsedOut;
            @endphp
            <td>
                @if($isManualOff)
                    <span class="badge bg-secondary">Không hoạt động</span>
                @elseif(!$isTrulyActive)
                    <span class="badge bg-dark">Tự tắt</span>
                @else
                    <span class="badge bg-success">Hoạt động</span>
                @endif
            </td>
            <td class="d-flex gap-1">
                <a href="{{ route('promotions.edit', $promo->id) }}" class="btn btn-sm btn-warning">Sửa</a>

                @if($isManualOff)
                    <form method="POST" action="{{ route('promotions.toggle', $promo->id) }}">
                        @csrf @method('PATCH')
                        <button class="btn btn-sm btn-secondary">Đã tắt</button>
                    </form>
                @elseif(!$isTrulyActive)
                    <button class="btn btn-sm btn-secondary" disabled>Đã tắt</button>
                @else
                    <form method="POST" action="{{ route('promotions.toggle', $promo->id) }}">
                        @csrf @method('PATCH')
                        <button class="btn btn-sm btn-success">Hoạt động</button>
                    </form>
                @endif

                <form method="POST" action="{{ route('promotions.destroy', $promo->id) }}" onsubmit="return confirm('Bạn chắc chắn xoá?')">
                    @csrf @method('DELETE')
                    <button class="btn btn-sm btn-danger">Xoá</button>
                </form>
            </td>
        </tr>
    @empty
        <tr>
            <td colspan="11">Không có mã khuyến mãi nào.</td>
        </tr>
    @endforelse
    </tbody>
</table>
</div>
@endsection
