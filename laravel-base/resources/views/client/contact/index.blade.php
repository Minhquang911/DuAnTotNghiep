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
                <h1>Liên hệ</h1>
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
                            Liên hệ
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('content')
    <!-- Contact Section Start -->
    <section class="contact-section fix section-padding">
        <div class="container">
            <div class="contact-wrapper">
                <div class="row g-4 align-items-center">
                    <div class="col-lg-4">
                        <div class="contact-left-items">
                            <div class="contact-info-area-2">
                                <div class="contact-info-items mb-4">
                                    <div class="icon">
                                        <i class="icon-icon-10"></i>
                                    </div>
                                    <div class="content">
                                        <p>Hỗ trợ 7/24</p>
                                        <h3>
                                            <a href="tel:0987654321">0987654321</a>
                                        </h3>
                                    </div>
                                </div>
                                <div class="contact-info-items mb-4">
                                    <div class="icon">
                                        <i class="icon-icon-11"></i>
                                    </div>
                                    <div class="content">
                                        <p>Liên hệ</p>
                                        <h3>
                                            <a href="mailto:book306store@gmail.com">book306store@gmail.com</a>
                                        </h3>
                                    </div>
                                </div>
                                <div class="contact-info-items border-none">
                                    <div class="icon">
                                        <i class="icon-icon-12"></i>
                                    </div>
                                    <div class="content">
                                        <p>Địa chỉ</p>
                                        <h3>
                                            13 Trịnh Văn Bô, TP Hà Nội
                                        </h3>
                                    </div>
                                </div>
                            </div>
                            <div class="video-image">
                                <img src="{{ asset('client/img/contact.jpg') }}" alt="img">
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-8">
                        <div class="contact-content">
                            <h2>Sẵn sàng để bắt đầu?</h2>
                            <p>
                                Nếu bạn có bất kỳ câu hỏi, góp ý hoặc cần được hỗ trợ, đừng ngần ngại liên hệ với chúng tôi.
                                Đội ngũ chăm sóc khách hàng luôn sẵn sàng hỗ trợ bạn 24/7 để đảm bảo bạn có trải nghiệm tốt
                                nhất. <br />
                                Chúng tôi luôn trân trọng mọi phản hồi từ bạn để không ngừng hoàn thiện và phát triển.
                            </p>
                            <form action="{{ route('client.contact.store') }}" id="contact-form" method="POST"
                                class="contact-form-items">
                                <div class="row g-4">
                                    <div class="col-lg-12 wow fadeInUp" data-wow-delay=".3s">
                                        <div class="form-clt">
                                            <span>Nhập email <span class="text-danger">*</span></span>
                                            <input type="text" name="email" placeholder="Nhập Email">
                                        </div>
                                    </div>
                                    <div class="col-lg-12 wow fadeInUp" data-wow-delay=".5s">
                                        <div class="form-clt">
                                            <span>Nội dung <span class="text-danger">*</span></span>
                                            <textarea name="content" placeholder="Nhập nội dung liên hệ"></textarea>
                                        </div>
                                    </div>
                                    <div class="col-lg-7 wow fadeInUp" data-wow-delay=".9s">
                                        <button type="submit" class="theme-btn">
                                            Gửi yêu cầu <i class="fa-solid fa-arrow-right-long"></i>
                                        </button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!--<< Map Section Start >>-->
    <div class="map-section">
        <div class="map-items">
            <div class="googpemap">
                <iframe
                    src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3723.8639508762258!2d105.74571490520208!3d21.038128992796025!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x313455e940879933%3A0xcf10b34e9f1a03df!2zVHLGsOG7nW5nIENhbyDEkeG6s25nIEZQVCBQb2x5dGVjaG5pYw!5e0!3m2!1svi!2s!4v1751963909291!5m2!1svi!2s"
                    style="border:0;" allowfullscreen="" loading="lazy"></iframe>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        $(document).ready(function() {
            $('#contact-form').on('submit', function(e) {
                e.preventDefault();

                // Lấy dữ liệu
                var email = $('input[name="email"]').val().trim();
                var content = $('textarea[name="content"]').val().trim();
                var errors = [];

                // Validate client
                if (!email) {
                    errors.push('Vui lòng nhập email.');
                } else if (!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email)) {
                    errors.push('Email không hợp lệ.');
                }
                if (!content) {
                    errors.push('Vui lòng nhập nội dung.');
                }

                if (errors.length > 0) {
                    errors.forEach(function(msg) {
                        toastr.error(msg);
                    });
                    return;
                }

                // Gửi AJAX
                $.ajax({
                    url: $(this).attr('action'),
                    method: 'POST',
                    data: {
                        email: email,
                        content: content,
                        _token: '{{ csrf_token() }}'
                    },
                    success: function(res) {
                        toastr.success(res.message || 'Gửi liên hệ thành công!');
                        $('#contact-form')[0].reset();
                    },
                    error: function(xhr) {
                        if (xhr.status === 401) {
                            toastr.error('Bạn cần đăng nhập để gửi yêu cầu.');
                        } else if (xhr.status === 422) {
                            // Lỗi validate từ server
                            var errors = xhr.responseJSON.errors;
                            Object.values(errors).forEach(function(msgArr) {
                                toastr.error(msgArr[0]);
                            });
                        } else {
                            toastr.error('Đã có lỗi xảy ra, vui lòng thử lại.');
                        }
                    }
                });
            });
        });
    </script>
@endpush