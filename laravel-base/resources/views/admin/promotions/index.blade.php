@extends('layouts.admin.AdminLayout')

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">

                @if (session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <i class="fas fa-check-circle me-2"></i>
                        {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                @if (session('error'))
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <i class="fas fa-exclamation-circle me-2"></i>
                        {{ session('error') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                <div class="card">
                    <div class="card-header bg-gradient-primary">
                        <div class="d-flex justify-content-between align-items-center">
                            <h3 class="card-title text-white fw-bold">
                                <i class="fas fa-gift me-2"></i>Quản lý Mã khuyến mãi
                            </h3>
                            <a href="{{ route('admin.promotions.create') }}" class="btn btn-light btn-sm">
                                <i class="fas fa-plus me-1"></i> Thêm mã khuyến mãi
                            </a>
                        </div>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('admin.promotions.index') }}" method="GET" class="mb-4">
                            <div class="row g-3 align-items-center">
                                <div class="col-md-4">
                                    <div class="input-group">
                                        <span class="input-group-text bg-light">
                                            <i class="fas fa-search text-muted"></i>
                                        </span>
                                        <input type="text" name="search" class="form-control"
                                            placeholder="Tìm kiếm theo tiêu đề hoặc mô tả..."
                                            value="{{ request('search') }}">
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <button type="submit" class="btn btn-primary">
                                        <i class="fas fa-search me-1"></i> Tìm kiếm
                                    </button>
                                    <a href="{{ route('admin.promotions.index') }}" class="btn btn-secondary">
                                        <i class="fas fa-redo me-1"></i> Làm mới
                                    </a>
                                </div>
                            </div>
                        </form>

                        <div class="table-responsive">
                            <table class="table table-hover align-middle mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th class="text-center" style="width: 60px">STT</th>
                                        <th style="width: 120px">Hình ảnh</th>
                                        <th>Tiêu đề</th>
                                        <th class="text-center">Loại</th>
                                        <th class="text-end">Giảm</th>
                                        <th class="text-end">Tối đa</th>
                                        <th class="text-end">Đơn tối thiểu</th>
                                        <th class="text-center">Số lượng</th>
                                        <th style="width: 170px">Thời gian</th>
                                        <th class="text-center" style="width: 80px">TT</th>
                                        <th class="text-center" style="width: 110px">Thao tác</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($promotions as $promotion)
                                        <tr>
                                            <td class="text-center text-muted">
                                                {{ ($promotions->currentPage() - 1) * $promotions->perPage() + $loop->iteration }}
                                            </td>
                                            <td class="text-center">
                                                <div class="banner-image-wrappert">
                                                    @if ($promotion->image)
                                                        <img src="{{ asset('storage/' . $promotion->image) }}"
                                                            alt="{{ $promotion->title }}"
                                                            class="img-thumbnail banner-image">
                                                    @else
                                                        <img src="https://png.pngtree.com/png-clipart/20210121/ourmid/pngtree-sale-symbol-3d-illustration-isolated-on-transparent-background-png-image_2767225.jpg"
                                                            alt="No image" class="img-thumbnail banner-image">
                                                    @endif
                                                </div>
                                            </td>
                                            <td>
                                                <div class="fw-semibold">{{ $promotion->title }}</div>
                                                <small
                                                    class="d-none d-md-block text-muted">{{ Str::limit($promotion->description, 40) }}</small>
                                            </td>
                                            <td class="text-center">
                                                <span class="badge bg-info-subtle text-info fw-normal">
                                                    {{ $promotion->discount_type == 'percent' ? 'Phần trăm' : 'Tiền mặt' }}
                                                </span>
                                            </td>
                                            <td class="text-end">
                                                <span
                                                    class="fw-medium">{{ $promotion->discount_type == 'percent' ? number_format($promotion->discount_value) . '%' : number_format($promotion->discount_value) . 'đ' }}</span>
                                            </td>
                                            <td class="text-end">
                                                <span
                                                    class="text-dark">{{ $promotion->max_discount_value ? number_format($promotion->max_discount_value) . 'đ' : '-' }}</span>
                                            </td>
                                            <td class="text-end">
                                                <span
                                                    class="text-dark">{{ $promotion->min_order_value ? number_format($promotion->min_order_value) . 'đ' : '-' }}</span>
                                            </td>
                                            <td class="text-center">
                                                <div class="d-flex flex-column gap-1">
                                                    <span class="text-muted" style="font-size:12px">Số lượng: <span
                                                            class="fw-medium text-dark">{{ $promotion->usage_limit }}</span></span>
                                                    <span class="text-muted" style="font-size:12px">Đã dùng: <span
                                                            class="fw-medium text-dark">{{ $promotion->used_count }}</span></span>
                                                    <span class="text-muted" style="font-size:12px">Còn lại: <span
                                                            class="fw-medium text-dark">{{ $promotion->usage_limit - $promotion->used_count }}</span></span>

                                                </div>
                                            </td>
                                            <td>
                                                <div class="d-flex flex-column gap-1">
                                                    <span class="text-muted" style="font-size:12px">Từ: <span
                                                            class="fw-medium text-dark">{{ $promotion->start_date ? \Carbon\Carbon::parse($promotion->start_date)->format('d/m/Y') : 'N/A' }}</span></span>
                                                    <span class="text-muted" style="font-size:12px">Đến: <span
                                                            class="fw-medium text-dark">{{ $promotion->end_date ? \Carbon\Carbon::parse($promotion->end_date)->format('d/m/Y') : 'N/A' }}</span></span>
                                                </div>
                                            </td>
                                            <td class="text-center">
                                                <div class="form-check form-switch d-inline-block">
                                                    <input class="form-check-input status-toggle" type="checkbox"
                                                        data-promotion-id="{{ $promotion->id }}"
                                                        {{ $promotion->is_active ? 'checked' : '' }}
                                                        data-bs-toggle="tooltip"
                                                        title="{{ $promotion->is_active ? 'Đang áp dụng' : 'Đang ẩn' }}">
                                                </div>
                                            </td>
                                            <td class="text-center">
                                                <div class="btn-group">
                                                    <a href="{{ route('admin.promotions.edit', $promotion->id) }}"
                                                        class="btn btn-sm btn-outline-primary" data-bs-toggle="tooltip"
                                                        title="Chỉnh sửa">
                                                        <i class="fas fa-edit"></i>
                                                    </a>
                                                    <form action="{{ route('admin.promotions.destroy', $promotion->id) }}"
                                                        method="POST" class="d-inline">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn btn-sm btn-outline-danger"
                                                            onclick="return confirm('Bạn có chắc chắn muốn xóa mã khuyến mãi này?')"
                                                            data-bs-toggle="tooltip" title="Xóa">
                                                            <i class="fas fa-trash"></i>
                                                        </button>
                                                    </form>
                                                </div>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="10" class="text-center py-4">
                                                <div class="text-muted">
                                                    <i class="fas fa-gift fa-2x mb-3"></i>
                                                    <p class="mb-0">Không có mã khuyến mãi nào.</p>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>

                        <div class="mt-4">
                            {{ $promotions->links('pagination::bootstrap-5') }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('styles')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">
    <style>
        .card-header.bg-gradient-primary {
            background: linear-gradient(45deg, #4e73df, #224abe);
        }

        .promotion-image-wrapper {
            width: 80px;
            height: 80px;
            overflow: hidden;
            border-radius: 8px;
            border: 1px solid #eee;
            display: flex;
            align-items: center;
            justify-content: center;
            background: #fafbfc;
        }

        .promotion-image {
            max-width: 100%;
            max-height: 100%;
            transition: transform 0.2s;
        }

        .promotion-image-wrapper:hover .promotion-image {
            transform: scale(1.08);
        }

        .form-switch {
            padding-left: 2.5em;
        }

        .form-switch .form-check-input {
            width: 2.5em;
            margin-left: -2.5em;
            border-radius: 2em;
            transition: background-position .15s ease-in-out;
        }

        .form-switch .form-check-input:checked {
            background-position: right center;
        }

        .btn-group .btn {
            padding: 0.25rem 0.5rem;
        }

        .table> :not(caption)>*>* {
            padding: 1rem 0.75rem;
        }

        .pagination {
            margin-bottom: 0;
        }

        .badge {
            font-size: 0.875rem;
            padding: 0.5em 0.75em;
        }

        .table-hover>tbody>tr:hover {
            background-color: #f6f9fc;
        }

        .object-fit-cover {
            object-fit: cover;
        }
    </style>
@endpush

@push('scripts')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
    <script>
        $(document).ready(function() {
            // Khởi tạo tooltips
            var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
            var tooltipList = tooltipTriggerList.map(function(tooltipTriggerEl) {
                return new bootstrap.Tooltip(tooltipTriggerEl)
            });

            // Xử lý toggle status
            $('.status-toggle').change(function() {
                const promotionId = $(this).data('promotion-id');
                const isActive = $(this).prop('checked') ? 1 : 0;
                const $toggle = $(this);

                $toggle.prop('disabled', true);

                $.ajax({
                    url: `/admin/promotions/${promotionId}/toggle-status`,
                    method: 'POST',
                    data: {
                        _token: '{{ csrf_token() }}',
                        is_active: isActive ? 1 : 0
                    },
                    success: function(response) {
                        if (response.success) {
                            $toggle.attr('title', isActive ? 'Đang áp dụng' : 'Đang ẩn');
                            if ($toggle.data('bs.tooltip')) {
                                $toggle.tooltip('dispose');
                            }
                            $toggle.tooltip();
                            toastr.success(response.message);
                        } else {
                            $toggle.prop('checked', !isActive);
                            toastr.error(response.message);
                        }
                    },
                    error: function(xhr) {
                        $toggle.prop('checked', !isActive);
                        toastr.error(xhr.responseJSON?.message ||
                            'Có lỗi xảy ra khi cập nhật trạng thái');
                    },
                    complete: function() {
                        $toggle.prop('disabled', false);
                    }
                });
            });
        });
    </script>
@endpush