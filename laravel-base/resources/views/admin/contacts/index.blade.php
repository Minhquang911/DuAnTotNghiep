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

                <!-- Thống kê -->
                <div class="row mb-4">
                    <div class="col-md-4">
                        <div class="card bg-primary text-white">
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <h6 class="card-title mb-0">Tổng số liên hệ</h6>
                                        <h2 class="mt-2 mb-0">{{ $stats['total'] }}</h2>
                                    </div>
                                    <i class="fas fa-comments fa-2x opacity-50"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="card bg-success text-white">
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <h6 class="card-title mb-0">Đã đọc</h6>
                                        <h2 class="mt-2 mb-0">{{ $stats['read'] }}</h2>
                                    </div>
                                    <i class="fas fa-check-circle fa-2x opacity-50"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="card bg-warning text-white">
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <h6 class="card-title mb-0">Chưa đọc</h6>
                                        <h2 class="mt-2 mb-0">{{ $stats['unread'] }}</h2>
                                    </div>
                                    <i class="fas fa-clock fa-2x opacity-50"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card">
                    <div class="card-header bg-gradient-primary">
                        <div class="d-flex justify-content-between align-items-center">
                            <h3 class="card-title text-white fw-bold">
                                <i class="fas fa-comments me-2"></i>Quản lý liên hệ
                            </h3>
                        </div>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('admin.contacts.index') }}" method="GET" class="mb-4">
                            <div class="row g-3">
                                <div class="col-md-3">
                                    <div class="input-group">
                                        <span class="input-group-text bg-light">
                                            <i class="fas fa-search text-muted"></i>
                                        </span>
                                        <input type="text" name="search" class="form-control"
                                               placeholder="Tìm kiếm nội dung..." value="{{ request('search') }}">
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <select name="user_id" class="form-select">
                                        <option value="">-- Người dùng --</option>
                                        @foreach ($users as $user)
                                            <option value="{{ $user->id }}"
                                                {{ request('user_id') == $user->id ? 'selected' : '' }}>
                                                {{ Str::limit($user->name, 30) }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-2">
                                    <select name="is_read" class="form-select">
                                        <option value="">-- Trạng thái --</option>
                                        <option value="1" {{ request('is_read') === '1' ? 'selected' : '' }}>Đã đọc
                                        </option>
                                        <option value="0" {{ request('is_read') === '0' ? 'selected' : '' }}>Chưa đọc
                                        </option>
                                    </select>
                                </div>
                                <div class="col-md-2">
                                    <select name="sort" class="form-select">
                                        <option value="latest" {{ request('sort') === 'latest' ? 'selected' : '' }}>Mới
                                            nhất</option>
                                        <option value="oldest" {{ request('sort') === 'oldest' ? 'selected' : '' }}>Cũ nhất
                                        </option>
                                        <option value="user" {{ request('sort') === 'user' ? 'selected' : '' }}>Theo
                                            người dùng</option>
                                    </select>
                                </div>
                                <div class="col-md-3 d-flex justify-content-end">
                                    <button type="submit" class="btn btn-primary w-100">
                                        <i class="fas fa-search me-1"></i> Tìm kiếm
                                    </button>
                                    <a href="{{ route('admin.contacts.index') }}" class="btn btn-secondary w-100 ms-2">
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
                                    <th>Nội dung</th>
                                    <th>Người dùng</th>
                                    <th class="text-center" style="width: 100px">Trạng thái</th>
                                    <th class="text-center" style="width: 150px">Thao tác</th>
                                </tr>
                                </thead>
                                <tbody>
                                @forelse($contacts as $contact)
                                    <tr>
                                        <td class="text-center">{{ $loop->iteration }}</td>
                                        <td>
                                            <div class="fw-bold">{{ Str::limit($contact->content, 100) }}</div>
                                            <small class="text-muted">
                                                <i class="far fa-clock me-1"></i>
                                                {{ $contact->created_at ? $contact->created_at->format('d/m/Y H:i') : 'Không có thời gian' }}
                                            </small>
                                        </td>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <img src="{{ $contact->user->avatar ?? asset('images/default-avatar.png') }}"
                                                     class="rounded-circle me-2" width="32" height="32"
                                                     alt="{{ $contact->user->name }}">
                                                <div>
                                                    <div class="fw-bold">{{ $contact->user->name }}</div>
                                                    <small class="text-muted">{{ $contact->email }}</small>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="text-center">
                                            @if ($contact->is_read)
                                                <span class="badge bg-info">Đã đọc</span>
                                            @else
                                                <span class="badge bg-primary">Chưa đọc</span>
                                            @endif
                                        </td>
                                        <td class="text-center">
                                            <div class="btn-group">
                                                @if (!$contact->is_read)
                                                    <button type="button"
                                                            class="btn btn-sm btn-outline-success approve-btn"
                                                            data-contact-id="{{ $contact->id }}"
                                                            data-bs-toggle="tooltip" title="Đã đọc">
                                                        <i class="fas fa-check"></i>
                                                    </button>
                                                @else
                                                    <button type="button"
                                                            class="btn btn-sm btn-outline-warning reject-btn"
                                                            data-contact-id="{{ $contact->id }}"
                                                            data-bs-toggle="tooltip" title="Chưa đọc">
                                                        <i class="fas fa-times"></i>
                                                    </button>
                                                @endif
                                                <button type="button"
                                                        class="btn btn-sm btn-outline-danger delete-btn"
                                                        data-contact-id="{{ $contact->id }}" data-bs-toggle="tooltip"
                                                        title="Xóa">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="7" class="text-center py-4">
                                            <div class="text-muted">
                                                <i class="fas fa-comments fa-2x mb-3"></i>
                                                <p class="mb-0">Không có liên hệ nào.</p>
                                            </div>
                                        </td>
                                    </tr>
                                @endforelse
                                </tbody>
                            </table>
                        </div>

                        <div class="mt-4">
                            {{ $contacts->links('pagination::bootstrap-5') }}
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
            font-size: 0.875em;
            padding: 0.35em 0.65em;
        }

        .card {
            border: none;
            box-shadow: 0 0.15rem 1.75rem 0 rgba(58, 59, 69, 0.15);
        }

        .card .card-body {
            padding: 1.25rem;
        }

        .card .card-title {
            font-size: 0.875rem;
            text-transform: uppercase;
            letter-spacing: 0.025em;
        }

        .card .fa-2x {
            font-size: 1.5em;
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

            // Xử lý đã đọc
            $('.approve-btn').click(function() {
                const contactId = $(this).data('contact-id');
                const $btn = $(this);

                if (confirm('Bạn có chắc chắn muốn đã đọc liên hệ này?')) {
                    $btn.prop('disabled', true);

                    $.ajax({
                        url: `/admin/contacts/${contactId}/approve`,
                        method: 'POST',
                        data: {
                            _token: '{{ csrf_token() }}'
                        },
                        success: function(response) {
                            if (response.success) {
                                toastr.success(response.message);
                                setTimeout(() => window.location.reload(), 1000);
                            } else {
                                toastr.error(response.message);
                            }
                        },
                        error: function() {
                            toastr.error('Có lỗi xảy ra khi đã đọc liên hệ');
                        },
                        complete: function() {
                            $btn.prop('disabled', false);
                        }
                    });
                }
            });

            // Xử lý từ chối bình luận
            $('.reject-btn').click(function() {
                const contactId = $(this).data('contact-id');
                const $btn = $(this);

                if (confirm('Bạn có chắc chắn muốn chưa đọc liên hệ này?')) {
                    $btn.prop('disabled', true);

                    $.ajax({
                        url: `/admin/contacts/${contactId}/reject`,
                        method: 'POST',
                        data: {
                            _token: '{{ csrf_token() }}'
                        },
                        success: function(response) {
                            if (response.success) {
                                toastr.success(response.message);
                                setTimeout(() => window.location.reload(), 1000);
                            } else {
                                toastr.error(response.message);
                            }
                        },
                        error: function() {
                            toastr.error('Có lỗi xảy ra khi chưa đọc liên hệ');
                        },
                        complete: function() {
                            $btn.prop('disabled', false);
                        }
                    });
                }
            });

            // Xử lý xóa bình luận
            $('.delete-btn').click(function() {
                const contactId = $(this).data('contact-id');
                const $btn = $(this);

                if (confirm('Bạn có chắc chắn muốn xóa liên hệ này?')) {
                    $btn.prop('disabled', true);

                    $.ajax({
                        url: `/admin/contacts/${contactId}`,
                        method: 'DELETE',
                        data: {
                            _token: '{{ csrf_token() }}'
                        },
                        success: function(response) {
                            if (response.success) {
                                toastr.success(response.message);
                                setTimeout(() => window.location.reload(), 1000);
                            } else {
                                toastr.error(response.message);
                            }
                        },
                        error: function() {
                            toastr.error('Có lỗi xảy ra khi xóa liên hệ');
                        },
                        complete: function() {
                            $btn.prop('disabled', false);
                        }
                    });
                }
            });
        });
    </script>
@endpush
