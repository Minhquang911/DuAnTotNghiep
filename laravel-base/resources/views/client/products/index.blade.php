@extends('layouts.client.ClientLayout')

@section('title')
    {{ isset($title) ? $title . ' - Book360 Store WooCommerce' : 'Book360 Store WooCommerce' }}
@endsection

@section('banner')
    <div class="breadcrumb-wrapper">
        <div class="book1">
            <img src="{{ asset('client/img/hero/book1.png') }}" alt="book">
        </div>
        <div class="book2">
            <img src="{{ asset('client/img/hero/book2.png') }}" alt="book">
        </div>
        <div class="container">
            <div class="page-heading">
                <h1>Gian hàng sách</h1>
                <div class="page-header">
                    <ul class="breadcrumb-items wow fadeInUp" data-wow-delay=".3s">
                        <li>
                            <a href="{{ route('home') }}">
                                Trang chủ
                            </a>
                        </li>
                        <li>
                            <i class="fa-solid fa-chevron-right"></i>
                        </li>
                        <li>
                            Cửa hàng
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('content')
    <section class="shop-section fix section-padding">
        <div class="container">
            <div class="shop-default-wrapper">
                <div class="row">
                    <div class="col-12">
                        <div class="woocommerce-notices-wrapper wow fadeInUp" data-wow-delay=".3s">
                            <p>Hiển thị {{ $products->firstItem() ?? 0 }}-{{ $products->lastItem() ?? 0 }} trong tổng số
                                {{ $products->total() }} sản phẩm </p>
                            <div class="form-clt">
                                <form id="sort-form" method="GET" action="{{ route('client.products.index') }}">
                                    <div class="nice-select" tabindex="0" id="sort-select">
                                        <span class="current">
                                            @switch($sort)
                                                @case('price_high_to_low')
                                                    Theo giá cao -> thấp
                                                @break

                                                @case('price_low_to_high')
                                                    Theo giá thấp -> cao
                                                @break

                                                @case('latest')
                                                    Theo ngày xuất bản
                                                @break

                                                @default
                                                    Sắp xếp mặc định
                                            @endswitch
                                        </span>
                                        <ul class="list">
                                            <li data-value="default"
                                                class="option {{ $sort == 'default' ? 'selected focus' : '' }}">
                                                Sắp xếp mặc định
                                            </li>
                                            <li data-value="price_high_to_low"
                                                class="option {{ $sort == 'price_high_to_low' ? 'selected focus' : '' }}">
                                                Theo giá cao -> thấp
                                            </li>
                                            <li data-value="price_low_to_high"
                                                class="option {{ $sort == 'price_low_to_high' ? 'selected focus' : '' }}">
                                                Theo giá thấp -> cao
                                            </li>
                                            <li data-value="latest"
                                                class="option {{ $sort == 'latest' ? 'selected focus' : '' }}">
                                                Theo ngày xuất bản
                                            </li>
                                        </ul>
                                    </div>
                                    <input type="hidden" name="sort" id="sort-input" value="{{ $sort }}">
                                </form>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-3 col-lg-4 order-2 order-md-1 wow fadeInUp" data-wow-delay=".3s">
                        <div class="main-sidebar">
                            <div class="single-sidebar-widget">
                                <div class="wid-title">
                                    <h5>Tìm kiếm</h5>
                                </div>
                                <form action="{{ route('client.products.index') }}" method="GET"
                                    class="search-toggle-box">
                                    <div class="input-area search-container">
                                        <input class="search-input" type="text" name="search"
                                            value="{{ old('search', e(request('search'))) }}"
                                            placeholder="Tìm kiếm theo tên, tác giả, ISBN..."
                                            style="color: black; background: white;">
                                        <button type="submit" class="cmn-btn search-icon">
                                            <i class="far fa-search"></i>
                                        </button>
                                    </div>
                                    @if (request('search'))
                                        <div class="mt-2">
                                            <a href="{{ route('client.products.index', array_merge(request()->except('search'), ['page' => 1])) }}"
                                                class="text-danger">
                                                <i class="fas fa-times"></i> Xóa nội dung
                                            </a>
                                        </div>
                                    @endif
                                </form>
                            </div>
                            <div class="single-sidebar-widget">
                                <div class="wid-title">
                                    <h5>Danh mục & Nhà xuất bản</h5>
                                </div>
                                <div class="product-status">
                                    <!-- Danh mục -->
                                    <div class="product-status_stock mb-4">
                                        <div class="nice-select category" tabindex="0">
                                            <span class="current">
                                                {{ request('category_id') ? $categories->firstWhere('id', request('category_id'))?->name : 'Chọn danh mục' }}
                                            </span>
                                            <ul class="list">
                                                <a href="{{ route('client.products.index', array_merge(request()->except(['category_id', 'page']), ['page' => 1])) }}"
                                                    class="{{ !request('category_id') ? 'selected' : '' }}">
                                                    <li class="option">
                                                        Tất cả danh mục
                                                        <span class="count">{{ $products->total() }}</span>
                                                    </li>
                                                </a>
                                                @foreach ($categories as $category)
                                                    <a href="{{ route('client.products.index', array_merge(request()->except(['category_id', 'page']), ['category_id' => $category->id, 'page' => 1])) }}"
                                                        class="{{ request('category_id') == $category->id ? 'selected' : '' }}">
                                                        <li class="option">
                                                            {{ $category->name }}
                                                            <span class="count">{{ $category->products_count }}</span>
                                                        </li>
                                                    </a>
                                                @endforeach
                                            </ul>
                                        </div>
                                    </div>

                                    <!-- Nhà xuất bản -->
                                    <div class="product-status_sale">
                                        <div class="nice-select category" tabindex="0">
                                            <span class="current">
                                                {{ request('publisher_id') ? $publishers->firstWhere('id', request('publisher_id'))?->name : 'Chọn nhà xuất bản' }}
                                            </span>
                                            <ul class="list">
                                                <a href="{{ route('client.products.index', array_merge(request()->except(['publisher_id', 'page']), ['page' => 1])) }}"
                                                    class="{{ !request('publisher_id') ? 'selected' : '' }}">
                                                    <li class="option">
                                                        Tất cả nhà xuất bản
                                                        <span class="count">{{ $products->total() }}</span>
                                                    </li>
                                                </a>
                                                @foreach ($publishers as $publisher)
                                                    <a href="{{ route('client.products.index', array_merge(request()->except(['publisher_id', 'page']), ['publisher_id' => $publisher->id, 'page' => 1])) }}"
                                                        class="{{ request('publisher_id') == $publisher->id ? 'selected' : '' }}">
                                                        <li class="option">
                                                            {{ $publisher->name }}
                                                            <span class="count">{{ $publisher->products_count }}</span>
                                                        </li>
                                                    </a>
                                                @endforeach
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Loại sách -->
                            <div class="single-sidebar-widget">
                                <div class="wid-title">
                                    <h5>Loại sách</h5>
                                </div>
                                <div class="product-status">
                                    <!-- Định dạng -->
                                    <div class="product-status_stock mb-4">
                                        <div class="nice-select category" tabindex="0">
                                            <span class="current">
                                                {{ request('format_id') ? $formats->firstWhere('id', request('format_id'))?->name : 'Chọn định dạng' }}
                                            </span>
                                            <ul class="list">
                                                <a href="{{ route('client.products.index', array_merge(request()->except(['format_id', 'page']), ['page' => 1])) }}"
                                                    class="{{ !request('format_id') ? 'selected' : '' }}">
                                                    <li class="option">
                                                        Tất cả định dạng
                                                        <span class="count">{{ $products->total() }}</span>
                                                    </li>
                                                </a>
                                                @foreach ($formats as $format)
                                                    <a href="{{ route('client.products.index', array_merge(request()->except(['format_id', 'page']), ['format_id' => $format->id, 'page' => 1])) }}"
                                                        class="{{ request('format_id') == $format->id ? 'selected' : '' }}">
                                                        <li class="option">
                                                            {{ $format->name }}
                                                            <span class="count">{{ $format->variants_count }}</span>
                                                        </li>
                                                    </a>
                                                @endforeach
                                            </ul>
                                        </div>
                                    </div>

                                    <!-- Ngôn ngữ -->
                                    <div class="product-status_sale">
                                        <div class="nice-select category" tabindex="0">
                                            <span class="current">
                                                {{ request('language_id') ? $languages->firstWhere('id', request('language_id'))?->name : 'Chọn ngôn ngữ' }}
                                            </span>
                                            <ul class="list">
                                                <a href="{{ route('client.products.index', array_merge(request()->except(['language_id', 'page']), ['page' => 1])) }}"
                                                    class="{{ !request('language_id') ? 'selected' : '' }}">
                                                    <li class="option">
                                                        Tất cả ngôn ngữ
                                                        <span class="count">{{ $products->total() }}</span>
                                                    </li>
                                                </a>
                                                @foreach ($languages as $language)
                                                    <a href="{{ route('client.products.index', array_merge(request()->except(['language_id', 'page']), ['language_id' => $language->id, 'page' => 1])) }}"
                                                        class="{{ request('language_id') == $language->id ? 'selected' : '' }}">
                                                        <li class="option">
                                                            {{ $language->name }}
                                                            <span class="count">{{ $language->variants_count }}</span>
                                                        </li>
                                                    </a>
                                                @endforeach
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Trạng thái -->
                            <div class="single-sidebar-widget">
                                <div class="wid-title">
                                    <h5>Trạng thái</h5>
                                </div>
                                <div class="categories-list">
                                    <ul class="nav nav-pills mb-3">
                                        <li class="nav-item">
                                            <a href="{{ route('client.products.index', array_merge(request()->except(['status', 'page']), ['page' => 1])) }}"
                                                class="nav-link {{ !request('status') ? 'active' : '' }}">
                                                Tất cả trạng thái
                                                <span class="count">{{ $products->total() }}</span>
                                            </a>
                                        </li>
                                        <li class="nav-item">
                                            <a href="{{ route('client.products.index', array_merge(request()->except(['status', 'page']), ['status' => 'hot', 'page' => 1])) }}"
                                                class="nav-link {{ request('status') == 'hot' ? 'active' : '' }}">
                                                Sách hot
                                                <span class="count">{{ $statusCounts['hot'] }}</span>
                                            </a>
                                        </li>
                                        <li class="nav-item">
                                            <a href="{{ route('client.products.index', array_merge(request()->except(['status', 'page']), ['status' => 'new', 'page' => 1])) }}"
                                                class="nav-link {{ request('status') == 'new' ? 'active' : '' }}">
                                                Sách mới
                                                <span class="count">{{ $statusCounts['new'] }}</span>
                                            </a>
                                        </li>
                                        <li class="nav-item">
                                            <a href="{{ route('client.products.index', array_merge(request()->except(['status', 'page']), ['status' => 'best_seller', 'page' => 1])) }}"
                                                class="nav-link {{ request('status') == 'best_seller' ? 'active' : '' }}">
                                                Bán chạy
                                                <span class="count">{{ $statusCounts['best_seller'] }}</span>
                                            </a>
                                        </li>
                                        <li class="nav-item">
                                            <a href="{{ route('client.products.index', array_merge(request()->except(['status', 'page']), ['status' => 'promotion', 'page' => 1])) }}"
                                                class="nav-link {{ request('status') == 'promotion' ? 'active' : '' }}">
                                                Đang khuyến mãi
                                                <span class="count">{{ $statusCounts['promotion'] }}</span>
                                            </a>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                            <div class="single-sidebar-widget mb-50">
                                <div class="wid-title">
                                    <h5>Lọc theo giá</h5>
                                </div>
                                <form action="{{ route('client.products.index') }}" method="GET"
                                    class="price-filter-form">
                                    @foreach (request()->except(['min_price', 'max_price']) as $key => $value)
                                        <input type="hidden" name="{{ $key }}" value="{{ $value }}">
                                    @endforeach
                                    <div class="price-range">
                                        <div class="price-inputs d-flex gap-3 mb-3">
                                            <div class="form-group">
                                                <input type="number" name="min_price" class="form-control"
                                                    value="{{ request('min_price') }}" min="{{ $priceRange['min'] }}"
                                                    max="{{ $priceRange['max'] }}" placeholder="Giá từ">
                                            </div>
                                            <div class="form-group">
                                                <input type="number" name="max_price" class="form-control"
                                                    value="{{ request('max_price') }}" min="{{ $priceRange['min'] }}"
                                                    max="{{ $priceRange['max'] }}" placeholder="Giá đến">
                                            </div>
                                        </div>
                                        <div class="price-slider">
                                            <div id="price-range" data-min="{{ $priceRange['min'] }}"
                                                data-max="{{ $priceRange['max'] }}"
                                                data-current-min="{{ request('min_price', $priceRange['min']) }}"
                                                data-current-max="{{ request('max_price', $priceRange['max']) }}">
                                            </div>
                                        </div>
                                        <button type="submit" class="theme-btn rounded-1">Áp dụng</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>

                    {{-- Danh sách sản phẩm --}}
                    <div class="col-xl-9 col-lg-8 order-1 order-md-2">
                        <div class="tab-content" id="pills-tabContent">
                            <div class="tab-pane fade show active" id="pills-arts" role="tabpanel"
                                aria-labelledby="pills-arts-tab" tabindex="0">
                                <div class="row">
                                    @foreach ($products as $product)
                                        <div class="col-xl-3 col-lg-4 col-md-6 wow fadeInUp" data-wow-delay=".2s">
                                            <div class="shop-box-items">
                                                <div class="book-thumb center">
                                                    <a href="{{ route('client.products.show',  $product->slug) }}">
                                                        @if ($product->cover_image_url)
                                                            <img src="{{ $product->cover_image_url }}" alt="img">
                                                        @else
                                                            <img src="{{ asset('auth/img/book_defaut.png') }}">
                                                        @endif
                                                    </a>
                                                    <ul class="post-box">
                                                        <li>
                                                            Hot
                                                        </li>
                                                        @if ($product->is_promotion)
                                                            <li>
                                                                Sale
                                                            </li>
                                                        @endif
                                                    </ul>
                                                </div>
                                                <div class="shop-content">
                                                    <h3 class="text-center">
                                                        <a href="{{ route('client.products.show',  $product->slug) }}">
                                                            {{ \Illuminate\Support\Str::limit($product->title, 25) }}
                                                        </a>
                                                    </h3>
                                                    <ul class="price-list">
                                                        <li
                                                            class="@if (!$product->average_rating) text-center w-100 @endif">
                                                            @if ($product->min_price && $product->max_price && $product->min_price != $product->max_price)
                                                                {{ number_format($product->min_price) }}₫
                                                                - {{ number_format($product->max_price) }}₫
                                                            @elseif($product->min_price)
                                                                {{ number_format($product->min_price) }}₫
                                                            @else
                                                                <span class="text-muted">-</span>
                                                            @endif
                                                        </li>
                                                        @if ($product->average_rating)
                                                            <li>
                                                                <i class="fa-solid fa-star"></i>
                                                                {{ $product->average_rating }}
                                                                ({{ $product->rating_count }})
                                                            </li>
                                                        @endif
                                                    </ul>
                                                    <div class="shop-button">
                                                        <a href="{{ route('client.products.show',  $product->slug) }}" class="theme-btn"><i
                                                                class="fa-solid fa-basket-shopping"></i> Add To Cart</a>
                                                    </div>
                                                </div>

                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                        <div class="page-nav-wrap text-center">
                            <div class="mt-4">
                                {{ $products->links('pagination::bootstrap-5') }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const sortSelect = document.getElementById('sort-select');
            const sortInput = document.getElementById('sort-input');
            const sortForm = document.getElementById('sort-form');

            // Xử lý sự kiện click cho các option
            sortSelect.querySelectorAll('.option').forEach(option => {
                option.addEventListener('click', function() {
                    const value = this.getAttribute('data-value');
                    sortInput.value = value;
                    sortForm.submit();
                });
            });
        });
    </script>
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.min.js"></script>
    <script>
        $(document).ready(function() {
            // Khởi tạo price range slider
            $("#price-range").slider({
                range: true,
                min: parseInt($("#price-range").data('min')),
                max: parseInt($("#price-range").data('max')),
                values: [
                    parseInt($("#price-range").data('current-min')),
                    parseInt($("#price-range").data('current-max'))
                ],
                slide: function(event, ui) {
                    $("input[name='min_price']").val(ui.values[0]);
                    $("input[name='max_price']").val(ui.values[1]);
                }
            });

            // Cập nhật slider khi input thay đổi
            $("input[name='min_price'], input[name='max_price']").on('change', function() {
                var min = parseInt($("input[name='min_price']").val()) || $("#price-range").slider("option",
                    "min");
                var max = parseInt($("input[name='max_price']").val()) || $("#price-range").slider("option",
                    "max");
                $("#price-range").slider("values", [min, max]);
            });
        });
    </script>
@endpush

@push('styles')
    <style>
        .categories-list ul {
            list-style: none;
            padding: 0;
            margin: 0;
        }

        .categories-list li {
            margin-bottom: 10px;
        }

        .categories-list a {
            display: flex;
            justify-content: space-between;
            align-items: center;
            color: #666;
            text-decoration: none;
            padding: 8px 12px;
            border-radius: 4px;
            transition: all 0.3s ease;
        }

        .categories-list a:hover {
            background: #f8f9fa;
            color: #333;
        }

        .categories-list a.active {
            background: #007bff;
            color: white;
        }

        .categories-list .count {
            font-size: 0.9em;
            color: inherit;
            opacity: 0.8;
        }

        .categories-list a.active .count {
            color: white;
        }

        .nice-select {
            width: 100%;
            margin-bottom: 15px;
        }

        .nice-select .list {
            width: 100%;
            max-height: 300px;
            overflow-y: auto;
        }

        .nice-select .option {
            padding: 8px 12px;
        }

        .nice-select .option a {
            display: flex;
            justify-content: space-between;
            align-items: center;
            color: inherit;
            text-decoration: none;
            width: 100%;
        }

        .nice-select .option.selected a {
            color: #007bff;
            font-weight: bold;
        }

        .price-range {
            padding: 15px;
            background: #f8f9fa;
            border-radius: 4px;
        }

        .price-inputs input {
            width: 100%;
            padding: 8px;
            border: 1px solid #ddd;
            border-radius: 4px;
        }

        .stars {
            display: inline-flex;
            gap: 2px;
        }

        .stars i {
            font-size: 14px;
        }
    </style>
@endpush