@extends('layouts.client.ClientLayout')

@section('title')
    {{ isset($title) ? $title . ' - Book360 Store WooCommerce' : 'Book360 Store WooCommerce' }}
@endsection

@section('banner')
    <!-- Banner Slider Section -->
    @if (empty($banners))
        <div class="hero-section hero-2 fix">
            <div class="container">
                <div class="row">
                    <div class="col-12 col-xl-6 col-lg-6">
                        <div class="hero-items">
                            <div class="frame-shape1 float-bob-x">
                                <img src="{{ asset('client/img/hero/hero2-shape1.png') }}" alt="shape-img">
                            </div>
                            <div class="frame-shape2 float-bob-y">
                                <img src="{{ asset('client/img/hero/hero2-shape2.png') }}" alt="shape-img">
                            </div>
                            <div class="book-image">
                                <img src="{{ asset('client/img/hero/hero-book.png') }}" alt="img">
                            </div>
                            <div class="hero-content">
                                <h6 class="wow fadeInUp" data-wow-delay=".3s">Khám phá những cuốn sách</h6>
                                <h1 class="wow fadeInUp" data-wow-delay=".5s">Mở rộng tâm trí <br> Đọc một cuốn sách </h1>
                                <p class="text-capitalize">Book360 - Thiên đường sách trực tuyến của bạn. Khám phá kho tàng
                                    sách phong phú với hàng ngàn đầu sách từ văn học, kinh tế, kỹ năng sống đến sách thiếu
                                    nhi.</p>
                                <div class="form-clt wow fadeInUp mt-5" data-wow-delay=".9s">
                                    <a href="{{ route('client.products.index') }}" class="theme-btn">
                                        Cửa hàng <i class="fa-solid fa-arrow-right-long"></i>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @else
        <!-- Add Swiper CSS in the head section -->
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" />

        <!-- Add Swiper JS before closing body tag -->
        <script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>

        <div class="swiper bannerSwiper">
            <div class="swiper-wrapper">
                @foreach ($banners as $banner)
                    <div class="swiper-slide">
                        <div class="hero-section hero-2 fix"
                            style="background-image: url('{{ asset('storage/' . $banner->image) }}')">
                            <div class="container">
                                <div class="row">
                                    <div class="col-12 col-xl-6 col-lg-6">
                                        <div class="hero-items">
                                            <div class="frame-shape1 float-bob-x">
                                                <img src="{{ asset('client/img/hero/hero2-shape1.png') }}" alt="shape-img">
                                            </div>
                                            <div class="frame-shape2 float-bob-y">
                                                <img src="{{ asset('client/img/hero/hero2-shape2.png') }}" alt="shape-img">
                                            </div>
                                            <div class="hero-content">
                                                <h6 class="wow fadeInUp" data-wow-delay=".3s">Book360 Store</h6>
                                                <h1 class="wow fadeInUp" data-wow-delay=".5s">{{ $banner->title }}</h1>
                                                <p class="text-capitalize">{{ $banner->description }}</p>
                                                <div class="form-clt wow fadeInUp mt-5" data-wow-delay=".9s">
                                                    <a href="{{ $banner->link }}" class="theme-btn">
                                                        Mua ngay <i class="fa-solid fa-arrow-right-long"></i>
                                                    </a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
            <div class="swiper-button-next"></div>
            <div class="swiper-button-prev"></div>
        </div>
        <!-- Initialize Swiper -->
        <script>
            var swiper = new Swiper(".bannerSwiper", {
                slidesPerView: 1,
                spaceBetween: 30,
                loop: true,
                autoplay: {
                    delay: 5000,
                    disableOnInteraction: false,
                },
                navigation: {
                    nextEl: ".swiper-button-next",
                    prevEl: ".swiper-button-prev",
                },
            });
        </script>

        <style>
            .hero-section {
                background-size: cover;
                background-position: center;
                background-repeat: no-repeat;
                min-height: 600px;
                position: relative;
            }

            .swiper {
                width: 100%;
                height: 100%;
            }

            .swiper-button-next,
            .swiper-button-prev {
                color: #fff;
            }

            .swiper-pagination-bullet {
                background: #fff;
            }

            .swiper-pagination-bullet-active {
                background: #007bff;
            }
        </style>
    @endif

    <!-- Book Banner Section start  -->
    <section class="book-banner-section fix section-padding">
        <div class="container">
            <div class="row g-4">
                <div class="swiper featured-books-slider">
                    <div class="swiper-wrapper">
                        @foreach ($promotions as $promotion)
                            <div class="swiper-slide">
                                <div class="wow fadeInUp" data-wow-delay=".3s">
                                    <div class="banner-book-card-items bg-cover"
                                        style="background-image: url('{{ asset('client/img/banner/book-banner-' . rand(1, 3) . '.jpg') }}');">
                                        <div class="book-shape">
                                            <img src="{{ asset('storage/' . $promotion->image) }}" alt="img">
                                        </div>
                                        <div class="banner-book-content">
                                            <h2 class="w-50">{{ $promotion->title }}</h2>
                                            <h6 class="w-50 m-0">{{ $promotion->description }}</h6>
                                            <h6 class="w-50">
                                                {{ $promotion->start_date->format('d/m/Y') }} -
                                                {{ $promotion->end_date->format('d/m/Y') }}
                                            </h6>
                                            <button class="theme-btn white-bg"
                                                onclick="copyPromotionCode('{{ $promotion->code }}', this)">
                                                Copy mã: <strong>{{ $promotion->code }}</strong>
                                                <i class="fa-solid fa-copy"></i>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <div class="swiper-pagination"></div>
                </div>
            </div>
        </div>
    </section>
    <script>
        function copyPromotionCode(code, el) {
            navigator.clipboard.writeText(code)
                .then(function() {
                    el.innerHTML = 'Đã copy! <i class="fa-solid fa-check"></i>';
                    setTimeout(() => {
                        el.innerHTML = 'Copy mã: <strong>' + code +
                            '</strong> <i class="fa-solid fa-copy"></i>';
                    }, 1500);
                })
                .catch(function() {
                    alert('Không thể copy, hãy thử thủ công.');
                });
        }
    </script>
@endsection

@section('content')
    <!-- Shop Section Start -->
    <section class="shop-section section-padding fix pt-0">
        <div class="container">
            <div class="section-title-area">
                <div class="section-title">
                    <h2 class="wow fadeInUp" data-wow-delay=".3s">Sách hot</h2>
                </div>
                <a href="{{ route('client.products.index') }}" class="theme-btn transparent-btn wow fadeInUp" data-wow-delay=".5s">Xem thêm <i
                        class="fa-solid fa-arrow-right-long"></i></a>
            </div>
            <div class="swiper book-slider">
                <div class="swiper-wrapper">
                    @foreach ($hotPromotionProducts as $product)
                        <div class="swiper-slide">
                            <div class="shop-box-items style-2">
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
                                    <div class="shop-button">
                                        <a href="{{ route('client.products.show',  $product->slug) }}" class="theme-btn">
                                            <i class="fa-solid fa-eye"></i> Xem chi tiết</a>
                                    </div>
                                </div>
                                <div class="shop-content">
                                    <h5>{{ $product->publisher->name }} </h5>
                                    <h3><a href="{{ route('client.products.show',  $product->slug) }}">{{ $product->title }}</a></h3>
                                    <ul class="price-list">
                                        <li>
                                            @if ($product->min_price && $product->max_price && $product->min_price != $product->max_price)
                                                {{ number_format($product->min_price) }}₫
                                                - {{ number_format($product->max_price) }}₫
                                            @elseif($product->min_price)
                                                {{ number_format($product->min_price) }}₫
                                            @else
                                                <span class="text-muted">-</span>
                                            @endif
                                        </li>
                                    </ul>
                                    <ul class="author-post">
                                        <li class="authot-list">
                                            <span class="content">{{ $product->category->name }}</span>
                                        </li>
                                        <li>
                                            @if ($product->average_rating)
                                                <i class="fa-solid fa-star"></i>
                                                {{ $product->average_rating }} ({{ $product->rating_count }})
                                            @endif
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </section>

    <!-- Featured Books Section Start -->
    <section class="featured-books-section pt-100 pb-145 fix section-bg">
        <div class="container">
            <div class="section-title-area justify-content-center">
                <div class="section-title wow fadeInUp" data-wow-delay=".3s">
                    <h2>Sách nổi bật</h2>
                </div>
            </div>

            <div class="swiper featured-books-slider">
                <div class="swiper-wrapper">
                    @foreach ($featuredProducts as $product)
                        <div class="swiper-slide">
                            <div class="shop-box-items style-4 wow fadeInUp" data-wow-delay=".2s">
                                <div class="book-thumb center">
                                    <a href="{{ route('client.products.show',  $product->slug) }}">
                                        @if ($product->cover_image_url)
                                            <img src="{{ $product->cover_image_url }}" alt="img">
                                        @else
                                            <img src="{{ asset('auth/img/book_defaut.png') }}">
                                        @endif
                                    </a>
                                </div>
                                <div class="shop-content">
                                    <ul class="book-category">
                                        <li class="book-category-badge">{{ $product->publisher->name }}</li>
                                        <li>
                                            @if ($product->average_rating)
                                                <i class="fa-solid fa-star"></i>
                                                {{ $product->average_rating }} ({{ $product->rating_count }})
                                            @endif
                                        </li>
                                    </ul>
                                    <h3><a href="{{ route('client.products.show',  $product->slug) }}">{{ $product->title }}</a></h3>
                                    <ul class="author-post">
                                        <li class="authot-list">
                                            <span class="content">{{ $product->category->name }}</span>
                                        </li>
                                    </ul>
                                    <div class="book-availablity">
                                        <div class="details">
                                            <ul class="price-list">
                                                <li style="color: #FF6500;">
                                                    @if ($product->min_price && $product->max_price && $product->min_price != $product->max_price)
                                                        {{ number_format($product->min_price) }}₫
                                                        - {{ number_format($product->max_price) }}₫
                                                    @elseif($product->min_price)
                                                        {{ number_format($product->min_price) }}₫
                                                    @else
                                                        <span class="text-muted">-</span>
                                                    @endif
                                                </li>
                                            </ul>
                                            <div style="width: 317px">

                                            </div>
                                            <p>{{ $product->view_count }} Lượt xem</p>
                                        </div>
                                        <div class="shop-btn">
                                            <a href="{{ route('client.products.show',  $product->slug) }}">
                                                <i class="fa-regular fa-eye"></i>
                                            </a>
                                        </div>

                                    </div>

                                </div>
                            </div>
                        </div>
                    @endforeach

                </div>

                <div class="swiper-pagination"></div>
            </div>
        </div>
    </section>


    <!-- Best Seller Section Start -->
    <section class="best-seller-section section-padding fix">
        <div class="container">
            <div class="section-title-area">
                <div class="section-title wow fadeInUp" data-wow-delay=".3s">
                    <h2>Sách bán chạy</h2>
                </div>
                <a href="{{ route('client.products.index') }}" class="theme-btn transparent-btn wow fadeInUp" data-wow-delay=".5s">Xem thêm <i
                        class="fa-solid fa-arrow-right-long"></i></a>
            </div>
            <div class="book-shop-wrapper style-2">
                @foreach ($bestSellerProducts as $product)
                    <div class="shop-box-items style-3 wow fadeInUp" data-wow-delay=".2s">
                        <div class="book-thumb center">
                            <a href="{{ route('client.products.show',  $product->slug) }}">
                                @if ($product->cover_image_url)
                                    <img src="{{ $product->cover_image_url }}" alt="img">
                                @else
                                    <img src="{{ asset('auth/img/book_defaut.png') }}">
                                @endif
                            </a>
                        </div>
                        <div class="shop-content">
                            <ul class="book-category">
                                <li class="book-category-badge">{{ $product->category->name }}</li>
                                <li>
                                    @if ($product->average_rating)
                                        <i class="fa-solid fa-star"></i>
                                        {{ $product->average_rating }} ({{ $product->rating_count }})
                                    @endif
                                </li>
                            </ul>
                            <h3><a href="{{ route('client.products.show',  $product->slug) }}">{{ $product->title }}</a></h3>
                            <ul class="author-post">
                                <li class="authot-list">
                                    <span class="content">{{ $product->publisher->name }}</span>
                                </li>
                            </ul>
                            <ul class="price-list">
                                <li style="font-size: 18px; font-weight: 600;">
                                    @if ($product->min_price && $product->max_price && $product->min_price != $product->max_price)
                                        {{ number_format($product->min_price) }}₫
                                        - {{ number_format($product->max_price) }}₫
                                    @elseif($product->min_price)
                                        {{ number_format($product->min_price) }}₫
                                    @else
                                        <span class="text-muted">-</span>
                                    @endif
                                </li>
                            </ul>
                            <div class="shop-button">
                                <a href="{{ route('client.products.show',  $product->slug) }}" class="theme-btn">
                                    <i class="fa-solid fa-eye"></i>
                                    Xem chi tiết</a>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    <!-- Feature Section Start -->
    <section class="feature-section fix section-padding pt-0">
        <div class="container">
            <div class="feature-wrapper">
                <div class="feature-box-items wow fadeInUp" data-wow-delay=".2s">
                    <div class="icon">
                        <i class="icon-icon-1"></i>
                    </div>
                    <div class="content">
                        <h3>Trả lại và hoàn tiền</h3>
                        <p>Đảm bảo hoàn lại tiền</p>
                    </div>
                </div>
                <div class="feature-box-items wow fadeInUp" data-wow-delay=".4s">
                    <div class="icon">
                        <i class="icon-icon-2"></i>
                    </div>
                    <div class="content">
                        <h3>Thanh toán an toàn</h3>
                        <p>Thanh toán online thuận tiện</p>
                    </div>
                </div>
                <div class="feature-box-items wow fadeInUp" data-wow-delay=".6s">
                    <div class="icon">
                        <i class="icon-icon-3"></i>
                    </div>
                    <div class="content">
                        <h3>hỗ trợ trực tuyến</h3>
                        <p>Sẵn sàng hỗ trợ 24/7</p>
                    </div>
                </div>
                <div class="feature-box-items wow fadeInUp" data-wow-delay=".8s">
                    <div class="icon">
                        <i class="icon-icon-4"></i>
                    </div>
                    <div class="content">
                        <h3>Ưu đãi hàng ngày</h3>
                        <p>Chương trình ưu đãi hấp dẫn</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Shop Section Start -->
    <section class="shop-section section-padding fix pt-0">
        <div class="container">
            <div class="section-title-area">
                <div class="section-title wow fadeInUp" data-wow-delay=".3s">
                    <h2>Sách mới</h2>
                </div>
                <a href="{{ route('client.products.index') }}" class="theme-btn transparent-btn wow fadeInUp" data-wow-delay=".5s">Xem thêm <i
                        class="fa-solid fa-arrow-right-long"></i></a>
            </div>
            <div class="book-shop-wrapper">
                @foreach ($newProducts as $product)
                <div class="shop-box-items style-2 wow fadeInUp" data-wow-delay=".2s">
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
                                Mới
                            </li>
                            @if ($product->is_promotion)
                                <li>
                                    Sale
                                </li>
                            @endif
                        </ul>
                        <div class="shop-button">
                            <a href="{{ route('client.products.show',  $product->slug) }}" class="theme-btn">
                                <i class="fa-solid fa-eye"></i>
                                Xem chi tiết</a>
                        </div>
                    </div>
                    <div class="shop-content">
                        <h5>{{ $product->publisher->name }} </h5>
                        <h3><a href="{{ route('client.products.show',  $product->slug) }}">{{ $product->title }}</a></h3>
                        <ul class="price-list">
                            <li>
                                @if ($product->min_price && $product->max_price && $product->min_price != $product->max_price)
                                    {{ number_format($product->min_price) }}₫
                                    - {{ number_format($product->max_price) }}₫
                                @elseif($product->min_price)
                                    {{ number_format($product->min_price) }}₫
                                @else
                                    <span class="text-muted">-</span>
                                @endif
                            </li>
                        </ul>
                        <ul class="author-post">
                            <li class="authot-list">
                                <span class="content">{{ $product->category->name }}</span>
                            </li>
                            <li>
                                @if ($product->average_rating)
                                    <i class="fa-solid fa-star"></i>
                                    {{ $product->average_rating }} ({{ $product->rating_count }})
                                @endif
                            </li>
                        </ul>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </section>

    <!-- Cta Banner Section Start -->
    <section class="cta-banner-section fix section-padding pt-0">
        <div class="container">
            <div class="cta-banner-wrapper style-2 section-padding bg-cover"
                style="background-image: url('{{ asset('client/img/cta-banner2.jpg') }}');">
                <div class="overlay"></div>
                <div class="cta-content text-left">
                    <h2 class="text-white mb-40 wow fadeInUp" data-wow-delay=".3s">Book360 <br/> Thiên đường sách trực tuyến của bạn.</h2>
                    <a href="{{ route('client.products.index') }}" class="theme-btn white-bg wow fadeInUp" data-wow-delay=".5s">Cửa hàng<i
                            class="fa-solid fa-arrow-right-long"></i></a>
                </div>
            </div>
        </div>
    </section>

    <!-- Shop Section Start -->
    <section class="shop-section section-padding fix pt-0">
        <div class="container">
            <div class="section-title-area">
                <div class="section-title">
                    <h2 class="wow fadeInUp" data-wow-delay=".3s">Có thể bạn quan tâm</h2>
                </div>
                <a href="{{ route('client.products.index') }}" class="theme-btn transparent-btn wow fadeInUp" data-wow-delay=".5s">Xem thêm <i
                        class="fa-solid fa-arrow-right-long"></i></a>
            </div>
            <div class="swiper book-slider">
                <div class="swiper-wrapper">
                    @foreach ($recommendedProducts as $product)
                        <div class="swiper-slide">
                            <div class="shop-box-items style-2">
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
                                    <div class="shop-button">
                                        <a href="{{ route('client.products.show',  $product->slug) }}" class="theme-btn">
                                            <i class="fa-solid fa-eye"></i>
                                            Xem chi tiết</a>
                                    </div>
                                </div>
                                <div class="shop-content">
                                    <h5>{{ $product->publisher->name }} </h5>
                                    <h3><a href="{{ route('client.products.show',  $product->slug) }}">{{ $product->title }}</a></h3>
                                    <ul class="price-list">
                                        <li>
                                            @if ($product->min_price && $product->max_price && $product->min_price != $product->max_price)
                                                {{ number_format($product->min_price) }}₫
                                                - {{ number_format($product->max_price) }}₫
                                            @elseif($product->min_price)
                                                {{ number_format($product->min_price) }}₫
                                            @else
                                                <span class="text-muted">-</span>
                                            @endif
                                        </li>
                                    </ul>
                                    <ul class="author-post">
                                        <li class="authot-list">
                                            <span class="content">{{ $product->category->name }}</span>
                                        </li>
                                        <li>
                                            @if ($product->average_rating)
                                                <i class="fa-solid fa-star"></i>
                                                {{ $product->average_rating }} ({{ $product->rating_count }})
                                            @endif
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </section>

    <!-- News Section Start -->
    <section class="news-section fix section-padding section-bg">
        <div class="container">
            <div class="section-title text-center">
                <h2 class="mb-3 wow fadeInUp" data-wow-delay=".3s">Tin tức - Sự kiện</h2>
                <p class="wow fadeInUp" data-wow-delay=".5s">Book360 - Tri thức đi muôn nơi </p>
            </div>
            <div class="row">
                @foreach ($posts as $post)
                    <div class="col-xl-3 col-lg-4 col-md-6 wow fadeInUp" data-wow-delay=".2s">
                        <div class="news-card-items">
                            <div class="news-image" style="height: 230px">
                                <img src="{{ asset('storage/' . $post->image) }}" alt="img">
                                <img src="{{ asset('storage/' . $post->image) }}" alt="img">
                                <div class="post-box">
                                    Activities
                                </div>
                            </div>
                            <div class="news-content">
                                <ul>
                                    <li>
                                        <i class="fa-light fa-calendar-days"></i>
                                        {{ $post->created_at->format('d/m/Y H:i') }}
                                    </li>
                                    <li>
                                        <i class="fa-regular fa-user"></i>
                                        By Admin
                                    </li>
                                </ul>
                                <h3><a href="">{{ $post->title }}</a></h3>
                                <a href="" class="theme-btn-2">Chi tiết <i
                                        class="fa-regular fa-arrow-right-long"></i></a>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>
@endsection