<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">


<!-- Mirrored from affixtheme.com/html/xmee/demo/login-15.html by HTTrack Website Copier/3.x [XR&CO'2014], Tue, 20 May 2025 01:25:23 GMT -->

<head>
    <meta charset="utf-8">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <title>{{ config('app.name', 'Book360') }}</title>
    <meta name="description" content="">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- Favicon -->
    <link rel="shortcut icon" type="image/x-icon" href="img/favicon.png">
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="{{ asset('auth/css/bootstrap.min.css') }}">
    <!-- Fontawesome CSS -->
    <link rel="stylesheet" href="{{ asset('auth/css/fontawesome-all.min.css') }}">
    <!-- Flaticon CSS -->
    <link rel="stylesheet" href="{{ asset('auth/font/flaticon.css') }}">
    <!-- Google Web Fonts -->
    <link href="https://fonts.googleapis.com/css?family=Roboto:300,400,500,700&amp;display=swap" rel="stylesheet">
    <!-- Custom CSS -->
    <link rel="stylesheet" href="{{ asset('auth/style.css') }}">
</head>

<body>
    <!--[if lt IE 8]>
        <p class="browserupgrade">You are using an <strong>outdated</strong> browser. Please <a href="http://browsehappy.com/">upgrade your browser</a> to improve your experience.</p>
    <![endif]-->
    <div id="preloader" class="preloader">
        <div class='inner'>
            <div class='line1'></div>
            <div class='line2'></div>
            <div class='line3'></div>
        </div>
    </div>
    <section class="fxt-template-animation fxt-template-layout15">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-6 col-12 fxt-bg-img" data-bg-image="{{ asset('auth/img/figure/bg15-l.jpg') }}">
                    <div class="fxt-intro">
                        <h1>Chào mừng tới Book360</h1>
                        <p style="text-align: justify;">Book360 - Thiên đường sách trực tuyến của bạn. Khám phá kho tàng
                            sách phong phú với hàng ngàn đầu sách từ văn học, kinh tế, kỹ năng sống đến sách thiếu nhi.
                            Với dịch vụ giao hàng nhanh chóng và giá cả hợp lý, Book360 cam kết mang đến trải nghiệm mua
                            sắm tuyệt vời cho độc giả.</p>
                        <div class="fxt-page-switcher">
                            <a class="switcher-text1 {{ Route::is('login') ? 'active' : '' }}"
                                href="{{ route('login') }}">{{ __('Đăng nhập') }}</a>
                            <a class="switcher-text1 {{ Route::is('register') ? 'active' : '' }}"
                                href="{{ route('register') }}">{{ __('Đăng ký') }}</a>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 col-12 fxt-bg-color">
					<div class="fxt-content">
						{{-- <div class="fxt-header">
							<!-- Logo -->
							<a href="login-15.html" class="fxt-logo"><img src="{{ asset('auth/img/logo-15.png') }}" alt="Logo"></a>
						</div> --}}

						@yield('content')
                        
                        <div class="fxt-footer">
                            <ul class="fxt-socials">
                                <li class="fxt-facebook fxt-transformY-50 fxt-transition-delay-4">
                                    <a href="#" title="Facebook"><i class="fab fa-facebook-f"></i></a>
                                </li>
                                <li class="fxt-twitter fxt-transformY-50 fxt-transition-delay-5">
                                    <a href="#" title="twitter"><i class="fab fa-twitter"></i></a>
                                </li>
                                <li class="fxt-google fxt-transformY-50 fxt-transition-delay-6">
                                    <a href="#" title="google"><i class="fab fa-google-plus-g"></i></a>
                                </li>
                                <li class="fxt-linkedin fxt-transformY-50 fxt-transition-delay-7">
                                    <a href="#" title="linkedin"><i class="fab fa-linkedin-in"></i></a>
                                </li>
                                <li class="fxt-pinterest fxt-transformY-50 fxt-transition-delay-8">
                                    <a href="#" title="pinterest"><i class="fab fa-pinterest-p"></i></a>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- jquery-->
    <script src="{{ asset('auth/js/jquery.min.js') }}"></script>
    <!-- Bootstrap js -->
    <script src="{{ asset('auth/js/bootstrap.min.js') }}"></script>
    <!-- Imagesloaded js -->
    <script src="{{ asset('auth/js/imagesloaded.pkgd.min.js') }}"></script>
    <!-- Validator js -->
    <script src="{{ asset('auth/js/validator.min.js') }}"></script>
    <!-- Custom Js -->
    <script src="{{ asset('auth/js/main.js') }}"></script>

</body>


<!-- Mirrored from affixtheme.com/html/xmee/demo/login-15.html by HTTrack Website Copier/3.x [XR&CO'2014], Tue, 20 May 2025 01:25:26 GMT -->

</html>