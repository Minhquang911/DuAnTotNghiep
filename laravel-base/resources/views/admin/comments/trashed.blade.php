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
                    <div class="card-header bg-gradient-danger">
                        <div class="d-flex justify-content-between align-items-center">
                            <h3 class="card-title text-white fw-bold">
                                <i class="fas fa-trash-alt me-2"></i>Bình luận đã xóa
                            </h3>

                            <a href="{{ route('admin.comments.index') }}" class="btn btn-light btn-sm">
                                <i class="fas fa-arrow-left me-1"></i> Quay lại danh sách
                            </a>
                        </div>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('admin.comments.trashed') }}" method="GET" class="mb-4">
                            <div class="row g-3">
                                <div class="col-md-3">
                                    <div class="input-group">
                                        <span class="input-group-text bg-light">
                                            <i class="fas fa-search text-muted"></i>
                                        </span>
                                        <input type="text" name="content" class="form-control"
                                            placeholder="Tìm kiếm nội dung..." value="{{ request('content') }}">
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <select name="product" class="form-select">
                                        <option value="">-- Sản phẩm --</option>
                                        @foreach ($products as $product)
                                            <option value="{{ $product->id }}"
                                                {{ request('product') == $product->id ? 'selected' : '' }}>
                                                {{ $product->title }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-2">
                                    <select name="status" class="form-select">
                                        <option value="">-- Trạng thái --</option>
                                        <option value="approved" {{ request('status') === 'approved' ? 'selected' : '' }}>Đã duyệt</option>
                                        <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>Chờ duyệt</option>
                                    </select>
                                </div>
                                <div class="col-md-2">
                                    <select name="sort" class="form-select">
                                        <option value="latest" {{ request('sort') === 'latest' ? 'selected' : '' }}>Mới xóa nhất</option>
                                        <option value="oldest" {{ request('sort') === 'oldest' ? 'selected' : '' }}>Xóa lâu nhất</option>
                                        <option value="product" {{ request('sort') === 'product' ? 'selected' : '' }}>Theo sản phẩm</option>
                                        <option value="user" {{ request('sort') === 'user' ? 'selected' : '' }}>Theo người dùng</option>
                                    </select>
                                </div>
                                <div class="col-md-2">
                                    <select name="per_page" class="form-select">
                                        <option value="10" {{ request('per_page', 10) == 10 ? 'selected' : '' }}>10 bình luận</option>
                                        <option value="20" {{ request('per_page', 10) == 20 ? 'selected' : '' }}>20 bình luận</option>
                                        <option value="50" {{ request('per_page', 10) == 50 ? 'selected' : '' }}>50 bình luận</option>
                                        <option value="100" {{ request('per_page', 10) == 100 ? 'selected' : '' }}>100 bình luận</option>
                                    </select>
                                </div>
                                <div class="col-md-1">
                                    <button type="submit" class="btn btn-primary w-100">
                                        <i class="fas fa-search"></i>
                                    </button>
                                </div>
                            </div>
                        </form>

                        <div class="table-responsive">
                            <table class="table table-hover table-striped align-middle">
                                <thead class="table-light">
                                    <tr>
                                        <th class="text-center" style="width: 60px">STT</th>
                                        <th>Nội dung</th>
                                        <th>Sản phẩm</th>
                                        <th>Người dùng</th>
                                        <th class="text-center" style="width: 100px">Trạng thái</th>
                                        <th class="text-center" style="width: 150px">Thời gian xóa</th>
                                        <th class="text-center" style="width: 150px">Thao tác</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($trashedComments as $comment)
                                        <tr>
                                            <td class="text-center">{{ $loop->iteration }}</td>
                                            <td>
                                                <div class="fw-bold">{{ Str::limit($comment->content, 100) }}</div>
                                                <small class="text-muted">
                                                    <i class="far fa-clock me-1"></i>
                                                    {{ $comment->created_at->format('d/m/Y H:i') }}
                                                </small>
                                                @if ($comment->replies_count > 0)
                                                    <div class="mt-1 text-info">
                                                        <i class="fas fa-reply-all me-1"></i>
                                                        <small>{{ $comment->replies_count }} phản hồi</small>
                                                    </div>
                                                @endif
                                            </td>
                                            <td>
                                                <a href="{{ route('admin.products.show', $comment->product_id) }}"
                                                    class="text-decoration-none">
                                                    {{ $comment->product->title }}
                                                </a>
                                            </td>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    <img src="{{ $comment->user->avatar ?? asset('images/default-avatar.png') }}"
                                                        class="rounded-circle me-2" width="32" height="32"
                                                        alt="{{ $comment->user->name }}">
                                                    <div>
                                                        <div class="fw-bold">{{ $comment->user->name }}</div>
                                                        <small class="text-muted">{{ $comment->user->email }}</small>
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="text-center">
                                                @if ($comment->is_approved)
                                                    <span class="badge bg-success">Đã duyệt</span>
                                                @else
                                                    <span class="badge bg-warning">Chờ duyệt</span>
                                                @endif
                                            </td>
                                            <td class="text-center">
                                                <small class="text-muted">
                                                    <i class="far fa-clock me-1"></i>
                                                    {{ $comment->deleted_at->format('d/m/Y H:i') }}
                                                </small>
                                            </td>
                                            <td class="text-center">
                                                <div class="btn-group">
                                                    <button type="button" class="btn btn-sm btn-outline-success restore-btn"
                                                        data-comment-id="{{ $comment->id }}" data-bs-toggle="tooltip"
                                                        title="Khôi phục">
                                                        <i class="fas fa-undo"></i>
                                                    </button>
                                                    <button type="button" class="btn btn-sm btn-outline-danger force-delete-btn"
                                                        data-comment-id="{{ $comment->id }}" data-bs-toggle="tooltip"
                                                        title="Xóa vĩnh viễn">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                </div>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="7" class="text-center py-4">
                                                <div class="text-muted">
                                                    <i class="fas fa-trash-alt fa-2x mb-3"></i>
                                                    <p class="mb-0">Không có bình luận nào đã xóa.</p>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>

                        <div class="mt-4">
                            {{ $trashedComments->appends(request()->query())->links('pagination::bootstrap-5') }}
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
        .card-header.bg-gradient-danger {
            background: linear-gradient(45deg, #e74a3b, #be2617);
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
