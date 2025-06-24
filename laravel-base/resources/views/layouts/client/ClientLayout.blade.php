<!DOCTYPE html>
<html lang="en">
<!--<< Header Area >>-->


<!-- Mirrored from gramentheme.com/html/bookle/index-2.html by HTTrack Website Copier/3.x [XR&CO'2014], Sat, 07 Jun 2025 01:58:09 GMT -->
<head>
    <!-- ========== Meta Tags ========== -->
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="author" content="gramentheme">
    <meta name="description" content="Book360 Store WooCommerce ">
    <!-- ======== Page title ============ -->
    <title>@yield('title', 'Book360 Store WooCommerce')</title>
    <!--<< Favcion >>-->
    <link rel="shortcut icon" href="{{ asset('client/img/favicon.png') }}">
    <!--<< Bootstrap min.css >>-->
    <link rel="stylesheet" href="{{ asset('client/css/bootstrap.min.css') }}">
    <!--<< All Min Css >>-->
    <link rel="stylesheet" href="{{ asset('client/css/all.min.css') }}">
    <!--<< Animate.css >>-->
    <link rel="stylesheet" href="{{ asset('client/css/animate.css') }}">
    <!--<< Magnific Popup.css >>-->
    <link rel="stylesheet" href="{{ asset('client/css/magnific-popup.css') }}">
    <!--<< MeanMenu.css >>-->
    <link rel="stylesheet" href="{{ asset('client/css/meanmenu.css') }}">
    <!--<< Swiper Bundle.css >>-->
    <link rel="stylesheet" href="{{ asset('client/css/swiper-bundle.min.css') }}">
    <!--<< Nice Select.css >>-->
    <link rel="stylesheet" href="{{ asset('client/css/nice-select.css') }}">
    <!--<< Icomoon.css >>-->
    <link rel="stylesheet" href="{{ asset('client/css/icomoon.css') }}">
    <!--<< Main.css >>-->
    <link rel="stylesheet" href="{{ asset('client/css/main.css') }}">
</head>

<body>
<!-- Preloader Start -->
@include('layouts.client.partials.preloader')

<!-- Offcanvas Area Start -->
@include('layouts.client.partials.offcanvas')

@include('layouts.client.partials.header')

@yield('banner')

@yield('content')

<!-- Footer Section Start -->
@include('layouts.client.partials.footer')

<!--<< All JS Plugins >>-->
<script src="{{ asset('client/js/jquery-3.7.1.min.js') }}"></script>
<!--<< Viewport Js >>-->
<script src="{{ asset('client/js/viewport.jquery.js') }}"></script>
<!--<< Bootstrap Js >>-->
<script src="{{ asset('client/js/bootstrap.bundle.min.js') }}"></script>
<!--<< Nice Select Js >>-->
<script src="{{ asset('client/js/jquery.nice-select.min.js') }}"></script>
<!--<< Waypoints Js >>-->
<script src="{{ asset('client/js/jquery.waypoints.js') }}"></script>
<!--<< Counterup Js >>-->
<script src="{{ asset('client/js/jquery.counterup.min.js') }}"></script>
<!--<< Swiper Slider Js >>-->
<script src="{{ asset('client/js/swiper-bundle.min.js') }}"></script>
<!--<< MeanMenu Js >>-->
<script src="{{ asset('client/js/jquery.meanmenu.min.js') }}"></script>
<!--<< Magnific Popup Js >>-->
<script src="{{ asset('client/js/jquery.magnific-popup.min.js') }}"></script>
<!--<< Wow Animation Js >>-->
<script src="{{ asset('client/js/wow.min.js') }}"></script>
<!-- Gsap -->
<script src="{{ asset('client/js/gsap.min.js') }}"></script>
<!--<< Main.js >>-->
<script src="{{ asset('client/js/main.js') }}"></script>

@stack('scripts')
</body>


<!-- Mirrored from gramentheme.com/html/bookle/index-2.html by HTTrack Website Copier/3.x [XR&CO'2014], Sat, 07 Jun 2025 01:58:13 GMT -->
</html>
