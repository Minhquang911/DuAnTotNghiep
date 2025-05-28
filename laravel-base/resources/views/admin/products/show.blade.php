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
                        <i class="fas fa-book me-2"></i>Chi tiết sách: {{ $product->title }}
                    </h3>
                    <a href="{{ route('admin.products.edit', $product->id) }}" class="btn btn-warning btn-sm">
                        <i class="fas fa-edit me-1"></i> Sửa sách
                    </a>
                </div>
            </div>
            <div class="card-body">
                <div class="row mb-4">
                    <div class="col-md-3 text-center">
                        @if ($product->cover_image_url)
                            <img src="{{ $product->cover_image_url }}" class="product-cover mb-2" width="200"
                                alt="Ảnh bìa">
                        @else
                            <div class="text-muted border rounded p-4 bg-light">Không có ảnh bìa</div>
                        @endif
                        <div class="mt-2">
                            <span class="badge bg-{{ $product->is_active ? 'success' : 'secondary' }}">
                                {{ $product->is_active ? 'Đang bán' : 'Ngừng bán' }}
                            </span>
                        </div>
                    </div>
                    <div class="col-md-9">
                        <div class="row mb-2">
                            <div class="col-sm-4 product-attr-label">Tên sách:</div>
                            <div class="col-sm-8 fw-bold fs-5">{{ $product->title }}</div>
                        </div>
                        <div class="row mb-2">
                            <div class="col-sm-4 product-attr-label">Tác giả:</div>
                            <div class="col-sm-8">{{ $product->author }}</div>
                        </div>
                        <div class="row mb-2">
                            <div class="col-sm-4 product-attr-label">Danh mục:</div>
                            <div class="col-sm-8">{{ $product->category->name ?? '-' }}</div>
                        </div>
                        <div class="row mb-2">
                            <div class="col-sm-4 product-attr-label">Nhà xuất bản:</div>
                            <div class="col-sm-8">{{ $product->publisher->name ?? '-' }}</div>
                        </div>
                        <div class="row mb-2">
                            <div class="col-sm-4 product-attr-label">ISBN:</div>
                            <div class="col-sm-8">{{ $product->isbn }}</div>
                        </div>
                        <div class="row mb-2">
                            <div class="col-sm-4 product-attr-label">Ngày xuất bản:</div>
                            <div class="col-sm-8">
                                {{ $product->published_at ? $product->published_at->format('d/m/Y') : '-' }}</div>
                        </div>
                        <div class="row mb-2">
                            <div class="col-sm-4 product-attr-label">Lượt xem:</div>
                            <div class="col-sm-8">{{ number_format($product->view_count) }}</div>
                        </div>
                        <div class="row mb-2">
                            <div class="col-sm-4 product-attr-label">Lượt mua:</div>
                            <div class="col-sm-8">{{ number_format($product->order_count) }}</div>
                        </div>
                        <div class="row mb-2">
                            <div class="col-sm-4 product-attr-label">Mô tả:</div>
                            <div class="col-sm-8">{!! nl2br(e($product->description)) !!}</div>
                        </div>
                        <div class="row mb-2">
                            <div class="col-sm-4 product-attr-label">Thông số:</div>
                            <div class="col-sm-8">
                                @php
                                    $specs = [];
                                    if ($product->length_cm && $product->width_cm) {
                                        $specs[] =
                                            rtrim(rtrim(number_format($product->length_cm, 2), '0'), '.') .
                                            ' x ' .
                                            rtrim(rtrim(number_format($product->width_cm, 2), '0'), '.') .
                                            ' cm';
                                    }
                                    if ($product->weight_g) {
                                        $specs[] = 'Trọng lượng: ' . number_format($product->weight_g) . 'g';
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
                            </div>
                        </div>
                        <div class="row mb-2">
                            <div class="col-sm-4 product-attr-label">Trạng thái khác:</div>
                            <div class="col-sm-8 product-badges">
                                @if ($product->is_hot)
                                    <span class="badge bg-danger"><i class="fas fa-fire"></i> Hot</span>
                                @endif
                                @if ($product->is_new)
                                    <span class="badge bg-info text-white"><i class="fas fa-star"></i> Mới</span>
                                @endif
                                @if ($product->is_best_seller)
                                    <span class="badge bg-warning text-dark"><i class="fas fa-trophy"></i> Bán chạy</span>
                                @endif
                                @if ($product->is_recommended)
                                    <span class="badge bg-primary"><i class="fas fa-thumbs-up"></i> Đề xuất</span>
                                @endif
                                @if ($product->is_featured)
                                    <span class="badge bg-success"><i class="fas fa-bolt"></i> Nổi bật</span>
                                @endif
                                @if ($product->is_promotion)
                                    <span class="badge bg-primary"><i class="fas fa-tags"></i> Khuyến mãi</span>
                                @endif
                                @if (
                                    !$product->is_hot &&
                                        !$product->is_new &&
                                        !$product->is_best_seller &&
                                        !$product->is_recommended &&
                                        !$product->is_featured &&
                                        !$product->is_promotion)
                                    <span class="text-muted">Không có</span>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
                <h5 class="mt-4 mb-3"><i class="fas fa-layer-group me-1"></i>Biến thể sách</h5>
                <div class="table-responsive">
                    <table class="table table-bordered align-middle table-variants">
                        <thead class="table-light">
                            <tr>
                                <th>Định dạng</th>
                                <th>Ngôn ngữ</th>
                                <th>SKU</th>
                                <th>Giá</th>
                                <th>Giá KM</th>
                                <th>Tồn kho</th>
                                <th>Trạng thái</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($product->variants as $variant)
                                <tr>
                                    <td>{{ $variant->format->name ?? '-' }}</td>
                                    <td>{{ $variant->language->name ?? '-' }}</td>
                                    <td>{{ $variant->sku }}</td>
                                    <td>{{ number_format($variant->price) }}₫</td>
                                    <td>
                                        @if ($variant->promotion_price)
                                            <span
                                                class="text-danger">{{ number_format($variant->promotion_price) }}₫</span>
                                        @else
                                            <span class="text-muted">-</span>
                                        @endif
                                    </td>
                                    <td>{{ $variant->stock }}</td>
                                    <td>
                                        @if ($variant->is_active)
                                            <span class="badge bg-success">Đang bán</span>
                                        @else
                                            <span class="badge bg-secondary">Ngừng bán</span>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <a href="{{ route('admin.products.index') }}" class="btn btn-secondary mt-3">
                    <i class="fas fa-arrow-left me-1"></i> Quay lại danh sách
                </a>

                <!-- Phần bình luận -->
                <div class="mt-5">
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <h5 class="mb-0">
                            <i class="fas fa-comments me-2"></i>Bình luận sách
                        </h5>
                        <div class="d-flex gap-2">
                            <a href="{{ route('admin.comments.index', ['product_id' => $product->id]) }}"
                                class="btn btn-primary btn-sm">
                                <i class="fas fa-list me-1"></i> Quản lý bình luận
                            </a>
                        </div>
                    </div>

                    <!-- Thống kê bình luận -->
                    <div class="row mb-4">
                        @php
                            $approvedComments = $product->comments()->where('is_approved', true)->count();
                            $pendingComments = $product->comments()->where('is_approved', false)->count();
                            $totalComments = $product->comments()->count();
                            $parentComments = $product->comments()->whereNull('parent_id')->count();
                        @endphp
                        <div class="col-md-3">
                            <div class="card bg-primary text-white">
                                <div class="card-body py-3">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div>
                                            <h6 class="card-title mb-0">Tổng số bình luận</h6>
                                            <h2 class="mt-2 mb-0">{{ $totalComments }}</h2>
                                        </div>
                                        <i class="fas fa-comments fa-2x opacity-50"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="card bg-success text-white">
                                <div class="card-body py-3">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div>
                                            <h6 class="card-title mb-0">Hiển thị</h6>
                                            <h2 class="mt-2 mb-0">{{ $approvedComments }}</h2>
                                        </div>
                                        <i class="fas fa-check-circle fa-2x opacity-50"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="card bg-warning text-white">
                                <div class="card-body py-3">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div>
                                            <h6 class="card-title mb-0">Ẩn</h6>
                                            <h2 class="mt-2 mb-0">{{ $pendingComments }}</h2>
                                        </div>
                                        <i class="fas fa-clock fa-2x opacity-50"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="card bg-info text-white">
                                <div class="card-body py-3">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div>
                                            <h6 class="card-title mb-0">Bình luận cha</h6>
                                            <h2 class="mt-2 mb-0">{{ $parentComments }}</h2>
                                        </div>
                                        <i class="fas fa-reply fa-2x opacity-50"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Danh sách bình luận -->
                    <div class="card">
                        <div class="card-header bg-light">
                            <div class="d-flex justify-content-between align-items-center">
                                <div class="d-flex align-items-center gap-2">
                                    <span class="text-muted">Hiển thị:</span>
                                    <select class="form-select form-select-sm" style="width: auto" id="perPageSelect">
                                        <option value="5" {{ request('per_page', 5) == 5 ? 'selected' : '' }}>5 bình
                                            luận</option>
                                        <option value="10" {{ request('per_page', 5) == 10 ? 'selected' : '' }}>10
                                            bình luận</option>
                                        <option value="20" {{ request('per_page', 5) == 20 ? 'selected' : '' }}>20
                                            bình luận</option>
                                        <option value="50" {{ request('per_page', 5) == 50 ? 'selected' : '' }}>50
                                            bình luận</option>
                                    </select>
                                </div>
                                <div class="d-flex align-items-center gap-2">
                                    <span class="text-muted">Sắp xếp:</span>
                                    <select class="form-select form-select-sm" style="width: auto" id="sortSelect">
                                        <option value="latest"
                                            {{ request('sort', 'latest') == 'latest' ? 'selected' : '' }}>Mới nhất</option>
                                        <option value="oldest"
                                            {{ request('sort', 'latest') == 'oldest' ? 'selected' : '' }}>Cũ nhất</option>
                                        <option value="most_replies"
                                            {{ request('sort', 'latest') == 'most_replies' ? 'selected' : '' }}>Nhiều trả
                                            lời nhất</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="card-body">
                            @php
                                $perPage = request('per_page', 5);
                                $sort = request('sort', 'latest');

                                $query = $product
                                    ->comments()
                                    ->with(['user', 'replies.user'])
                                    ->whereNull('parent_id');

                                switch ($sort) {
                                    case 'oldest':
                                        $query->orderBy('created_at', 'asc');
                                        break;
                                    case 'most_replies':
                                        $query
                                            ->withCount('replies')
                                            ->orderBy('replies_count', 'desc')
                                            ->orderBy('created_at', 'desc');
                                        break;
                                    default:
                                        // latest
                                        $query->orderBy('created_at', 'desc');
                                }

                                $parentComments = $query->paginate($perPage);
                            @endphp

                            @forelse($parentComments as $parentComment)
                                <div class="comment-item mb-4 {{ !$loop->last ? 'border-bottom pb-4' : '' }}">
                                    <!-- Bình luận cha -->
                                    <div class="d-flex">
                                        <div class="flex-shrink-0">
                                            <img src="{{ $parentComment->user->avatar ?? asset('images/default-avatar.png') }}"
                                                class="rounded-circle" width="48" height="48"
                                                alt="{{ $parentComment->user->name }}">
                                        </div>
                                        <div class="flex-grow-1 ms-3">
                                            <div class="d-flex justify-content-between align-items-start">
                                                <div>
                                                    <h6 class="mb-1">
                                                        {{ $parentComment->user->name }}
                                                        @if ($parentComment->is_approved)
                                                            <span class="badge bg-success ms-2">Hiển thị</span>
                                                        @else
                                                            <span class="badge bg-warning ms-2">Ẩn</span>
                                                        @endif
                                                        @if ($parentComment->replies_count > 0)
                                                            <span class="badge bg-info ms-2">
                                                                <i
                                                                    class="fas fa-reply me-1"></i>{{ $parentComment->replies_count }}
                                                            </span>
                                                        @endif
                                                    </h6>
                                                    <small class="text-muted">
                                                        <i class="far fa-clock me-1"></i>
                                                        {{ $parentComment->created_at->format('d/m/Y H:i') }}
                                                    </small>
                                                </div>
                                                <div class="btn-group">
                                                    @if (!$parentComment->is_approved)
                                                        <button type="button"
                                                            class="btn btn-sm btn-outline-success approve-btn"
                                                            data-comment-id="{{ $parentComment->id }}"
                                                            data-bs-toggle="tooltip" title="Hiển thị">
                                                            <i class="fas fa-check"></i>
                                                        </button>
                                                    @else
                                                        <button type="button"
                                                            class="btn btn-sm btn-outline-warning reject-btn"
                                                            data-comment-id="{{ $parentComment->id }}"
                                                            data-bs-toggle="tooltip" title="Ẩn">
                                                            <i class="fas fa-times"></i>
                                                        </button>
                                                    @endif
                                                    <button type="button"
                                                        class="btn btn-sm btn-outline-danger delete-btn"
                                                        data-comment-id="{{ $parentComment->id }}"
                                                        data-bs-toggle="tooltip" title="Xóa">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                </div>
                                            </div>
                                            <p class="mt-2 mb-2">{{ $parentComment->content }}</p>

                                            <!-- Nút hiển thị/ẩn trả lời -->
                                            @if ($parentComment->replies->count() > 0)
                                                <button
                                                    class="btn btn-link btn-sm p-0 text-decoration-none toggle-replies collapsed"
                                                    data-bs-toggle="collapse"
                                                    data-bs-target="#replies-{{ $parentComment->id }}">
                                                    <i class="fas fa-chevron-down me-1"></i>
                                                    <span class="show-text">Hiển thị
                                                        {{ $parentComment->replies->count() }} trả lời</span>
                                                    <span class="hide-text d-none">Ẩn trả lời</span>
                                                </button>
                                            @endif
                                        </div>
                                    </div>

                                    <!-- Các bình luận con -->
                                    @if ($parentComment->replies->count() > 0)
                                        <div class="replies ms-5 mt-3 collapse" id="replies-{{ $parentComment->id }}">
                                            @foreach ($parentComment->replies as $reply)
                                                <div
                                                    class="reply-item mb-3 {{ !$loop->last ? 'border-bottom pb-3' : '' }}">
                                                    <div class="d-flex">
                                                        <div class="flex-shrink-0">
                                                            <img src="{{ $reply->user->avatar ?? asset('images/default-avatar.png') }}"
                                                                class="rounded-circle" width="40" height="40"
                                                                alt="{{ $reply->user->name }}">
                                                        </div>
                                                        <div class="flex-grow-1 ms-3">
                                                            <div class="d-flex justify-content-between align-items-start">
                                                                <div>
                                                                    <h6 class="mb-1">
                                                                        {{ $reply->user->name }}
                                                                        @if ($reply->is_approved)
                                                                            <span class="badge bg-success ms-2">Hiển
                                                                                thị</span>
                                                                        @else
                                                                            <span class="badge bg-warning ms-2">Ẩn</span>
                                                                        @endif
                                                                    </h6>
                                                                    <small class="text-muted">
                                                                        <i class="far fa-clock me-1"></i>
                                                                        {{ $reply->created_at->format('d/m/Y H:i') }}
                                                                    </small>
                                                                </div>
                                                                <div class="btn-group">
                                                                    @if (!$reply->is_approved)
                                                                        <button type="button"
                                                                            class="btn btn-sm btn-outline-success approve-btn"
                                                                            data-comment-id="{{ $reply->id }}"
                                                                            data-bs-toggle="tooltip" title="Hiển thị">
                                                                            <i class="fas fa-check"></i>
                                                                        </button>
                                                                    @else
                                                                        <button type="button"
                                                                            class="btn btn-sm btn-outline-warning reject-btn"
                                                                            data-comment-id="{{ $reply->id }}"
                                                                            data-bs-toggle="tooltip" title="Ẩn">
                                                                            <i class="fas fa-times"></i>
                                                                        </button>
                                                                    @endif
                                                                    <button type="button"
                                                                        class="btn btn-sm btn-outline-danger delete-btn"
                                                                        data-comment-id="{{ $reply->id }}"
                                                                        data-bs-toggle="tooltip" title="Xóa">
                                                                        <i class="fas fa-trash"></i>
                                                                    </button>
                                                                </div>
                                                            </div>
                                                            <p class="mt-2 mb-0">{{ $reply->content }}</p>
                                                        </div>
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>
                                    @endif
                                </div>
                            @empty
                                <div class="text-center py-4">
                                    <div class="text-muted">
                                        <i class="fas fa-comments fa-2x mb-3"></i>
                                        <p class="mb-0">Chưa có bình luận nào.</p>
                                    </div>
                                </div>
                            @endforelse

                            <!-- Phân trang -->
                            @if ($parentComments->hasPages())
                                <div
                                    class="d-flex flex-column flex-md-row justify-content-between align-items-center mt-4 gap-3">
                                    <div class="text-muted small">
                                        <i class="fas fa-info-circle me-1"></i>
                                        Hiển thị {{ $parentComments->firstItem() ?? 0 }} -
                                        {{ $parentComments->lastItem() ?? 0 }}
                                        của {{ $parentComments->total() }} bình luận
                                        ({{ ceil($parentComments->total() / $perPage) }} trang)
                                    </div>
                                    <nav aria-label="Phân trang bình luận">
                                        {{ $parentComments->appends(request()->query())->links('pagination::bootstrap-5') }}
                                    </nav>
                                </div>
                            @endif
                        </div>
                    </div>

                    <!-- Danh sách đánh giá -->
                    <div class="mt-5">
                        <div class="d-flex justify-content-between align-items-center mb-4">
                            <h5 class="mb-0">
                                <i class="fas fa-star me-2"></i>Đánh giá sách
                            </h5>
                            <div class="d-flex gap-2">
                                <a href="{{ route('admin.ratings.index', ['product_id' => $product->id]) }}"
                                    class="btn btn-primary btn-sm">
                                    <i class="fas fa-list me-1"></i> Quản lý đánh giá
                                </a>
                            </div>
                        </div>
                    </div>

                    <!-- Thống kê đánh giá-->
                    <div class="row mb-4">
                        @php
                            $totalRatings = $product->ratings()->count();
                            $approvedRatings = $product->ratings()->where('is_approved', true)->count();
                            $pendingRatings = $product->ratings()->where('is_approved', false)->count();
                        @endphp
                        <div class="col-md-4">
                            <div class="card bg-primary text-white">
                                <div class="card-body py-3">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div>
                                            <h6 class="card-title mb-0">Tổng số đánh giá</h6>
                                            <h2 class="mt-2 mb-0">{{ $totalRatings }}</h2>
                                        </div>
                                        <i class="fas fa-star fa-2x opacity-50"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="card bg-success text-white">
                                <div class="card-body py-3">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div>
                                            <h6 class="card-title mb-0">Hiển thị</h6>
                                            <h2 class="mt-2 mb-0">{{ $approvedRatings }}</h2>
                                        </div>
                                        <i class="fas fa-check-circle fa-2x opacity-50"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="card bg-warning text-white">
                                <div class="card-body py-3">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div>
                                            <h6 class="card-title mb-0">Ẩn</h6>
                                            <h2 class="mt-2 mb-0">{{ $pendingRatings }}</h2>
                                        </div>
                                        <i class="fas fa-times-circle fa-2x opacity-50"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>


                    <div class="card">
                        <div class="card-header bg-light">
                            <div class="d-flex justify-content-between align-items-center">
                                <div class="d-flex align-items-center gap-2">
                                    <span class="text-muted">Hiển thị:</span>
                                    <select class="form-select form-select-sm" style="width: auto"
                                        id="perPageSelectRating">
                                        <option value="5" {{ request('per_page_rating', 5) == 5 ? 'selected' : '' }}>
                                            5
                                            đánh
                                            giá</option>
                                        <option value="10"
                                            {{ request('per_page_rating', 5) == 10 ? 'selected' : '' }}>10
                                            đánh giá</option>
                                        <option value="20"
                                            {{ request('per_page_rating', 5) == 20 ? 'selected' : '' }}>20
                                            đánh giá</option>
                                        <option value="50"
                                            {{ request('per_page_rating', 5) == 50 ? 'selected' : '' }}>50
                                            đánh giá</option>
                                    </select>
                                </div>
                                <div class="d-flex align-items-center gap-2">
                                    <span class="text-muted">Sắp xếp:</span>
                                    <select class="form-select form-select-sm" style="width: auto" id="sortSelectRating">
                                        <option value="latest"
                                            {{ request('sort_rating', 'latest') == 'latest' ? 'selected' : '' }}>Mới
                                            nhất</option>
                                        <option value="oldest"
                                            {{ request('sort_rating', 'latest') == 'oldest' ? 'selected' : '' }}>Cũ
                                            nhất</option>
                                        <option value="reply"
                                            {{ request('sort_rating', 'latest') == 'reply' ? 'selected' : '' }}>Chưa
                                            trả lời</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="card-body">
                            @php
                                $perPage = request('per_page_rating', 5);
                                $sort = request('sort_rating', 'latest');

                                $query = $product->ratings()->with(['user']);

                                switch ($sort) {
                                    case 'oldest':
                                        $query->orderBy('created_at', 'asc');
                                        break;
                                    case 'reply':
                                        $query->whereNull('reply');
                                        break;
                                    default:
                                        // latest
                                        $query->orderBy('created_at', 'desc');
                                }

                                $ratings = $query->paginate($perPage);
                            @endphp

                            @forelse($ratings as $rating)
                                <div class="rating-item mb-4 {{ !$loop->last ? 'border-bottom pb-4' : '' }}">
                                    <!-- Đánh giá -->
                                    <div class="d-flex">
                                        <div class="flex-shrink-0">
                                            <img src="{{ $rating->user->avatar ?? asset('images/default-avatar.png') }}"
                                                class="rounded-circle" width="48" height="48"
                                                alt="{{ $rating->user->name }}">
                                        </div>
                                        <div class="flex-grow-1 ms-3">
                                            <div class="d-flex justify-content-between align-items-start">
                                                <div>
                                                    <h6 class="mb-1">
                                                        {{ $rating->user->name }}
                                                        @if ($rating->is_approved)
                                                            <span class="badge bg-success ms-2">Hiển thị</span>
                                                        @else
                                                            <span class="badge bg-warning ms-2">Ẩn</span>
                                                        @endif
                                                        <div class="mt-1">
                                                            @for ($i = 1; $i <= 5; $i++)
                                                                <i
                                                                    class="fas fa-star {{ $i <= $rating->rating ? 'text-warning' : 'text-muted' }}"></i>
                                                            @endfor
                                                        </div>
                                                    </h6>
                                                    <small class="text-muted">
                                                        <i class="far fa-clock me-1"></i>
                                                        {{ $rating->created_at->format('d/m/Y H:i') }}
                                                    </small>
                                                </div>
                                                <div class="btn-group">
                                                    @if (!$rating->is_approved)
                                                        <button type="button"
                                                            class="btn btn-sm btn-outline-success approve-btn-rating"
                                                            data-rating-id="{{ $rating->id }}" data-bs-toggle="tooltip"
                                                            title="Hiển thị">
                                                            <i class="fas fa-check"></i>
                                                        </button>
                                                    @else
                                                        <button type="button"
                                                            class="btn btn-sm btn-outline-warning reject-btn-rating"
                                                            data-rating-id="{{ $rating->id }}" data-bs-toggle="tooltip"
                                                            title="Ẩn">
                                                            <i class="fas fa-times"></i>
                                                        </button>
                                                    @endif
                                                    <button type="button"
                                                        class="btn btn-sm btn-outline-danger delete-btn-rating"
                                                        data-rating-id="{{ $rating->id }}" data-bs-toggle="tooltip"
                                                        title="Xóa">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                </div>
                                            </div>
                                            <p class="mt-2 mb-2">{{ $rating->comment }}</p>

                                            <!-- Phần trả lời -->
                                            @if ($rating->reply)
                                                <div class="reply-section mt-3 bg-light p-3 rounded">
                                                    <div class="d-flex align-items-start">
                                                        <div class="flex-shrink-0">
                                                            <i class="fas fa-reply text-primary"></i>
                                                        </div>
                                                        <div class="flex-grow-1 ms-3">
                                                            <div class="d-flex justify-content-between">
                                                                <small class="text-muted">
                                                                    <i class="far fa-clock me-1"></i>
                                                                    {{ $rating->reply_at->format('d/m/Y H:i') }}
                                                                </small>
                                                            </div>
                                                            <p class="mb-0 mt-1">{{ $rating->reply }}</p>
                                                        </div>
                                                    </div>
                                                </div>
                                            @else
                                                <div class="mt-3">
                                                    <button type="button"
                                                        class="btn btn-sm btn-outline-primary reply-btn-rating"
                                                        data-rating-id="{{ $rating->id }}" data-bs-toggle="modal"
                                                        data-bs-target="#replyModalRating">
                                                        <i class="fas fa-reply me-1"></i>Trả lời
                                                    </button>
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            @empty
                                <div class="text-center py-4">
                                    <div class="text-muted">
                                        <i class="fas fa-star fa-2x mb-3"></i>
                                        <p class="mb-0">Chưa có đánh giá nào.</p>
                                    </div>
                                </div>
                            @endforelse

                            <!-- Phân trang -->
                            @if ($ratings->hasPages())
                                <div
                                    class="d-flex flex-column flex-md-row justify-content-between align-items-center mt-4 gap-3">
                                    <div class="text-muted small">
                                        <i class="fas fa-info-circle me-1"></i>
                                        Hiển thị {{ $ratings->firstItem() ?? 0 }} -
                                        {{ $ratings->lastItem() ?? 0 }}
                                        của {{ $ratings->total() }} đánh giá
                                        ({{ ceil($ratings->total() / $perPage) }} trang)
                                    </div>
                                    <nav aria-label="Phân trang đánh giá">
                                        {{ $ratings->appends(request()->query())->links('pagination::bootstrap-5') }}
                                    </nav>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal trả lời đánh giá -->
    <div class="modal fade" id="replyModalRating" tabindex="-1" aria-labelledby="replyModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="replyModalLabel">Trả lời đánh giá</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="replyForm">
                        <input type="hidden" name="rating_id" id="reply-rating-id">
                        <div class="mb-3">
                            <label for="reply-content" class="form-label">Nội dung trả lời</label>
                            <textarea class="form-control" id="reply-content" name="reply" rows="3" required></textarea>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Đóng</button>
                    <button type="button" class="btn btn-primary" id="submit-reply-btn-rating">Gửi trả lời</button>
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

            // Xử lý trả lời đánh giá
            $('.reply-btn-rating').click(function() {
                const ratingId = $(this).data('rating-id');
                $('#reply-rating-id').val(ratingId);
                $('#replyModal').modal('show');
            });

            // Xử lý gửi trả lời
            $('#submit-reply-btn-rating').click(function() {
                const ratingId = $('#reply-rating-id').val();
                const reply = $('#reply-content').val();
                if (!reply.trim()) {
                    toastr.error('Vui lòng nhập nội dung trả lời!');
                    return;
                }
                $(this).prop('disabled', true);
                $.ajax({
                    url: `/admin/ratings/${ratingId}/reply`,
                    method: 'POST',
                    data: {
                        _token: '{{ csrf_token() }}',
                        reply: reply
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
                        toastr.error('Có lỗi xảy ra khi gửi trả lời');
                    },
                    complete: function() {
                        $(this).prop('disabled', false);
                    }
                });
            });

            // Xử lý duyệt đánh giá
            $('.approve-btn-rating').click(function() {
                const ratingId = $(this).data('rating-id');
                const $btn = $(this);

                if (confirm('Bạn có chắc chắn muốn duyệt đánh giá này?')) {
                    $btn.prop('disabled', true);

                    $.ajax({
                        url: `/admin/ratings/${ratingId}/approve`,
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
                            toastr.error('Có lỗi xảy ra khi duyệt đánh giá');
                        },
                        complete: function() {
                            $btn.prop('disabled', false);
                        }
                    });
                }
            });

            // Xử lý từ chối đánh giá
            $('.reject-btn-rating').click(function() {
                const ratingId = $(this).data('rating-id');
                const $btn = $(this);

                if (confirm('Bạn có chắc chắn muốn từ chối đánh giá này?')) {
                    $btn.prop('disabled', true);

                    $.ajax({
                        url: `/admin/ratings/${ratingId}/reject`,
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
                            toastr.error('Có lỗi xảy ra khi từ chối đánh giá');
                        },
                        complete: function() {
                            $btn.prop('disabled', false);
                        }
                    });
                }
            });

            // Xử lý xóa đánh giá
            $('.delete-btn-rating').click(function() {
                const ratingId = $(this).data('rating-id');
                const $btn = $(this);

                if (confirm('Bạn có chắc chắn muốn xóa đánh giá này?')) {
                    $btn.prop('disabled', true);

                    $.ajax({
                        url: `/admin/ratings/${ratingId}`,
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
                            toastr.error('Có lỗi xảy ra khi xóa đánh giá');
                        },
                        complete: function() {
                            $btn.prop('disabled', false);
                        }
                    });
                }
            });
        });

        // Xử lý thay đổi số lượng hiển thị
        $('#perPageSelect').change(function() {
            const url = new URL(window.location.href);
            url.searchParams.set('per_page', $(this).val());
            window.location.href = url.toString();
        });

        // Xử lý thay đổi số lượng hiển thị đánh giá
        $('#perPageSelectRating').change(function() {
            const url = new URL(window.location.href);
            url.searchParams.set('per_page_rating', $(this).val());
            window.location.href = url.toString();
        });

        // Xử lý thay đổi sắp xếp
        $('#sortSelect').change(function() {
            const url = new URL(window.location.href);
            url.searchParams.set('sort', $(this).val());
            window.location.href = url.toString();
        });

        // Xử lý thay đổi sắp xếp đánh giá
        $('#sortSelectRating').change(function() {
            const url = new URL(window.location.href);
            url.searchParams.set('sort_rating', $(this).val());
            window.location.href = url.toString();
        });

        // Xử lý toggle replies
        $('.toggle-replies').click(function() {
        $(this).toggleClass('collapsed');
        });
    </script>
@endpush