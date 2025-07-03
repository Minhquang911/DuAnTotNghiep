<!-- Sticky Header Section start  -->
@php
    $categories = App\Models\Category::withCount('products')->where('is_active', 1)->get();
    $publishers = App\Models\Publisher::withCount('products')->where('is_active', 1)->get();
@endphp
<header class="header-2 sticky-header">
    <div class="mega-menu-wrapper">
        <div class="header-main">
            <div class="container">
                <div class="row">
                    <div class="col-6 col-xl-9">
                        <div class="header-left">
                            <div class="logo">
                                <a href="{{ route('home') }}" class="header-logo">
                                    <img src="{{ asset('client/img/logo/black-logo.png') }}" alt="logo-img"
                                        style="max-width: 200px;">
                                </a>
                            </div>
                            <div class="mean__menu-wrapper">
                                <div class="main-menu">
                                    <nav id="mobile-menu">
                                        <ul>
                                            <li>
                                                <a href="{{ route('home') }}">
                                                    Trang chủ
                                                </a>
                                            </li>
                                            <li class="has-dropdown">
                                                <a href="{{ route('client.products.index') }}">
                                                    Cửa hàng
                                                    <i class="fas fa-angle-down"></i>
                                                </a>
                                                <ul class="submenu">
                                                    @foreach ($categories as $category)
                                                        <li>
                                                            <a href="{{ route('client.products.index', array_merge(request()->except(['category_id', 'page']), ['category_id' => $category->id, 'page' => 1])) }}">
                                                                <span>{{ $category->name }}</span>
                                                                <span>({{ $category->products_count }})</span>
                                                            </a>
                                                        </li>
                                                    @endforeach
                                                </ul>
                                            </li>
                                            <li>
                                                <a href="news.html">
                                                    Bài viết
                                                </a>
                                            </li>
                                            <li>
                                                <a href="contact.html">Liên hệ</a>
                                            </li>
                                        </ul>
                                    </nav>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-6 col-xl-3">
                        <div class="header-right">
                            <div class="category-oneadjust gap-6 d-flex align-items-center">
                                <div class="icon">
                                    <i class="fa-user-pen fa-solid fa-grid-2"></i>
                                </div>
                                <form action="{{ route('client.products.index') }}"
                                    class="search-toggle-box d-md-block" style="width: 500px">
                                    <div class="input-area">
                                        <input type="text" value="{{ old('search', e(request('search'))) }}"
                                            name="search" placeholder="Tìm kiếm sách">
                                        <button class="cmn-btn">
                                            <i class="far fa-search"></i>
                                        </button>
                                    </div>
                                </form>
                            </div>
                            <div class="menu-cart">
                                <a href="{{ route('cart.index') }}" class="cart-icon">
                                    <i class="fa-regular fa-cart-shopping"></i>
                                </a>
                                <div class="header-humbager ml-30">
                                    <a class="sidebar__toggle" href="javascript:void(0)">
                                        <div class="bar-icon-2">
                                            <img src="{{ asset('client/img/icon/icon-13.svg') }}" alt="img">
                                        </div>
                                    </a>
                                </div>
                                @if (auth()->check())
                                    <a href="{{ auth()->check() ? route('user.profile') : route('login') }}"
                                        class="user-icon" title="Tài khoản">
                                        <i class="fa-regular fa-user"></i>
                                    </a>
                                    <a href="{{ route('logout') }}" class="user-icon" title="Đăng xuất"
                                        onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                        <i class="fa-solid fa-arrow-right-from-bracket"></i>
                                    </a>
                                    <form id="logout-form" action="{{ route('logout') }}" method="POST"
                                        style="display: none;">
                                        @csrf
                                    </form>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</header>

<!-- Main Header Section start  -->
<div class="header-2-section">
    <div class="container">
        <div class="header-2-wrapper" style="justify-content: space-between">
            <div class="header-top-logo">
                <a href="{{ route('home') }}">
                    <img src="{{ asset('client/img/logo/black-logo.png') }}" alt="img" style="max-width: 200px;">
                </a>
            </div>
            <div class="header-2-right">
                <div class="category-oneadjust gap-6 d-flex align-items-center">
                    <div class="bd-header__category-nav p-relative">
                        <div class="bd-category__click style-2">
                            <span><i class="icon-icon-15"></i> Danh mục </span>
                        </div>
                        <div class="category__items-2" style="display: none;">
                            <div class="category-item">
                                <nav>
                                    <ul>
                                        @foreach ($categories as $category)
                                            <li>
                                                <a
                                                    href="{{ route('client.products.index', array_merge(request()->except(['category_id', 'page']), ['category_id' => $category->id, 'page' => 1])) }}">
                                                    <span>{{ $category->name }}</span>
                                                    <span>({{ $category->products_count }})</span>
                                                </a>
                                            </li>
                                        @endforeach
                                    </ul>
                                </nav>
                            </div>
                        </div>
                    </div>
                    <form action="{{ route('client.products.index') }}" class="search-toggle-box d-md-block">
                        <div class="input-area">
                            <input type="text" value="{{ old('search', e(request('search'))) }}" name="search"
                                placeholder="Tìm kiếm sách theo từ khóa">
                            <button class="cmn-btn">
                                <i class="far fa-search"></i>
                            </button>
                        </div>
                    </form>
                </div>
                <div class="author-icon">
                    <div class="icon">
                        <img src="{{ asset('client/img/icon/icon-23.svg') }}" alt="icon">
                    </div>
                    <div class="content">
                        <span>Gọi cho chúng tôi</span>
                        <h5>
                            <a href="tel:0123456789">0123456789</a>
                        </h5>
                    </div>
                </div>
                <div class="menu-cart text-end">
                    <a href="{{ route('cart.index') }}" class="cart-icon">
                        <i class="fa-regular fa-cart-shopping"></i>
                    </a>
                    <div class="header-humbager ml-30">
                        <a class="sidebar__toggle" href="javascript:void(0)">
                            <div class="bar-icon-2">
                                <img src="{{ asset('client/img/icon/icon-13.svg') }}" alt="img">
                            </div>
                        </a>
                    </div>
                    @if (auth()->check())
                        <a href="{{ auth()->check() ? route('user.profile') : route('login') }}" class="user-icon"
                            title="Tài khoản">
                            <i class="fa-regular fa-user"></i>
                        </a>
                        <a href="{{ route('logout') }}" class="user-icon" title="Đăng xuất"
                            onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                            <i class="fa-solid fa-arrow-right-from-bracket"></i>
                        </a>
                        <form id="logout-form" action="{{ route('logout') }}" method="POST"
                            style="display: none;">
                            @csrf
                        </form>
                    @else
                        <a href="{{ route('login') }}">
                            <button type="button" class="theme-btn rounded-1">
                                Đăng nhập
                            </button>
                        </a>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>