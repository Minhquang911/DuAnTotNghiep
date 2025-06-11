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
                                <i class="fas fa-book me-2"></i>Quản lý sản phẩm sách
                            </h3>
                            <div>
                                <a href="{{ route('admin.products.trashed') }}" class="btn btn-light btn-sm me-2">
                                    <i class="fas fa-trash me-1"></i> Thùng rác
                                </a>
                                <a href="{{ route('admin.products.create') }}" class="btn btn-light btn-sm">
                                    <i class="fas fa-plus me-1"></i> Thêm sách mới
                                </a>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('admin.products.index') }}" method="GET" class="mb-4">
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
                                    <select name="status" class="form-select">
                                        <option value="">-- Trạng thái --</option>
                                        <option value="active" {{ request('status') === 'active' ? 'selected' : '' }}>Đang
                                            bán</option>
                                        <option value="inactive" {{ request('status') === 'inactive' ? 'selected' : '' }}>
                                            Ngừng bán</option>
                                        <option value="hot" {{ request('status') === 'hot' ? 'selected' : '' }}>Hot
                                        </option>
                                        <option value="new" {{ request('status') === 'new' ? 'selected' : '' }}>Mới
                                        </option>
                                        <option value="best_seller"
                                            {{ request('status') === 'best_seller' ? 'selected' : '' }}>Bán chạy</option>
                                        <option value="recommended"
                                            {{ request('status') === 'recommended' ? 'selected' : '' }}>Đề xuất</option>
                                        <option value="featured" {{ request('status') === 'featured' ? 'selected' : '' }}>
                                            Nổi bật</option>
                                        <option value="promotion"
                                            {{ request('status') === 'promotion' ? 'selected' : '' }}>Khuyến mãi</option>
                                    </select>
                                </div>
                                <div class="col-md-3 d-flex justify-content-end">
                                    <button type="submit" class="btn btn-primary w-100">
                                        <i class="fas fa-search me-1"></i> Tìm kiếm
                                    </button>
                                    <a href="{{ route('admin.products.index') }}" class="btn btn-secondary ms-2 w-100">
                                        <i class="fas fa-redo me-1"></i> Làm mới
                                    </a>
                                </div>
                            </div>
                            <div class="row g-3 mt-2">
                                <div class="col-md-2">
                                    <select name="format_id" class="form-select">
                                        <option value="">-- Định dạng --</option>
                                        @foreach ($formats as $format)
                                            <option value="{{ $format->id }}"
                                                {{ request('format_id') == $format->id ? 'selected' : '' }}>
                                                {{ $format->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-2">
                                    <select name="language_id" class="form-select">
                                        <option value="">-- Ngôn ngữ --</option>
                                        @foreach ($languages as $lang)
                                            <option value="{{ $lang->id }}"
                                                {{ request('language_id') == $lang->id ? 'selected' : '' }}>
                                                {{ $lang->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-2">
                                    <input type="number" name="min_price" class="form-control" placeholder="Giá từ"
                                        value="{{ request('min_price') }}">
                                </div>
                                <div class="col-md-2">
                                    <input type="number" name="max_price" class="form-control" placeholder="Giá đến"
                                        value="{{ request('max_price') }}">
                                </div>
                                <div class="col-md-2">
                                    <select name="sort" class="form-select">
                                        <option value="latest" {{ request('sort') === 'latest' ? 'selected' : '' }}>Mới
                                            nhất</option>
                                        <option value="oldest" {{ request('sort') === 'oldest' ? 'selected' : '' }}>Cũ nhất
                                        </option>
                                        <option value="title_asc" {{ request('sort') === 'title_asc' ? 'selected' : '' }}>
                                            Tên A-Z</option>
                                        <option value="title_desc"
                                            {{ request('sort') === 'title_desc' ? 'selected' : '' }}>Tên Z-A</option>
                                        <option value="author_asc"
                                            {{ request('sort') === 'author_asc' ? 'selected' : '' }}>Tác giả A-Z</option>
                                        <option value="author_desc"
                                            {{ request('sort') === 'author_desc' ? 'selected' : '' }}>Tác giả Z-A</option>
                                        <option value="price_asc" {{ request('sort') === 'price_asc' ? 'selected' : '' }}>
                                            Giá tăng dần</option>
                                        <option value="price_desc"
                                            {{ request('sort') === 'price_desc' ? 'selected' : '' }}>Giá giảm dần</option>
                                    </select>
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
                                        <th>Giá</th>
                                        <th>Thông số</th>
                                        <th class="text-center" style="width: 100px">Trạng thái</th>
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
                                                    <img src="{{ asset('auth/img/book_defaut.png') }}"
                                                        class="img-thumbnail me-2"
                                                        style="width: 40px; height: 60px; object-fit: cover;">
                                                @endif
                                            </td>
                                            <td>
                                                <div class="fw-bold">{{ $product->title }}</div>
                                                <div class="text-muted small">ISBN: {{ $product->isbn }}</div>
                                            </td>
                                            <td>{{ $product->author }}</td>
                                            <td>{{ $product->category ? $product->category->name : '-' }}</td>
                                            <td>{{ $product->publisher ? $product->publisher->name : '-' }}</td>
                                            <td>
                                                @if ($product->min_price && $product->max_price && $product->min_price != $product->max_price)
                                                    <span
                                                        class="text-primary fw-bold">{{ number_format($product->min_price) }}₫
                                                        - {{ number_format($product->max_price) }}₫</span>
                                                @elseif($product->min_price)
                                                    <span
                                                        class="text-primary fw-bold">{{ number_format($product->min_price) }}₫</span>
                                                @else
                                                    <span class="text-muted">-</span>
                                                @endif
                                            </td>
                                            <td>
                                                @php
                                                    $specs = [];
                                                    if ($product->length_cm && $product->width_cm) {
                                                        $specs[] =
                                                            rtrim(
                                                                rtrim(number_format($product->length_cm, 2), '0'),
                                                                '.',
                                                            ) .
                                                            ' x ' .
                                                            rtrim(
                                                                rtrim(number_format($product->width_cm, 2), '0'),
                                                                '.',
                                                            ) .
                                                            ' cm';
                                                    }
                                                    if ($product->weight_g) {
                                                        $specs[] =
                                                            'Trọng lượng: ' . number_format($product->weight_g) . 'g';
                                                    }
                                                    if ($product->page_count) {
                                                        $specs[] = number_format($product->page_count) . ' trang';
                                                    }
                                                @endphp
                                                @if (count($specs))
                                                    {{ implode(' | ', $specs) }}
                                                @else
                                                    <span class="text-muted">-</span>
                                                @endif
                                            </td>
                                            <td class="text-center">
                                                <div class="form-check form-switch d-inline-block">
                                                    <input class="form-check-input status-toggle" type="checkbox"
                                                        data-product-id="{{ $product->id }}"
                                                        {{ $product->is_active ? 'checked' : '' }}
                                                        data-bs-toggle="tooltip"
                                                        title="{{ $product->is_active ? 'Đang bán' : 'Ngừng bán' }}">
                                                </div>
                                            </td>
                                            <td class="text-center">
                                                <div class="btn-group">
                                                    <a href="{{ route('admin.products.show', $product->id) }}"
                                                        class="btn btn-sm btn-outline-info" data-bs-toggle="tooltip"
                                                        title="Xem chi tiết">
                                                        <i class="fas fa-eye"></i>
                                                    </a>
                                                    <a href="{{ route('admin.products.edit', $product->id) }}"
                                                        class="btn btn-sm btn-outline-primary" data-bs-toggle="tooltip"
                                                        title="Chỉnh sửa">
                                                        <i class="fas fa-edit"></i>
                                                    </a>
                                                    <form action="{{ route('admin.products.destroy', $product->id) }}"
                                                        method="POST" class="d-inline delete-form">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit"
                                                            class="btn btn-sm btn-outline-danger delete-btn"
                                                            data-bs-toggle="tooltip" title="Xóa">
                                                            <i class="fas fa-trash"></i>
                                                        </button>
                                                    </form>
                                                </div>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="9" class="text-center py-4">
                                                <div class="text-muted">
                                                    <i class="fas fa-book fa-2x mb-3"></i>
                                                    <p class="mb-0">Không có sản phẩm nào.</p>
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

        .img-thumbnail {
            border-radius: 0.25rem;
            border: 1px solid #dee2e6;
            background: #fff;
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
                const productId = $(this).data('product-id');
                const isActive = $(this).prop('checked') ? 1 : 0;
                const $toggle = $(this);

                $toggle.prop('disabled', true);

                $.ajax({
                    url: `/admin/products/${productId}/toggle-status`,
                    method: 'POST',
                    data: {
                        _token: '{{ csrf_token() }}',
                        is_active: isActive ? 1 : 0
                    },
                    success: function(response) {
                        if (response.success) {
                            $toggle.attr('title', isActive ? 'Đang bán' : 'Ngừng bán');
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

            // Xử lý xóa sản phẩm
            $('.delete-form').on('submit', function(e) {
                e.preventDefault();
                const form = $(this);

                if (confirm('Bạn có chắc chắn muốn xóa sản phẩm này?')) {
                    $.ajax({
                        url: form.attr('action'),
                        method: 'POST',
                        data: form.serialize(),
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest'
                        },
                        success: function(response) {
                            if (response.success) {
                                toastr.success(response.message);
                                setTimeout(() => {
                                    window.location.reload();
                                }, 1000);
                            } else {
                                toastr.error(response.message);
                            }
                        },
                        error: function(xhr) {
                            if (xhr.status === 419) {
                                toastr.error(
                                    'Phiên làm việc đã hết hạn. Vui lòng tải lại trang và thử lại.'
                                    );
                            } else if (xhr.responseJSON && xhr.responseJSON.message) {
                                toastr.error(xhr.responseJSON.message);
                            } else {
                                toastr.error('Có lỗi xảy ra khi xóa sản phẩm');
                            }
                        }
                    });
                }
            });
        });
    </script>
@endpush