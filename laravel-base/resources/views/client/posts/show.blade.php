
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
                <h1>Chi tiết bài viết</h1>
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
                            Chi tiết bài viết
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('content')
    <section class="news-details fix section-padding">
        <div class="container">
            <div class="news-details-area">
                <div class="row g-5">
                    <div class="col-xl-9 col-lg-8">
                        <div class="blog-post-details">
                            <div class="single-blog-post">
                                <div class="post-featured-thumb bg-cover"
                                    style="background-image: url('{{ asset('storage/' . $post->image) }}');"></div>
                                <div class="post-content">
                                    <ul class="post-list d-flex align-items-center">
                                        <li>
                                            <i class="fa-light fa-user"></i>
                                            By Admin
                                        </li>
                                        <li>
                                            <i class="fa-light fa-calendar-days"></i>
                                            {{ $post->created_at->format('d/m/Y H:i') }}
                                        </li>
                                    </ul>
                                    <h3>{{ $post->title }}</h3>
                                    <p class="mb-3">
                                        {!! $post->content !!}
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-3 col-lg-4">
                        <div class="main-sidebar">
                            <div class="single-sidebar-widget">
                                <div class="wid-title">
                                    <h3>Bài viết mới</h3>
                                </div>
                                <div class="recent-post-area">
                                    @foreach ($posts as $post)
                                        <div class="recent-items">
                                            <div class="recent-thumb">
                                                <img src="{{ asset('storage/' . $post->image) }}" alt="img" width="50px">
                                            </div>
                                            <div class="recent-content">
                                                <ul>
                                                    <li>
                                                        <i class="fa-solid fa-calendar-days"></i>
                                                        {{ $post->created_at->format('d/m/Y H:i') }}
                                                    </li>
                                                </ul>
                                                <h6>
                                                    <a href="{{ route('client.posts.show', $post->slug) }}">
                                                        {{ $post->title }}
                                                    </a>
                                                </h6>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

@push('scripts')
@endpush
