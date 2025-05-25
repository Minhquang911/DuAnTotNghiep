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
                                <i class="fas fa-images me-2"></i>Quản lý Banner
                            </h3>
                            <a href="{{ route('admin.banners.create') }}" class="btn btn-light btn-sm">
                                <i class="fas fa-plus me-1"></i> Thêm banner mới
                            </a>
                        </div>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('admin.banners.index') }}" method="GET" class="mb-4">
                            <div class="row g-3 align-items-center">
                                <div class="col-md-4">
                                    <div class="input-group">
                                        <span class="input-group-text bg-light">
                                            <i class="fas fa-search text-muted"></i>
                                        </span>
                                        <input type="text" name="search" class="form-control"
                                            placeholder="Tìm kiếm theo tiêu đề banner..." value="{{ request('search') }}">
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <button type="submit" class="btn btn-primary">
                                        <i class="fas fa-search me-1"></i> Tìm kiếm
                                    </button>
                                    <a href="{{ route('admin.banners.index') }}" class="btn btn-secondary">
                                        <i class="fas fa-redo me-1"></i> Làm mới
                                    </a>
                                </div>
                            </div>
                        </form>

                        <div class="table-responsive">
                            <table class="table table-hover table-striped align-middle">
                                <thead class="table-light">
                                    <tr>
                                        <th class="text-center" style="width: 60px">STT</th>
                                        <th style="width: 120px">Hình ảnh</th>
                                        <th>Tiêu đề</th>
                                        <th>Link</th>
                                        <th style="width: 200px">Thời gian hiển thị</th>
                                        <th class="text-center" style="width: 100px">Trạng thái</th>
                                        <th class="text-center" style="width: 150px">Thao tác</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($banners as $banner)
                                        <tr>
                                            <td class="text-center">{{ $loop->iteration }}</td>
                                            <td>
                                                <div class="banner-image-wrapper">
                                                    <img src="{{ asset('storage/' . $banner->image) }}"
                                                        alt="{{ $banner->title }}" class="img-thumbnail banner-image">
                                                </div>
                                            </td>
                                            <td>
                                                <div class="fw-bold">{{ $banner->title }}</div>
                                                @if ($banner->description)
                                                    <small
                                                        class="text-muted">{{ Str::limit($banner->description, 50) }}</small>
                                                @endif
                                            </td>
                                            <td>
                                                <a href="{{ $banner->link }}" target="_blank"
                                                    class="text-primary text-decoration-none">
                                                    <i class="fas fa-external-link-alt me-1"></i>
                                                    {{ Str::limit($banner->link, 30) }}
                                                </a>
                                            </td>
                                            <td>
                                                <div class="d-flex flex-column">
                                                    <div class="d-flex align-items-center mb-1">
                                                        <i class="fas fa-calendar-alt text-muted me-2"></i>
                                                        <div>
                                                            <small class="text-muted d-block">Từ ngày:</small>
                                                            <span
                                                                class="fw-medium">{{ $banner->start_date ? $banner->start_date->format('d/m/Y') : 'N/A' }}</span>
                                                        </div>
                                                    </div>
                                                    <div class="d-flex align-items-center">
                                                        <i class="fas fa-calendar-check text-muted me-2"></i>
                                                        <div>
                                                            <small class="text-muted d-block">Đến ngày:</small>
                                                            <span
                                                                class="fw-medium">{{ $banner->end_date ? $banner->end_date->format('d/m/Y') : 'N/A' }}</span>
                                                        </div>
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="text-center">
                                                <div class="form-check form-switch d-inline-block">
                                                    <input class="form-check-input status-toggle" type="checkbox"
                                                        data-banner-id="{{ $banner->id }}"
                                                        {{ $banner->is_active ? 'checked' : '' }} data-bs-toggle="tooltip"
                                                        title="{{ $banner->is_active ? 'Đang hiển thị' : 'Đang ẩn' }}">
                                                </div>
                                            </td>
                                            <td class="text-center">
                                                <div class="btn-group">
                                                    <a href="{{ route('admin.banners.edit', $banner->id) }}"
                                                        class="btn btn-sm btn-outline-primary" data-bs-toggle="tooltip"
                                                        title="Chỉnh sửa">
                                                        <i class="fas fa-edit"></i>
                                                    </a>
                                                    <form action="{{ route('admin.banners.destroy', $banner->id) }}"
                                                        method="POST" class="d-inline">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn btn-sm btn-outline-danger"
                                                            onclick="return confirm('Bạn có chắc chắn muốn xóa banner này?')"
                                                            data-bs-toggle="tooltip" title="Xóa">
                                                            <i class="fas fa-trash"></i>
                                                        </button>
                                                    </form>
                                                </div>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="8" class="text-center py-4">
                                                <div class="text-muted">
                                                    <i class="fas fa-images fa-2x mb-3"></i>
                                                    <p class="mb-0">Không có banner nào.</p>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>

                        <div class="mt-4">
                            {{ $banners->links('pagination::bootstrap-5') }}
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

        .banner-image-wrapper {
            width: 100px;
            height: 60px;
            overflow: hidden;
            border-radius: 4px;
        }

        .banner-image {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: transform 0.3s ease;
        }

        .banner-image:hover {
            transform: scale(1.1);
        }

        .form-switch {
            padding-left: 2.5em;
        }

        .form-switch .form-check-input {
            width: 2.5em;
            margin-left: -2.5em;
            background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='-4 -4 8 8'%3e%3ccircle r='3' fill='rgba%280, 0, 0, 0.25%29'/%3e%3c/svg%3e");
            background-position: left center;
            border-radius: 2em;
            transition: background-position .15s ease-in-out;
        }

        .form-switch .form-check-input:checked {
            background-position: right center;
            background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='-4 -4 8 8'%3e%3ccircle r='3' fill='%23fff'/%3e%3c/svg%3e");
        }

        .btn-group {
            box-shadow: none;
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
                const bannerId = $(this).data('banner-id');
                const isActive = $(this).prop('checked') ? 1 : 0;
                const $toggle = $(this);

                $toggle.prop('disabled', true);

                $.ajax({
                    url: `/admin/banners/${bannerId}/toggle-status`,
                    method: 'POST',
                    data: {
                        _token: '{{ csrf_token() }}',
                        is_active: isActive ? 1 : 0
                    },
                    success: function(response) {
                        if (response.success) {
                            $toggle.attr('title', isActive ? 'Đang hiển thị' : 'Đang ẩn');
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
                        toastr.error('Có lỗi xảy ra khi cập nhật trạng thái');
                    },
                    complete: function() {
                        $toggle.prop('disabled', false);
                    }
                });
            });
        });
    </script>
@endpush