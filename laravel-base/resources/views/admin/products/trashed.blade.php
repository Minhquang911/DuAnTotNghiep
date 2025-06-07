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
                                <i class="fas fa-trash me-2"></i>Thùng rác sản phẩm
                            </h3>
                            <div>
                                <button type="button" class="btn btn-light btn-sm me-2" id="restoreAllBtn">
                                    <i class="fas fa-undo me-1"></i> Khôi phục tất cả
                                </button>
                                <button type="button" class="btn btn-light btn-sm me-2" id="forceDeleteAllBtn">
                                    <i class="fas fa-trash-alt me-1"></i> Xóa vĩnh viễn tất cả
                                </button>
                                <a href="{{ route('admin.products.index') }}" class="btn btn-light btn-sm">
                                    <i class="fas fa-arrow-left me-1"></i> Quay lại
                                </a>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('admin.products.trashed') }}" method="GET" class="mb-4">
                            <div class="row g-3 align-items-center">
                                <div class="col-md-3">
                                    <div class="input-group">
                                        <span class="input-group-text bg-light">
                                            <i class="fas fa-search text-muted"></i>
                                        </span>
                                        <input type="text" name="search" class="form-control"
                                            placeholder="Tìm kiếm theo tên, tác giả, ISBN..."
                                            value="{{ request('search') }}">
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <select name="category_id" class="form-select">
                                        <option value="">-- Danh mục --</option>
                                        @foreach ($categories as $cat)
                                            <option value="{{ $cat->id }}"
                                                {{ request('category_id') == $cat->id ? 'selected' : '' }}>
                                                {{ $cat->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-2">
                                    <select name="publisher_id" class="form-select">
                                        <option value="">-- Nhà xuất bản --</option>
                                        @foreach ($publishers as $pub)
                                            <option value="{{ $pub->id }}"
                                                {{ request('publisher_id') == $pub->id ? 'selected' : '' }}>
                                                {{ $pub->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-2">
                                    <select name="sort" class="form-select">
                                        <option value="deleted_at_desc" {{ request('sort') === 'deleted_at_desc' ? 'selected' : '' }}>
                                            Xóa gần nhất</option>
                                        <option value="deleted_at_asc" {{ request('sort') === 'deleted_at_asc' ? 'selected' : '' }}>
                                            Xóa lâu nhất</option>
                                        <option value="title_asc" {{ request('sort') === 'title_asc' ? 'selected' : '' }}>
                                            Tên A-Z</option>
                                        <option value="title_desc" {{ request('sort') === 'title_desc' ? 'selected' : '' }}>
                                            Tên Z-A</option>
                                        <option value="author_asc" {{ request('sort') === 'author_asc' ? 'selected' : '' }}>
                                            Tác giả A-Z</option>
                                        <option value="author_desc" {{ request('sort') === 'author_desc' ? 'selected' : '' }}>
                                            Tác giả Z-A</option>
                                    </select>
                                </div>
                                <div class="col-md-3 d-flex justify-content-end">
                                    <button type="submit" class="btn btn-primary w-100">
                                        <i class="fas fa-search me-1"></i> Tìm kiếm
                                    </button>
                                    <a href="{{ route('admin.products.trashed') }}" class="btn btn-secondary ms-2 w-100">
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
                                        <th>Ảnh bìa</th>
                                        <th>Tên sách</th>
                                        <th>Tác giả</th>
                                        <th>Danh mục</th>
                                        <th>Nhà xuất bản</th>
                                        <th>Ngày xóa</th>
                                        <th class="text-center" style="width: 150px">Thao tác</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($products as $product)
                                        <tr>
                                            <td class="text-center">
                                                {{ ($products->currentPage() - 1) * $products->perPage() + $loop->iteration }}
                                            </td>
                                            <td>
                                                @if ($product->cover_image_url)
                                                    <img src="{{ $product->cover_image_url }}" alt="Ảnh bìa"
                                                        class="img-thumbnail"
                                                        style="width: 50px; height: 70px; object-fit: cover;">
                                                @else
                                                    <span class="text-muted">Không có</span>
                                                @endif
                                            </td>
                                            <td>
                                                <div class="fw-bold">{{ $product->title }}</div>
                                                <div class="text-muted small">ISBN: {{ $product->isbn }}</div>
                                            </td>
                                            <td>{{ $product->author }}</td>
                                            <td>{{ $product->category ? $product->category->name : '-' }}</td>
                                            <td>{{ $product->publisher ? $product->publisher->name : '-' }}</td>
                                            <td>{{ $product->deleted_at->format('d/m/Y H:i') }}</td>
                                            <td class="text-center">
                                                <div class="btn-group">
                                                    <button type="button" class="btn btn-sm btn-outline-success restore-btn"
                                                        data-product-id="{{ $product->id }}" data-bs-toggle="tooltip"
                                                        title="Khôi phục">
                                                        <i class="fas fa-undo"></i>
                                                    </button>
                                                    <button type="button" class="btn btn-sm btn-outline-danger force-delete-btn"
                                                        data-product-id="{{ $product->id }}" data-bs-toggle="tooltip"
                                                        title="Xóa vĩnh viễn">
                                                        <i class="fas fa-trash-alt"></i>
                                                    </button>
                                                </div>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="8" class="text-center py-4">
                                                <div class="text-muted">
                                                    <i class="fas fa-trash fa-2x mb-3"></i>
                                                    <p class="mb-0">Thùng rác trống.</p>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>

                        <div class="mt-4">
                            {{ $products->links('pagination::bootstrap-5') }}
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

        .img-thumbnail {
            border-radius: 0.25rem;
            border: 1px solid #dee2e6;
            background: #fff;
        }
    </style>
@endpush