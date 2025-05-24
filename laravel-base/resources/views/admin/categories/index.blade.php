
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
                                <i class="fas fa-tags me-2"></i>Quản lý Danh mục
                            </h3>
                            <a href="{{ route('admin.categories.create') }}" class="btn btn-light btn-sm">
                                <i class="fas fa-plus me-1"></i> Thêm danh mục mới
                            </a>
                        </div>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('admin.categories.index') }}" method="GET" class="mb-4">
                            <div class="row g-3 align-items-center">
                                <div class="col-md-6">
                                    <div class="input-group">
                                        <span class="input-group-text bg-light">
                                            <i class="fas fa-search text-muted"></i>
                                        </span>
                                        <input type="text" name="search" class="form-control"
                                            placeholder="Tìm kiếm theo tên danh mục..." value="{{ request('search') }}">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <button type="submit" class="btn btn-primary">
                                        <i class="fas fa-search me-1"></i> Tìm kiếm
                                    </button>
                                    <a href="{{ route('admin.categories.index') }}" class="btn btn-secondary">
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
                                        <th>Tên danh mục</th>
                                        <th>Slug</th>
                                        <th>Danh mục cha</th>
                                        <th>Mô tả</th>
                                        <th class="text-center" style="width: 100px">Trạng thái</th>
                                        <th class="text-center" style="width: 150px">Thao tác</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($categories as $category)
                                        @include('admin.categories.partials.category_row', [
                                            'category' => $category,
                                            'level' => 0,
                                            'loop' => $loop,
                                        ])
                                    @empty
                                        <tr>
                                            <td colspan="7" class="text-center py-4">
                                                <div class="text-muted">
                                                    <i class="fas fa-tags fa-2x mb-3"></i>
                                                    <p class="mb-0">Không có danh mục nào.</p>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>

                        <div class="mt-4">
                            {{ $categories->links('pagination::bootstrap-5') }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
    <script>
        $(document).ready(function() {
            // Khởi tạo tooltips
            var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
            var tooltipList = tooltipTriggerList.map(function(tooltipTriggerEl) {
                return new bootstrap.Tooltip(tooltipTriggerEl)
            });

            $('.category-status-toggle').change(function() {
                const categoryId = $(this).data('category-id');
                const isActive = $(this).prop('checked') ? 1 : 0;
                const $toggle = $(this);

                $toggle.prop('disabled', true);

                $.ajax({
                    url: `/admin/categories/${categoryId}/toggle-status`,
                    method: 'POST',
                    data: {
                        _token: '{{ csrf_token() }}',
                        is_active: isActive ? 1 : 0
                    },
                    success: function(response) {
                        if (response.success) {
                            $toggle.attr('title', isActive ? 'Đang hoạt động' : 'Đang khóa');
                            if ($toggle.data('bs.tooltip')) {
                                $toggle.tooltip('dispose');
                            }
                            $toggle.tooltip();
                            toastr.success(response.message);
                            setTimeout(function() {
                                location.reload();
                            }, 1000);
                        } else {
                            $toggle.prop('checked', !isActive);
                            toastr.error(response.message);
                        }
                    },
                    error: function() {
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

@push('styles')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">
    <style>
        .card-header.bg-gradient-primary {
            background: linear-gradient(45deg, #4e73df, #224abe);
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

        .category-name {
            font-weight: 500;
            color: #2c3e50;
        }

        .category-slug {
            font-size: 0.875rem;
            color: #6c757d;
        }

        .category-description {
            font-size: 0.875rem;
            color: #6c757d;
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }

        .category-parent {
            font-size: 0.875rem;
            color: #6c757d;
        }

        .category-level {
            margin-left: 1.5rem;
            position: relative;
        }

        .category-level::before {
            content: '';
            position: absolute;
            left: -1rem;
            top: 50%;
            width: 0.75rem;
            height: 1px;
            background-color: #dee2e6;
        }
    </style>
@endpush
