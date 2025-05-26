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
                                <i class="fas fa-language me-2"></i>Quản lý ngôn ngữ sách
                            </h3>
                            <a href="{{ route('admin.languages.create') }}" class="btn btn-light btn-sm">
                                <i class="fas fa-plus me-1"></i> Thêm ngôn ngữ mới
                            </a>
                        </div>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('admin.languages.index') }}" method="GET" class="mb-4">
                            <div class="row g-3 align-items-center">
                                <div class="col-md-3">
                                    <div class="input-group">
                                        <span class="input-group-text bg-light">
                                            <i class="fas fa-search text-muted"></i>
                                        </span>
                                        <input type="text" name="search" class="form-control"
                                            placeholder="Tìm kiếm theo tên hoặc mã ngôn ngữ..." 
                                            value="{{ request('search') }}">
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <select name="status" class="form-select">
                                        <option value="">-- Trạng thái --</option>
                                        <option value="active" {{ request('status') === 'active' ? 'selected' : '' }}>Đang hoạt động</option>
                                        <option value="inactive" {{ request('status') === 'inactive' ? 'selected' : '' }}>Không hoạt động</option>
                                    </select>
                                </div>
                                <div class="col-md-3">
                                    <select name="sort" class="form-select">
                                        <option value="latest" {{ request('sort') === 'latest' ? 'selected' : '' }}>Mới nhất</option>
                                        <option value="oldest" {{ request('sort') === 'oldest' ? 'selected' : '' }}>Cũ nhất</option>
                                        <option value="name_asc" {{ request('sort') === 'name_asc' ? 'selected' : '' }}>Tên A-Z</option>
                                        <option value="name_desc" {{ request('sort') === 'name_desc' ? 'selected' : '' }}>Tên Z-A</option>
                                        <option value="code_asc" {{ request('sort') === 'code_asc' ? 'selected' : '' }}>Mã A-Z</option>
                                        <option value="code_desc" {{ request('sort') === 'code_desc' ? 'selected' : '' }}>Mã Z-A</option>
                                    </select>
                                </div>
                                <div class="col-md-3 d-flex justify-content-end">
                                    <button type="submit" class="btn btn-primary w-100">
                                        <i class="fas fa-search me-1"></i> Tìm kiếm
                                    </button>
                                    <a href="{{ route('admin.languages.index') }}" class="btn btn-secondary ms-2 w-100">
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
                                        <th>Tên ngôn ngữ</th>
                                        <th>Mã ngôn ngữ</th>
                                        <th>Slug</th>
                                        <th class="text-center" style="width: 100px">Trạng thái</th>
                                        <th class="text-center" style="width: 150px">Thao tác</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($languages as $language)
                                        <tr>
                                            <td class="text-center">{{ $loop->iteration }}</td>
                                            <td>
                                                <div class="fw-bold">{{ $language->name }}</div>
                                            </td>
                                            <td>
                                                <span class="badge bg-info">{{ $language->code }}</span>
                                            </td>
                                            <td>
                                                <code class="text-muted">{{ $language->slug }}</code>
                                            </td>
                                            <td class="text-center">
                                                <div class="form-check form-switch d-inline-block">
                                                    <input class="form-check-input status-toggle" type="checkbox"
                                                        data-language-id="{{ $language->id }}"
                                                        {{ $language->is_active ? 'checked' : '' }}
                                                        data-bs-toggle="tooltip"
                                                        title="{{ $language->is_active ? 'Đang hoạt động' : 'Không hoạt động' }}">
                                                </div>
                                            </td>
                                            <td class="text-center">
                                                <div class="btn-group">
                                                    <a href="{{ route('admin.languages.edit', $language->id) }}"
                                                        class="btn btn-sm btn-outline-primary" 
                                                        data-bs-toggle="tooltip" 
                                                        title="Chỉnh sửa">
                                                        <i class="fas fa-edit"></i>
                                                    </a>
                                                    <form action="{{ route('admin.languages.destroy', $language->id) }}"
                                                        method="POST" class="d-inline">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" 
                                                            class="btn btn-sm btn-outline-danger"
                                                            onclick="return confirm('Bạn có chắc chắn muốn xóa ngôn ngữ này?')"
                                                            data-bs-toggle="tooltip" 
                                                            title="Xóa">
                                                            <i class="fas fa-trash"></i>
                                                        </button>
                                                    </form>
                                                </div>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="6" class="text-center py-4">
                                                <div class="text-muted">
                                                    <i class="fas fa-language fa-2x mb-3"></i>
                                                    <p class="mb-0">Không có ngôn ngữ nào.</p>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>

                        <div class="mt-4">
                            {{ $languages->links('pagination::bootstrap-5') }}
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

        .table > :not(caption) > * > * {
            padding: 1rem 0.75rem;
        }

        .pagination {
            margin-bottom: 0;
        }

        code {
            font-size: 0.875em;
            color: #6c757d;
            background-color: #f8f9fa;
            padding: 0.2em 0.4em;
            border-radius: 3px;
        }

        .badge {
            font-size: 0.875em;
            padding: 0.35em 0.65em;
        }
    </style>
@endpush

@push('scripts')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
    <script>
        $(document).ready(function() {
            // Khởi tạo tooltips
            var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
            var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
                return new bootstrap.Tooltip(tooltipTriggerEl)
            });

            // Xử lý toggle status
            $('.status-toggle').change(function() {
                const languageId = $(this).data('language-id');
                const isActive = $(this).prop('checked') ? 1 : 0;
                const $toggle = $(this);

                $toggle.prop('disabled', true);

                $.ajax({
                    url: `/admin/languages/${languageId}/toggle-status`,
                    method: 'POST',
                    data: {
                        _token: '{{ csrf_token() }}',
                        is_active: isActive ? 1 : 0
                    },
                    success: function(response) {
                        if (response.success) {
                            $toggle.attr('title', isActive ? 'Đang hoạt động' : 'Không hoạt động');
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