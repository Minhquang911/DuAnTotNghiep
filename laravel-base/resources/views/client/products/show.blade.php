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
                                <h3>
                                    @if ($product->min_price && $product->max_price && $product->min_price != $product->max_price)
                                        {{ number_format($product->min_price) }}₫
                                        - {{ number_format($product->max_price) }}₫
                                    @elseif($product->min_price)
                                        {{ number_format($product->min_price) }}₫
                                    @else
                                        <span class="text-muted">-</span>
                                    @endif
                                </h3>
                            </div>
                            <div class="cart-wrapper">
                                <div class="quantity-basket">
                                    <p class="qty">
                                        <button class="qtyminus" aria-hidden="true">−</button>
                                        <input type="number" name="qty" id="qty2" min="1" max="10"
                                            step="1" value="1">
                                        <button class="qtyplus" aria-hidden="true">+</button>
                                    </p>
                                </div>
                                <a href="shop-details.html" class="theme-btn"><i class="fa-solid fa-basket-shopping"></i>
                                    Add To Cart</a>
                            </div>
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
                        <li class="nav-item" role="presentation">
                            <a href="#comment" data-bs-toggle="tab" class="nav-link" aria-selected="false" tabindex="-1"
                                role="tab">
                                <h6>Bình luận</h6>
                            </a>
                        </li>
                        <li class="nav-item" role="presentation">
                            <a href="#rating" data-bs-toggle="tab" class="nav-link" aria-selected="false" tabindex="-1"
                                role="tab">
                                <h6>Đánh giá </h6>
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
                            Hiển thị bình luận
                        </div>
                        <div id="rating" class="tab-pane fade" role="tabpanel">
                            Hiển thị đánh giá
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
                                    <a href="{{ route('client.products.show',  $product->slug) }}" class="theme-btn"><i
                                            class="fa-solid fa-basket-shopping"></i> Add To Cart</a>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </section>
@endsection