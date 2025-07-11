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
                <h1>Tin tức</h1>
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
                            Tin tức
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('content')
    <section class="news-section fix section-padding">
        <div class="container">
            <div class="row g-4">
                @foreach ($posts as $post)
                    <div class="col-xl-3 col-lg-4 col-md-6 wow fadeInUp" data-wow-delay=".2s">
                        <div class="news-card-items style-2 mt-0" style="height: 415px">
                            <div class="news-image" style="height: 230px">
                                <img src="{{ asset('storage/' . $post->image) }}" alt="img">
                                <img src="{{ asset('storage/' . $post->image) }}" alt="img">
                                <div class="post-box">
                                    News
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
                                <h3><a href="{{ route('client.posts.show', $post->slug) }}">{{ $post->title }}</a></h3>
                                <a href="{{ route('client.posts.show', $post->slug) }}" class="theme-btn-2">Chi tiết <i
                                        class="fa-regular fa-arrow-right-long"></i></a>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
            <div class="page-nav-wrap text-center">
                <div class="mt-4">
                    {{ $posts->links('pagination::bootstrap-5') }}
                </div>
            </div>
        </div>
    </section>
@endsection

@push('scripts')
@endpush