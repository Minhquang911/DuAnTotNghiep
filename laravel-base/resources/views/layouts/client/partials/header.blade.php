<!-- Sticky Header Section start  -->
<header class="header-2 sticky-header">
    <div class="mega-menu-wrapper">
        <div class="header-main">
            <div class="container">
                <div class="row">
                    <div class="col-6 col-xl-9">
                        <div class="header-left">
                            <div class="logo">
                                <a href="index.html" class="header-logo">
                                    <img src="{{ asset('client/img/logo/black-logo.svg') }}" alt="logo-img">
                                </a>
                            </div>
                            <div class="mean__menu-wrapper">
                                <div class="main-menu">
                                    <nav id="mobile-menu">
                                        <ul>
                                            <li>
                                                <a href="index.html">
                                                    Home
                                                    <i class="fas fa-angle-down"></i>
                                                </a>
                                                <ul class="submenu">
                                                    <li><a href="index.html">Home 01</a></li>
                                                    <li><a href="index-2.html">Home 02</a></li>
                                                </ul>
                                            </li>
                                            <li>
                                                <a href="shop.html">
                                                    Shop
                                                    <i class="fas fa-angle-down"></i>
                                                </a>
                                                <ul class="submenu">
                                                    <li><a href="shop.html">Shop Default</a></li>
                                                    <li><a href="shop-list.html">Shop List</a></li>
                                                    <li><a href="shop-details.html">Shop Details</a></li>
                                                    <li><a href="shop-cart.html">Shop Cart</a></li>
                                                    <li><a href="wishlist.html">Wishlist</a></li>
                                                    <li><a href="checkout.html">Checkout</a></li>
                                                </ul>
                                            </li>
                                            <li class="has-dropdown">
                                                <a href="about.html">
                                                    Pages
                                                    <i class="fas fa-angle-down"></i>
                                                </a>
                                                <ul class="submenu">
                                                    <li><a href="about.html">About Us</a></li>
                                                    <li class="has-dropdown">
                                                        <a href="team.html">
                                                            Author
                                                            <i class="fas fa-angle-down"></i>
                                                        </a>
                                                        <ul class="submenu">
                                                            <li><a href="team.html">Author</a></li>
                                                            <li><a href="team-details.html">Author Profile</a></li>
                                                        </ul>
                                                    </li>
                                                    <li><a href="faq.html">Faq's</a></li>
                                                    <li><a href="404.html">404 Page</a></li>
                                                </ul>
                                            </li>
                                            <li>
                                                <a href="news.html">
                                                    Blog
                                                    <i class="fas fa-angle-down"></i>
                                                </a>
                                                <ul class="submenu">
                                                    <li><a href="news-grid.html">Blog Grid</a></li>
                                                    <li><a href="news.html">Blog List</a></li>
                                                    <li><a href="news-details.html">Blog Details</a></li>
                                                </ul>
                                            </li>
                                            <li>
                                                <a href="contact.html">Contact</a>
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
                                    <i class="fa-sharp fa-solid fa-grid-2"></i>
                                </div>
                                <select name="cate" class="category">
                                    <option value="1">
                                        Category
                                    </option>
                                    <option value="1">
                                        Web Design
                                    </option>
                                    <option value="1">
                                        Web Development
                                    </option>
                                    <option value="1">
                                        Graphic Design
                                    </option>
                                    <option value="1">
                                        Softwer Eng
                                    </option>
                                </select>
                                <form action="#" class="search-toggle-box d-md-block">
                                    <div class="input-area">
                                        <input type="text" placeholder="Author">
                                        <button class="cmn-btn">
                                            <i class="far fa-search"></i>
                                        </button>
                                    </div>
                                </form>
                            </div>
                            <div class="menu-cart">
                                <a href="wishlist.html" class="cart-icon">
                                    <i class="fa-regular fa-heart"></i>
                                </a>
                                <a href="shop-cart.html" class="cart-icon">
                                    <i class="fa-regular fa-cart-shopping"></i>
                                </a>
                                <div class="header-humbager ml-30">
                                    <a class="sidebar__toggle" href="javascript:void(0)">
                                        <div class="bar-icon-2">
                                            <img src="{{ asset('client/img/icon/icon-13.svg') }}" alt="img">
                                        </div>
                                    </a>
                                </div>
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
        <div class="header-2-wrapper">
            <div class="header-top-logo">
                <a href="index.html">
                    <img src="{{ asset('client/img/logo/black-logo.svg') }}" alt="img">
                </a>
            </div>
            <div class="header-2-right">

                <div class="category-oneadjust gap-6 d-flex align-items-center">
                    <div class="bd-header__category-nav p-relative">
                        <div class="bd-category__click style-2">
                            <span><i class="icon-icon-15"></i> Categories </span>
                        </div>
                        <div class="category__items-2" style="display: none;">
                            <div class="category-item">
                                <nav>
                                    <ul>
                                        <li>
                                            <a href="shop-details.html">
                                                <span>Novel Books</span>
                                                <span>(8)</span>
                                            </a>
                                        </li>
                                        <li>
                                            <a href="shop-details.html">
                                                <span>Poetry Books</span>
                                                <span>(5)</span>
                                            </a>
                                        </li>
                                        <li>
                                            <a href="shop-details.html">
                                                <span>History Books</span>
                                                <span>(7)</span>
                                            </a>
                                        </li>
                                        <li>
                                            <a href="shop-details.html">
                                                <span>Movement Books</span>
                                                <span>(3)</span>
                                            </a>
                                        </li>
                                        <li>
                                            <a href="shop-details.html">
                                                <span>Independence Books </span>
                                                <span>(4)</span>
                                            </a>
                                        </li>
                                        <li>
                                            <a href="shop-details.html">
                                                <span>Technology Books</span>
                                                <span>(2)</span>
                                            </a>
                                        </li>
                                        <li>
                                            <a href="shop-details.html">
                                                <span>Political Books</span>
                                                <span>(1)</span>
                                            </a>
                                        </li>
                                        <li>
                                            <a href="shop-details.html">
                                                <span>Romantic Books</span>
                                                <span>(7)</span>
                                            </a>
                                        </li>
                                    </ul>
                                </nav>
                            </div>
                        </div>
                    </div>
                    <form action="#" class="search-toggle-box d-md-block">
                        <div class="input-area">
                            <input type="text" placeholder="Search For books for keyword">
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
                        <span>Call Us Now</span>
                        <h5>
                            <a href="tel:+2085550112">+208-555-0112</a>
                        </h5>
                    </div>
                </div>
                <div class="menu-cart">
                    <a href="wishlist.html" class="cart-icon">
                        <i class="fa-regular fa-heart"></i>
                    </a>
                    <a href="shop-cart.html" class="cart-icon">
                        <i class="fa-regular fa-cart-shopping"></i>
                    </a>
                    <div class="header-humbager ml-30">
                        <a class="sidebar__toggle" href="javascript:void(0)">
                            <div class="bar-icon-2">
                                <img src="{{ asset('client/img/icon/icon-13.svg') }}" alt="img">
                            </div>
                        </a>
                    </div>
                    <button type="button" class="theme-btn rounded-1" data-bs-toggle="modal"
                        data-bs-target="#registrationModal">Sign Up</button>
                </div>
            </div>
        </div>
    </div>
</div>
