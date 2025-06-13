@extends('layouts.admin.AdminLayout')

@section('content')
    <style>
        .card-header.bg-gradient-primary {
            background: linear-gradient(45deg, #4e73df, #224abe);
        }

        .product-cover {
            width: 100%;
            max-width: 220px;
            height: auto;
            border-radius: 0.5rem;
            box-shadow: 0 2px 8px rgba(34, 74, 190, 0.08);
            background: #fff;
        }

        .product-attr-label {
            min-width: 120px;
            font-weight: 500;
            color: #224abe;
        }

        .product-badges .badge {
            margin-right: 0.5em;
            margin-bottom: 0.25em;
        }

        .table-variants th,
        .table-variants td {
            vertical-align: middle !important;
        }
    </style>
    <div class="container-fluid">
        <div class="card shadow">
            <div class="card-header bg-gradient-primary">
                <div class="d-flex justify-content-between align-items-center">
                    <h3 class="card-title text-white fw-bold">
                        <i class="fas fa-book me-2"></i>Chi tiết bài viết: {{ $post->title }}
                    </h3>
                    <a href="{{ route('admin.posts.edit', $post->id) }}" class="btn btn-warning btn-sm">
                        <i class="fas fa-edit me-1"></i> Sửa bài viết
                    </a>
                </div>
            </div>
            <div class="card-body">
                <div class="row mb-4">
                    <div class="col-md-3 text-center">
                        @if ($post->image)
                            <img src="{{ asset('storage/' . $post->image) }}" class="product-cover mb-2" width="200"
                                alt="Ảnh bìa">
                        @else
                            <div class="text-muted border rounded p-4 bg-light">Không có ảnh bài viết</div>
                        @endif
                        <div class="mt-2">
                            <span class="badge bg-{{ $post->is_published ? 'success' : 'secondary' }}">
                                {{ $post->is_published ? 'Đã đăng' : 'Chưa đăng' }}
                            </span>
                        </div>
                    </div>
                    <div class="col-md-9">
                        <div class="row mb-2">
                            <div class="col-sm-2 product-attr-label">Tên bài viết:</div>
                            <div class="col-sm-10 fw-bold fs-5">{{ $post->title }}</div>
                        </div>
                        <div class="row mb-2">
                            <div class="col-sm-2 product-attr-label">Slug:</div>
                            <code class="col-sm-10">{{ $post->slug }}</code>
                        </div>
                        <div class="row mb-2">
                            <div class="col-sm-2 product-attr-label">Tác giả:</div>
                            <div class="col-sm-10">{{ $post->user->name }}</div>
                        </div>
                        <div class="row mb-2 align-items-center">
                            <div class="col-6 d-flex">
                                <span class="product-attr-label me-2">Ngày tạo:</span>
                                <span>{{ $post->created_at ? $post->created_at->format('d/m/Y H:i') : '' }}</span>
                            </div>
                            <div class="col-6 d-flex">
                                @if ($post->updated_at && $post->updated_at->ne($post->created_at))
                                    <span class="product-attr-label me-2">Ngày sửa:</span>
                                    <span>{{ $post->updated_at ? $post->updated_at->format('d/m/Y H:i') : '' }}</span>
                                @endif
                            </div>
                        </div>
                        
                        <div class="row mb-2">
                            <div class="col-sm-2 product-attr-label">Nội dung:</div>
                            <div class="col-sm-10">{!! $post->content !!}</div>
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
        /* ... existing styles ... */
        .toggle-replies {
            color: #6c757d;
            font-size: 0.875rem;
        }

        .toggle-replies:hover {
            color: #4e73df;
        }

        .toggle-replies.collapsed .show-text {
            display: inline !important;
        }

        .toggle-replies.collapsed .hide-text {
            display: none !important;
        }

        .toggle-replies:not(.collapsed) .show-text {
            display: none !important;
        }

        .toggle-replies:not(.collapsed) .hide-text {
            display: inline !important;
        }

        .pagination {
            margin-bottom: 0;
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

            // Xử lý duyệt bình luận
            $('.approve-btn').click(function() {
                const commentId = $(this).data('comment-id');
                const $btn = $(this);

                if (confirm('Bạn có chắc chắn muốn duyệt bình luận này?')) {
                    $btn.prop('disabled', true);

                    $.ajax({
                        url: `/admin/comments/${commentId}/approve`,
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
                            toastr.error('Có lỗi xảy ra khi duyệt bình luận');
                        },
                        complete: function() {
                            $btn.prop('disabled', false);
                        }
                    });
                }
            });

            // Xử lý từ chối bình luận
            $('.reject-btn').click(function() {
                const commentId = $(this).data('comment-id');
                const $btn = $(this);

                if (confirm('Bạn có chắc chắn muốn từ chối bình luận này?')) {
                    $btn.prop('disabled', true);

                    $.ajax({
                        url: `/admin/comments/${commentId}/reject`,
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
                            toastr.error('Có lỗi xảy ra khi từ chối bình luận');
                        },
                        complete: function() {
                            $btn.prop('disabled', false);
                        }
                    });
                }
            });

            // Xử lý xóa bình luận
            $('.delete-btn').click(function() {
                const commentId = $(this).data('comment-id');
                const $btn = $(this);

                if (confirm('Bạn có chắc chắn muốn xóa bình luận này?')) {
                    $btn.prop('disabled', true);

                    $.ajax({
                        url: `/admin/comments/${commentId}`,
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
                            toastr.error('Có lỗi xảy ra khi xóa bình luận');
                        },
                        complete: function() {
                            $btn.prop('disabled', false);
                        }
                    });
                }
            });

            // Xử lý thay đổi số lượng hiển thị
            $('#perPageSelect').change(function() {
                const url = new URL(window.location.href);
                url.searchParams.set('per_page', $(this).val());
                window.location.href = url.toString();
            });

            // Xử lý thay đổi sắp xếp
            $('#sortSelect').change(function() {
                const url = new URL(window.location.href);
                url.searchParams.set('sort', $(this).val());
                window.location.href = url.toString();
            });

            // Xử lý toggle replies
            $('.toggle-replies').click(function() {
                $(this).toggleClass('collapsed');
            });
        });
    </script>
@endpush