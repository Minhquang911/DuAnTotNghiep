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
                            <p>Showing 1-3 Of 34 Results </p>
                            <div class="form-clt">
                                <div class="nice-select" tabindex="0">
                                    <span class="current">
                                        Default Sorting
                                    </span>
                                    <ul class="list">
                                        <li data-value="1" class="option selected focus">
                                            Default sorting
                                        </li>
                                        <li data-value="1" class="option">
                                            Sort by popularity
                                        </li>
                                        <li data-value="1" class="option">
                                            Sort by average rating
                                        </li>
                                        <li data-value="1" class="option">
                                            Sort by latest
                                        </li>
                                    </ul>
                                </div>
                                <div class="icon">
                                    <a href="shop-list.html"><i class="fas fa-list"></i></a>
                                </div>
                                <div class="icon-2 active">
                                    <a href="shop.html"><i class="fa-sharp fa-regular fa-grid-2"></i></a>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-3 col-lg-4 order-2 order-md-1 wow fadeInUp" data-wow-delay=".3s">
                        <div class="main-sidebar">
                            <div class="single-sidebar-widget">
                                <div class="wid-title">
                                    <h5>Search</h5>
                                </div>
                                <form action="#" class="search-toggle-box">
                                    <div class="input-area search-container">
                                        <input class="search-input" type="text" placeholder="Search here">
                                        <button class="cmn-btn search-icon">
                                            <i class="far fa-search"></i>
                                        </button>
                                    </div>
                                </form>
                            </div>
                            <div class="single-sidebar-widget">
                                <div class="wid-title">
                                    <h5>Categories</h5>
                                </div>
                                <div class="categories-list">
                                    <ul class="nav nav-pills mb-3" id="pills-tab" role="tablist">
                                        <li class="nav-item" role="presentation">
                                            <button class="nav-link active" id="pills-arts-tab" data-bs-toggle="pill"
                                                data-bs-target="#pills-arts" type="button" role="tab"
                                                aria-controls="pills-arts" aria-selected="true">Arts &
                                                Photography</button>
                                        </li>
                                        <li class="nav-item" role="presentation">
                                            <button class="nav-link" id="pills-Biographies-tab" data-bs-toggle="pill"
                                                data-bs-target="#pills-Biographies" type="button" role="tab"
                                                aria-controls="pills-Biographies" aria-selected="false">Biographies &
                                                Memoirs</button>
                                        </li>
                                        <li class="nav-item" role="presentation">
                                            <button class="nav-link" id="pills-ChristianBooks-tab" data-bs-toggle="pill"
                                                data-bs-target="#pills-ChristianBooks" type="button" role="tab"
                                                aria-controls="pills-ChristianBooks" aria-selected="false">Christian
                                                Books & Bibles</button>
                                        </li>
                                        <li class="nav-item" role="presentation">
                                            <button class="nav-link" id="pills-ResearchPublishing-tab" data-bs-toggle="pill"
                                                data-bs-target="#pills-ResearchPublishing" type="button" role="tab"
                                                aria-controls="pills-ResearchPublishing" aria-selected="false">Research &
                                                Publishing Guides</button>
                                        </li>
                                        <li class="nav-item" role="presentation">
                                            <button class="nav-link" id="pills-SportsOutdoors-tab" data-bs-toggle="pill"
                                                data-bs-target="#pills-SportsOutdoors" type="button" role="tab"
                                                aria-controls="pills-SportsOutdoors" aria-selected="false">Sports &
                                                Outdoors</button>
                                        </li>
                                        <li class="nav-item" role="presentation">
                                            <button class="nav-link" id="pills-FoodDrink-tab" data-bs-toggle="pill"
                                                data-bs-target="#pills-FoodDrink" type="button" role="tab"
                                                aria-controls="pills-FoodDrink" aria-selected="false">Food &
                                                Drink</button>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                            <div class="single-sidebar-widget">
                                <div class="wid-title">
                                    <h5>Product Status</h5>
                                </div>
                                <div class="product-status">
                                    <div class="product-status_stock  gap-6 d-flex align-items-center">
                                        <div class="nice-select category" tabindex="0"><span class="current">
                                                In Stock
                                            </span>
                                            <ul class="list">
                                                <li data-value="1" class="option selected">
                                                    In Stock
                                                </li>
                                                <li data-value="1" class="option">
                                                    Castle In The Sky
                                                </li>
                                                <li data-value="1" class="option">
                                                    The Hidden Mystery Behind
                                                </li>
                                                <li data-value="1" class="option">
                                                    Flovely And Unicom Erna
                                                </li>
                                            </ul>
                                        </div>
                                    </div>
                                    <div class="product-status_sale gap-6 d-flex align-items-center">
                                        <div class="nice-select category" tabindex="0">
                                            <span class="current">
                                                On Sale
                                            </span>
                                            <ul class="list">
                                                <li data-value="1" class="option selected">
                                                    On Sale
                                                </li>
                                                <li data-value="1" class="option">
                                                    Flovely And Unicom Erna
                                                </li>
                                                <li data-value="1" class="option">
                                                    Castle In The Sky
                                                </li>
                                                <li data-value="1" class="option">
                                                    How Deal With Very Bad BOOK
                                                </li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>

                            </div>
                            <div class="single-sidebar-widget mb-50">
                                <div class="wid-title">
                                    <h5>Filter By Price</h5>
                                </div>
                                <div class="range__barcustom">
                                    <div class="slider">
                                        <div class="progress" style="left: 15.29%; right: 58.9%;"></div>
                                    </div>
                                    <div class="range-input">
                                        <input type="range" class="range-min" min="0" max="10000"
                                            value="2500">
                                        <input type="range" class="range-max" min="100" max="10000"
                                            value="7500">
                                    </div>
                                    <div class="range-items">
                                        <div class="price-input">
                                            <div class="d-flex align-items-center">
                                                <a href="shop-left-sidebar.html" class="filter-btn mt-2 me-3">Filter</a>
                                                <div class="field">
                                                    <span>Price:</span>
                                                </div>
                                                <div class="field">
                                                    <span>$</span>
                                                    <input type="number" class="input-min" value="100">
                                                </div>
                                                <div class="separators">-</div>
                                                <div class="field">
                                                    <span>$</span>
                                                    <input type="number" class="input-max" value="1000">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="single-sidebar-widget mb-0">
                                <div class="wid-title">
                                    <h5>By Review</h5>
                                </div>
                                <div class="categories-list">
                                    <label class="checkbox-single d-flex align-items-center">
                                        <span class="d-flex gap-xl-3 gap-2 align-items-center">
                                            <span class="checkbox-area d-center">
                                                <input type="checkbox">
                                                <span class="checkmark d-center"></span>
                                            </span>
                                            <span class="text-color">
                                                <span class="star">
                                                    <i class="fa-solid fa-star"></i>
                                                    <i class="fa-solid fa-star"></i>
                                                    <i class="fa-solid fa-star"></i>
                                                    <i class="fa-solid fa-star"></i>
                                                    <i class="fa-solid fa-star"></i>
                                                </span>
                                                35
                                            </span>
                                        </span>
                                    </label>
                                    <label class="checkbox-single d-flex align-items-center">
                                        <span class="d-flex gap-xl-3 gap-2 align-items-center">
                                            <span class="checkbox-area d-center">
                                                <input type="checkbox">
                                                <span class="checkmark d-center"></span>
                                            </span>
                                            <span class="text-color">
                                                <span class="star">
                                                    <i class="fa-solid fa-star"></i>
                                                    <i class="fa-solid fa-star"></i>
                                                    <i class="fa-solid fa-star"></i>
                                                    <i class="fa-solid fa-star"></i>
                                                    <i class="fa-sharp fa-light fa-star"></i>
                                                </span>
                                                24
                                            </span>
                                        </span>
                                    </label>
                                    <label class="checkbox-single d-flex align-items-center">
                                        <span class="d-flex gap-xl-3 gap-2 align-items-center">
                                            <span class="checkbox-area d-center">
                                                <input type="checkbox">
                                                <span class="checkmark d-center"></span>
                                            </span>
                                            <span class="text-color">
                                                <span class="star">
                                                    <i class="fa-solid fa-star"></i>
                                                    <i class="fa-solid fa-star"></i>
                                                    <i class="fa-solid fa-star"></i>
                                                    <i class="fa-sharp fa-light fa-star"></i>
                                                    <i class="fa-sharp fa-light fa-star"></i>
                                                </span>
                                                15
                                            </span>
                                        </span>
                                    </label>
                                    <label class="checkbox-single d-flex align-items-center">
                                        <span class="d-flex gap-xl-3 gap-2 align-items-center">
                                            <span class="checkbox-area d-center">
                                                <input type="checkbox">
                                                <span class="checkmark d-center"></span>
                                            </span>
                                            <span class="text-color">
                                                <span class="star">
                                                    <i class="fa-solid fa-star"></i>
                                                    <i class="fa-solid fa-star"></i>
                                                    <i class="fa-sharp fa-light fa-star"></i>
                                                    <i class="fa-sharp fa-light fa-star"></i>
                                                    <i class="fa-sharp fa-light fa-star"></i>
                                                </span>
                                                2
                                            </span>
                                        </span>
                                    </label>
                                    <label class="checkbox-single d-flex align-items-center">
                                        <span class="d-flex gap-xl-3 gap-2 align-items-center">
                                            <span class="checkbox-area d-center">
                                                <input type="checkbox">
                                                <span class="checkmark d-center"></span>
                                            </span>
                                            <span class="text-color">
                                                <span class="star">
                                                    <i class="fa-solid fa-star"></i>
                                                    <i class="fa-sharp fa-light fa-star"></i>
                                                    <i class="fa-sharp fa-light fa-star"></i>
                                                    <i class="fa-sharp fa-light fa-star"></i>
                                                    <i class="fa-sharp fa-light fa-star"></i>
                                                </span>
                                                1
                                            </span>
                                        </span>
                                    </label>
                                </div>
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
                                                    <a href="shop-details.html">
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
                                                        <a href="shop-details.html">
                                                            {{ \Illuminate\Support\Str::limit($product->title, 25) }}
                                                        </a>
                                                    </h3>
                                                    <ul class="price-list ">
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
                                                    <div class="shop-button">
                                                        <a href="shop-details.html" class="theme-btn"><i
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
                            <ul>
                                <li><a class="previous" href="shop.html">Previous</a></li>
                                <li><a class="page-numbers" href="shop.html">1</a></li>
                                <li><a class="page-numbers" href="shop.html">2</a></li>
                                <li><a class="page-numbers" href="shop.html">3</a></li>
                                <li><a class="page-numbers" href="shop.html">...</a></li>
                                <li><a class="next" href="shop.html">Next</a></li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection