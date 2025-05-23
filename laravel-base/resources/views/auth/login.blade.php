@extends('layouts.app')

@section('content')
    <div class="fxt-form">
        <h2>Đăng nhập</h2>
        <p>Đăng nhập để tiếp tục trên trang web của chúng tôi</p>
        <form method="POST" action="{{ route('login') }}">
            @csrf

            <div class="form-group">
                <div class="fxt-transformY-50 fxt-transition-delay-1">
                    <input id="email" type="email" class="form-control @error('email') is-invalid @enderror"
                           name="email" value="{{ old('email') }}" required autocomplete="email" placeholder="Email Address">
                    @if (!$errors->has('email'))
                        <i class="flaticon-envelope"></i>
                    @endif

                    @error('email')
                    <span class="invalid-feedback" role="alert">
                            {{ $message }}
                        </span>
                    @enderror
                </div>
            </div>
            <div class="form-group">
                <div class="fxt-transformY-50 fxt-transition-delay-2">
                    <input id="password" type="password" class="form-control @error('password') is-invalid @enderror"
                           name="password" required placeholder="Password" autocomplete="current-password">

                    @if (!$errors->has('password'))
                        <i class="flaticon-padlock"></i>
                    @endif

                    @error('password')
                    <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>
            </div>
            <div class="form-group">
                <div class="fxt-transformY-50 fxt-transition-delay-3">
                    <div class="fxt-content-between">
                        <button type="submit" class="fxt-btn-fill">Đăng nhập</button>
                        <a href="{{ route('password.request') }}" class="switcher-text2">Quên mật
                            khẩu</a>
                    </div>
                </div>
            </div>
        </form>
    </div>
@endsection
