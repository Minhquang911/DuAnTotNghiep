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
                <h1>Thông tin sách</h1>
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
                            Chi tiết cuốn sách
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('content')
    <!-- Shop Details Section Start -->
    <style>
        .loading-spinner {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            padding: 2rem;
        }

        .loading-spinner .spinner-border {
            width: 3rem;
            height: 3rem;
        }

        .pagination-container {
            margin-top: 1rem;
        }

        .nice-select ul {
            background: aliceblue !important;
        }

        .nice-select ul li {
            border: none !important;
        }
    </style>
    <section class="shop-details-section fix section-padding">
        <div class="container">
            <div class="shop-details-wrapper">
                <div class="row g-4">
                    <div class="col-lg-5">
                        <div class="shop-details-image">
                            <div class="tab-content">
                                @foreach ($product->albums as $index => $album)
                                    <div id="thumb{{ $index + 1 }}"
                                        class="tab-pane fade {{ $index === 0 ? 'show active' : '' }}">
                                        <div class="shop-details-thumb">
                                            <img src="{{ asset('storage/' . $album->image) }}" alt="img"
                                                height="400px">
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                            <ul class="nav">
                                @foreach ($product->albums as $index => $album)
                                    <li class="nav-item">
                                        <a href="#thumb{{ $index + 1 }}" data-bs-toggle="tab"
                                            class="nav-link {{ $index === 0 ? 'active' : '' }}">
                                            <img src="{{ asset('storage/' . $album->image) }}" alt="img"
                                                height="120px">
                                        </a>
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                    </div>

                    <div class="col-lg-7">
                        <div class="shop-details-content">
                            <div class="title-wrapper">
                                <h2>{{ $product->title }}</h2>
                                @if ($product->average_rating)
                                    <div class="star">
                                        <a>
                                            <i class="fa-solid fa-star"></i>
                                            {{ $product->average_rating }}
                                        </a>
                                        ({{ $product->rating_count }} lượt đánh giá)
                                    </div>
                                @endif
                            </div>
                            <div class="social-icon">
                                <h6>Danh mục:</h6>
                                <a
                                    href="{{ route('client.products.index', array_merge(request()->except(['category_id', 'page']), ['category_id' => $product->category->id, 'page' => 1])) }}">
                                    {{ $product->category->name }}
                                </a>
                            </div>
                            <div class="social-icon">
                                <h6>Nhà xuất bản:</h6>
                                <a
                                    href="{{ route('client.products.index', array_merge(request()->except(['publisher_id', 'page']), ['publisher_id' => $product->publisher->id, 'page' => 1])) }}">
                                    {{ $product->publisher->name }}
                                </a>
                            </div>
                            <div class="social-icon">
                                <h6>Số lượng:</h6>
                                500
                            </div>
                            <div class="price-list">
                                <h3 id="variant-price">
                                    @if ($product->min_price && $product->max_price && $product->min_price != $product->max_price)
                                        {{ number_format($product->min_price) }}₫ -
                                        {{ number_format($product->max_price) }}₫
                                    @elseif($product->min_price)
                                        {{ number_format($product->min_price) }}₫
                                    @else
                                        <span class="text-muted">-</span>
                                    @endif
                                </h3>
                            </div>

                            <form action="{{ route('cart.add') }}" method="POST">
                                @csrf
                                <div class="social-icon">
                                    <h6>Chọn loại sách:</h6>
                                    <select id="variant-select" class="form-control" name="variant_id">
                                        <option value="" disabled {{ old('variant_id') ? '' : 'selected' }}>-- Chọn
                                            loại sách --</option>
                                        @foreach ($variants as $variant)
                                            <option value="{{ $variant->id }}" data-price="{{ $variant->price }}"
                                                data-promotion="{{ $variant->promotion_price }}"
                                                data-stock="{{ $variant->stock }}"
                                                {{ old('variant_id') == $variant->id ? 'selected' : '' }}>
                                                {{ $variant->format->name ?? '' }} - {{ $variant->language->name ?? '' }}
                                                ({{ $variant->stock }})
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                @if ($errors->has('variant_id'))
                                    <span class="text-danger">{{ $errors->first('variant_id') }}</span>
                                @endif

                                @if ($errors->has('quantity'))
                                    <span class="text-danger">{{ $errors->first('quantity') }}</span>
                                @endif

                                <div class="cart-wrapper">
                                    <div class="quantity-basket">
                                        <p class="qty">
                                            <button class="qtyminus" aria-hidden="true">−</button>
                                            <input type="number" name="quantity" id="qty2" min="1"
                                                max="{{ $variant->stock }}" step="1"
                                                value="{{ old('quantity', 1) }}">
                                            <button class="qtyplus" aria-hidden="true">+</button>
                                        </p>
                                    </div>
                                    <button type="submit" class="theme-btn">
                                        <i class="fa-solid fa-basket-shopping"></i>
                                        Add To Cart
                                    </button>
                                    @if (session('success'))
                                        <span class="text-success">{{ session('success') }}</span>
                                    @endif
                                </div>
                            </form>
                            <div class="category-box">
                                <div class="category-list">
                                    <ul>
                                        <li>
                                            <span>Mã sách:</span> {{ $product->isbn }}
                                        </li>
                                        <li>
                                            <span>Danh mục:</span> {{ $product->category->name }}
                                        </li>
                                        <li>
                                            <span>Nhà xuất bản:</span> {{ $product->publisher->name }}
                                        </li>
                                        <li>
                                            <span>Tác giả:</span> {{ $product->author }}
                                        </li>

                                    </ul>
                                    <ul>
                                        <li>
                                            <span>Kích thước:</span> @php
                                                $specs = [];
                                                if ($product->length_cm && $product->width_cm) {
                                                    $specs[] =
                                                        rtrim(rtrim(number_format($product->length_cm, 2), '0'), '.') .
                                                        ' x ' .
                                                        rtrim(rtrim(number_format($product->width_cm, 2), '0'), '.') .
                                                        ' cm';
                                                }
                                            @endphp
                                            @if (count($specs))
                                                {{ implode(' | ', $specs) }}
                                            @else
                                                <span class="text-muted">-</span>
                                            @endif
                                        </li>
                                        <li>
                                            <span>Trọng lượng:</span> {{ $product->weight_g }} g
                                        </li>
                                        <li>
                                            <span>Số trang:</span> {{ $product->page_count }} trang
                                        </li>
                                        <li>
                                            <span>Lượt xem:</span> {{ $product->view_count }}
                                        </li>
                                        <li>
                                            <span>Ngày phát hành:</span>
                                            {{ $product->published_at ? $product->published_at->format('d-m-Y') : '-' }}
                                        </li>
                                    </ul>
                                </div>
                            </div>
                            <div class="box-check">
                                <div class="check-list">
                                    <ul>
                                        <li>
                                            <i class="fa-solid fa-check"></i>
                                            Giao sách tận nhà nhanh chóng
                                        </li>
                                        <li>
                                            <i class="fa-solid fa-check"></i>
                                            Thanh toán linh hoạt – an tâm tuyệt đối
                                        </li>
                                    </ul>
                                    <ul>
                                        <li>
                                            <i class="fa-solid fa-check"></i>
                                            Nhiều ưu đãi cho mọt sách
                                        </li>
                                        <li>
                                            <i class="fa-solid fa-check"></i>
                                            Sách chính hãng – chất lượng đảm bảo
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="single-tab section-padding pb-0">
                    <ul class="nav mb-5" role="tablist">
                        <li class="nav-item" role="presentation">
                            <a href="#description" data-bs-toggle="tab" class="nav-link ps-0 active" aria-selected="true"
                                role="tab">
                                <h6>Mô tả chi tiết</h6>
                            </a>
                        </li>
                        @php
                            $approvedComments = $product->comments()->where('is_approved', true)->count();
                            $approvedRatings = $product->ratings()->where('is_approved', true)->count();
                        @endphp
                        <li class="nav-item" role="presentation">
                            <a href="#comment" data-bs-toggle="tab" class="nav-link" aria-selected="false"
                                tabindex="-1" role="tab">
                                <h6>Bình luận ({{ $approvedComments }})</h6>
                            </a>
                        </li>
                        <li class="nav-item" role="presentation">
                            <a href="#rating" data-bs-toggle="tab" class="nav-link" aria-selected="false"
                                tabindex="-1" role="tab">
                                <h6>Đánh giá ({{ $approvedRatings }})</h6>
                            </a>
                        </li>
                    </ul>
                    <div class="tab-content">
                        <div id="description" class="tab-pane fade show active" role="tabpanel">
                            <div class="description-items">
                                <p>
                                    {{ $product->description }}
                                </p>
                            </div>
                        </div>

                        <div id="comment" class="tab-pane fade" role="tabpanel">
                            <div id="comments-container">
                                @include('client.products.partials.comments', ['comments' => $comments])
                            </div>
                        </div>
                        <div id="rating" class="tab-pane fade" role="tabpanel">
                            <div id="ratings-container">
                                @include('client.products.partials.ratings', ['ratings' => $ratings])
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Top Ratting Book Section Start -->
    <section class="top-ratting-book-section fix section-padding pt-0">
        <div class="container">
            <div class="section-title text-center">
                <h2 class="mb-3 wow fadeInUp" data-wow-delay=".3s">Sản phẩm liên quan</h2>
                <p class="wow fadeInUp" data-wow-delay=".5s">
                    Khám phá thêm các sản phẩm tương tự mà bạn có thể quan tâm. <br>
                    Chất lượng đảm bảo – Giá cả hợp lý – Cập nhật mới nhất mỗi ngày.
                </p>
            </div>
            <div class="swiper book-slider">
                <div class="swiper-wrapper">
                    @foreach ($relatedProducts as $product)
                        <div class="swiper-slide">
                            <div class="shop-box-items style-2">
                                <div class="book-thumb center">
                                    <a href="{{ route('client.products.show', $product->slug) }}">
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
                                    <h5> {{ $product->category->name }} </h5>
                                    <h3 class="text-center">
                                        <a href="{{ route('client.products.show', $product->slug) }}">
                                            {{ \Illuminate\Support\Str::limit($product->title, 25) }}
                                        </a>
                                    </h3>
                                    <ul class="price-list">
                                        <li class="@if (!$product->average_rating) text-center w-100 @endif">
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
                                </div>
                                <div class="shop-button">
                                    <a href="{{ route('client.products.show', $product->slug) }}" class="theme-btn">
                                        <i class="fa-solid fa-eye"></i>
                                        Xem chi tiết</a>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </section>
@endsection

@push('scripts')
    <script>
        $(document).ready(function() {
            // Setup CSRF token cho tất cả AJAX requests
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            // Xử lý AJAX pagination cho comments
            $(document).on('click', '#comments-container .pagination a', function(e) {
                e.preventDefault();
                var page = $(this).attr('href').split('page=')[1];
                loadComments(page);
            });

            // Xử lý AJAX pagination cho ratings
            $(document).on('click', '#ratings-container .pagination a', function(e) {
                e.preventDefault();
                var page = $(this).attr('href').split('page=')[1];
                loadRatings(page);
            });

            // Function để load comments
            function loadComments(page) {
                // Hiển thị loading
                $('#comments-container').html(
                    '<div class="loading-spinner"><div class="spinner-border text-primary" role="status"><span class="visually-hidden">Đang tải...</span></div><p class="mt-2">Đang tải bình luận...</p></div>'
                );

                $.ajax({
                    url: '/products/{{ $product->id }}/comments?page=' + page,
                    type: 'GET',
                    success: function(data) {
                        $('#comments-container').html(data);
                        // Scroll to top of comments container
                        $('html, body').animate({
                            scrollTop: $('#comment').offset().top - 100
                        }, 500);
                    },
                    error: function() {
                        $('#comments-container').html(
                            '<div class="text-center py-4"><div class="alert alert-danger">Có lỗi xảy ra khi tải bình luận. Vui lòng thử lại.</div></div>'
                        );
                    }
                });
            }

            // Function để load ratings
            function loadRatings(page) {
                // Hiển thị loading
                $('#ratings-container').html(
                    '<div class="loading-spinner"><div class="spinner-border text-primary" role="status"><span class="visually-hidden">Đang tải...</span></div><p class="mt-2">Đang tải đánh giá...</p></div>'
                );

                $.ajax({
                    url: '/products/{{ $product->id }}/ratings?page=' + page,
                    type: 'GET',
                    success: function(data) {
                        $('#ratings-container').html(data);
                        // Scroll to top of ratings container
                        $('html, body').animate({
                            scrollTop: $('#rating').offset().top - 100
                        }, 500);
                    },
                    error: function() {
                        $('#ratings-container').html(
                            '<div class="text-center py-4"><div class="alert alert-danger">Có lỗi xảy ra khi tải đánh giá. Vui lòng thử lại.</div></div>'
                        );
                    }
                });
            }
        });
    </script>
    <script>
        $(document).ready(function() {
            const $variantSelect = $('#variant-select');
            const $priceDisplay = $('#variant-price');
            const originalPriceHtml = $priceDisplay.html(); // Lưu giá ban đầu (min-max)

            function updatePrice() {
                const $selectedOption = $variantSelect.find('option:selected');
                const value = $selectedOption.val();

                if (!value) {
                    // Nếu chưa chọn biến thể => hiển thị lại khoảng giá ban đầu
                    $priceDisplay.html(originalPriceHtml);
                    return;
                }

                const price = parseFloat($selectedOption.data('price')) || 0;
                const promotion = parseFloat($selectedOption.data('promotion')) || 0;

                if (promotion > 0 && promotion < price) {
                    $priceDisplay.html(`
                        <span style="text-decoration: line-through; color: gray; margin-right: 8px;">
                            ${price.toLocaleString('vi-VN')}₫
                        </span>
                        <span>
                            ${promotion.toLocaleString('vi-VN')}₫
                        </span>
                    `);
                } else {
                    $priceDisplay.html(`${price.toLocaleString('vi-VN')}₫`);
                }
            }

            // Gắn sự kiện change bằng jQuery
            $variantSelect.on('change', updatePrice);
        });
    </script>
@endpush

@if (session('success'))
    <script>
        toastr.success("{{ session('success') }}");
    </script>
@endif
@if (session('error'))
    <script>
        toastr.error("{{ session('error') }}");
    </script>
@endif