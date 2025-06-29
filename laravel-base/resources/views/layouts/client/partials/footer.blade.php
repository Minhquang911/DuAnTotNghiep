@php
    $categoriesFootrer = App\Models\Category::withCount('products')
        ->where('is_active', 1)
        ->orderByDesc('products_count')
        ->take(5)
        ->get();
        
    $publishersFootrer = App\Models\Publisher::withCount('products')
        ->where('is_active', 1)
        ->orderByDesc('products_count')
        ->take(5)
        ->get();
@endphp
<footer class="footer-section footer-bg">
    <div class="container">
        <div class="contact-info-area">
            <div class="contact-info-items wow fadeInUp" data-wow-delay=".2s">
                <div class="icon">
                    <i class="icon-icon-5"></i>
                </div>
                <div class="content">
                    <p>Hỗ trợ 7/24</p>
                    <h3>
                        <a href="tel:0987654321">0987654321</a>
                    </h3>
                </div>
            </div>
            <div class="contact-info-items wow fadeInUp" data-wow-delay=".4s">
                <div class="icon">
                    <i class="icon-icon-6"></i>
                </div>
                <div class="content">
                    <p>Liên hệ</p>
                    <h3>
                        <a href="mailto:book306store@gmail.com">book306store@gmail.com</a>
                    </h3>
                </div>
            </div>
            <div class="contact-info-items wow fadeInUp" data-wow-delay=".6s">
                <div class="icon">
                    <i class="icon-icon-7"></i>
                </div>
                <div class="content">
                    <p>Giờ hoạt động</p>
                    <h3>
                        T2-T6, 09am -05pm
                    </h3>
                </div>
            </div>
            <div class="contact-info-items wow fadeInUp" data-wow-delay=".8s">
                <div class="icon">
                    <i class="icon-icon-8"></i>
                </div>
                <div class="content">
                    <p>Địa chỉ</p>
                    <h3>
                        13 Trịnh Văn Bô, TP Hà Nội
                    </h3>
                </div>
            </div>
        </div>
    </div>
    <div class="footer-widgets-wrapper">
        <div class="plane-shape float-bob-y">
            <img src="{{ asset('client/img/plane-shape.png') }}" alt="img">
        </div>
        <div class="container">
            <div class="row">
                <div class="col-xl-3 col-lg-4 col-md-6 wow fadeInUp" data-wow-delay=".2s">
                    <div class="single-footer-widget">
                        <div class="widget-head">
                            <a href="{{ route('home') }}">
                                <img src="{{ asset('client/img/logo/black-logo.png') }}" alt="logo-img"
                                    style="max-width: 200px;">
                            </a>
                        </div>
                        <div class="footer-content">
                            <p>
                                Book360 - Thiên đường sách trực tuyến của bạn.
                            </p>
                            <div class="social-icon d-flex align-items-center">
                                <a href="https://www.facebook.com/"><i class="fab fa-facebook-f"></i></a>
                                <a href="https://x.com/"><i class="fab fa-twitter"></i></a>
                                <a href="https://www.youtube.com/"><i class="fab fa-youtube"></i></a>
                                <a href="https://www.linkedin.com/"><i class="fab fa-linkedin-in"></i></a>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xl-3 col-lg-4 col-md-6 ps-lg-5 wow fadeInUp" data-wow-delay=".4s">
                    <div class="single-footer-widget">
                        <div class="widget-head">
                            <h3>Nhà xuất bản</h3>
                        </div>
                        <ul class="list-area">
                            @foreach ($publishersFootrer as $publisher)
                                <li>
                                    <a href="">
                                        <i class="fa-solid fa-chevrons-right"></i>
                                        <span>{{ $publisher->name }}</span>
                                        <span>({{ $publisher->products_count }})</span>
                                    </a>
                                </li>
                            @endforeach

                        </ul>
                    </div>
                </div>
                <div class="col-xl-3 col-lg-4 col-md-6 ps-lg-5 wow fadeInUp" data-wow-delay=".6s">
                    <div class="single-footer-widget">
                        <div class="widget-head">
                            <h3>Danh mục</h3>
                        </div>
                        <ul class="list-area">
                            @foreach ($categoriesFootrer as $category)
                                <li>
                                    <a href="">
                                        <i class="fa-solid fa-chevrons-right"></i>
                                        <span>{{ $category->name }}</span>
                                        <span>({{ $category->products_count }})</span>
                                    </a>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                </div>
                <div class="col-xl-3 col-lg-4 col-md-6 wow fadeInUp" data-wow-delay=".8s">
                    <div class="single-footer-widget">
                        <div class="widget-head">
                            <h3>Tin tức</h3>
                        </div>
                        <div class="footer-content">
                            <p>Đăng ký nhận bản tin hàng tuần của searing để nhận thông tin cập nhật mới nhất.</p>
                            <div class="footer-input">
                                <input type="email" id="email2" placeholder="Nhập địa chỉ email">
                                <button class="newsletter-btn" type="submit">
                                    <i class="fa-regular fa-paper-plane"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="footer-bottom">
        <div class="container">
            <div class="footer-wrapper d-flex align-items-center justify-content-between">
                <p class="wow fadeInLeft" data-wow-delay=".3s">
                    © All Copyright 2025 by <a href="{{ route('home') }}">Book360</a>
                </p>
                <ul class="brand-logo wow fadeInRight" data-wow-delay=".5s">
                    <li>
                        <a href="contact.html">
                            <img src="{{ asset('client/img/visa-logo.png') }}" alt="img">
                        </a>
                    </li>
                    <li>
                        <a href="contact.html">
                            <img src="{{ asset('client/img/mastercard.png') }}" alt="img">
                        </a>
                    </li>
                    <li>
                        <a href="contact.html">
                            <img src="{{ asset('client/img/payoneer.png') }}" alt="img">
                        </a>
                    </li>
                    <li>
                        <a href="contact.html">
                            <img src="{{ asset('client/img/affirm.png') }}" alt="img">
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</footer>