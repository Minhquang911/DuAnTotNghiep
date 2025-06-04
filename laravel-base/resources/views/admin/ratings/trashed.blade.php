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
                                <i class="fas fa-trash-alt me-2"></i>Đánh giá đã xóa
                            </h3>

                            <a href="{{ route('admin.ratings.index') }}" class="btn btn-light btn-sm">
                                <i class="fas fa-arrow-left me-1"></i> Quay lại danh sách
                            </a>
                        </div>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('admin.ratings.trashed') }}" method="GET" class="mb-4">
                            <div class="row g-3">
                                <div class="col-md-3">
                                    <div class="input-group">
                                        <span class="input-group-text bg-light">
                                            <i class="fas fa-search text-muted"></i>
                                        </span>
                                        <input type="text" name="comment" class="form-control"
                                            placeholder="Tìm kiếm nội dung..." value="{{ request('comment') }}">
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
                                    <select name="rating" class="form-select">
                                        <option value="">-- Số sao --</option>
                                        <option value="5" {{ request('rating') == 5 ? 'selected' : '' }}>5 sao</option>
                                        <option value="4" {{ request('rating') == 4 ? 'selected' : '' }}>4 sao</option>
                                        <option value="3" {{ request('rating') == 3 ? 'selected' : '' }}>3 sao</option>
                                        <option value="2" {{ request('rating') == 2 ? 'selected' : '' }}>2 sao</option>
                                        <option value="1" {{ request('rating') == 1 ? 'selected' : '' }}>1 sao</option>
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
                                        <option value="10" {{ request('per_page', 10) == 10 ? 'selected' : '' }}>10 đánh giá</option>
                                        <option value="20" {{ request('per_page', 10) == 20 ? 'selected' : '' }}>20 đánh giá</option>
                                        <option value="50" {{ request('per_page', 10) == 50 ? 'selected' : '' }}>50 đánh giá</option>
                                        <option value="100" {{ request('per_page', 10) == 100 ? 'selected' : '' }}>100 đánh giá</option>
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
                                        <th class="text-center" style="width: 100px">Số sao</th>
                                        <th class="text-center" style="width: 150px">Thời gian xóa</th>
                                        <th class="text-center" style="width: 150px">Thao tác</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($trashedRatings as $rating)
                                        <tr>
                                            <td class="text-center">{{ $loop->iteration }}</td>
                                            <td>
                                                <div class="fw-bold">{{ Str::limit($rating->comment, 100) }}</div>
                                                <small class="text-muted">
                                                    <i class="far fa-clock me-1"></i>
                                                    {{ $rating->created_at->format('d/m/Y H:i') }}
                                                </small>
                                                @if ($rating->reply)
                                                    <div class="mt-1 text-info">
                                                        <i class="fas fa-reply me-1"></i>
                                                        <small>{{ Str::limit($rating->reply, 100) }}</small>
                                                        <small class="text-muted d-block">
                                                            <i class="far fa-clock me-1"></i>
                                                            {{ $rating->reply_at->format('d/m/Y H:i') }}
                                                        </small>
                                                    </div>
                                                @endif
                                            </td>
                                            <td>
                                                <a href="{{ route('admin.product-variants.show', $rating->product_variant_id) }}"
                                                    class="text-decoration-none">
                                                    {{ $rating->productVariant->full_name }}
                                                </a>
                                            </td>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    <img src="{{ $rating->user->avatar ?? asset('images/default-avatar.png') }}"
                                                        class="rounded-circle me-2" width="32" height="32"
                                                        alt="{{ $rating->user->name }}">
                                                    <div>
                                                        <div class="fw-bold">{{ $rating->user->name }}</div>
                                                        <small class="text-muted">{{ $rating->user->email }}</small>
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="text-center">
                                                <div class="d-flex justify-content-center">
                                                    @for ($i = 1; $i <= 5; $i++)
                                                        <i class="fas fa-star {{ $i <= $rating->rating ? 'text-warning' : 'text-muted' }}"></i>
                                                    @endfor
                                                </div>
                                            </td>
                                            <td class="text-center">
                                                <small class="text-muted">
                                                    <i class="far fa-clock me-1"></i>
                                                    {{ $rating->deleted_at->format('d/m/Y') }}
                                                </small>
                                            </td>
                                            <td class="text-center">
                                                <div class="btn-group">
                                                    <button type="button" class="btn btn-sm btn-outline-success restore-btn"
                                                        data-rating-id="{{ $rating->id }}" data-bs-toggle="tooltip"
                                                        title="Khôi phục">
                                                        <i class="fas fa-undo"></i>
                                                    </button>
                                                    <button type="button" class="btn btn-sm btn-outline-danger force-delete-btn"
                                                        data-rating-id="{{ $rating->id }}" data-bs-toggle="tooltip"
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
                                                    <p class="mb-0">Không có đánh giá nào đã xóa.</p>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>

                        <div class="mt-4">
                            {{ $trashedRatings->appends(request()->query())->links('pagination::bootstrap-5') }}
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

@push('scripts')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
    <script>
        $(document).ready(function() {
            // Khởi tạo tooltips
            var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
            var tooltipList = tooltipTriggerList.map(function(tooltipTriggerEl) {
                return new bootstrap.Tooltip(tooltipTriggerEl)
            });

            // Xử lý khôi phục đánh giá
            $('.restore-btn').click(function() {
                const ratingId = $(this).data('rating-id');
                const $btn = $(this);

                if (confirm('Bạn có chắc chắn muốn khôi phục đánh giá này?')) {
                    $btn.prop('disabled', true);

                    $.ajax({
                        url: `/admin/ratings/${ratingId}/restore`,
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
                            toastr.error('Có lỗi xảy ra khi khôi phục đánh giá');
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