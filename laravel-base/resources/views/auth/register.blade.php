@extends('layouts.app')

@section('content')
    <div class="fxt-form">
        <h2>Đăng ký</h2>
        <p>Tạo tài khoản miễn phí và tận hưởng dịch vụ</p>
        <form method="POST" action="{{ route('register') }}">
            @csrf
            <div class="form-group">
                <div class="fxt-transformY-50 fxt-transition-delay-1">
                    <input type="text" class="form-control @error('name') is-invalid @enderror" name="name"
                           value="{{ old('name') }}" placeholder="Họ và tên" required autocomplete="name" autofocus>
                    @if (!$errors->has('name'))
                        <i class="flaticon-user"></i>
                    @endif
                    @error('name')
                    <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>
            </div>
            <div class="form-group">
                <div class="fxt-transformY-50 fxt-transition-delay-1">
                    <input type="email" class="form-control @error('email') is-invalid @enderror" name="email"
                           value="{{ old('email') }}" placeholder="Email" required autocomplete="email">
                    @if (!$errors->has('email'))
                        <i class="flaticon-envelope"></i>
                    @endif
                    @error('email')
                    <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>
            </div>
            <div class="form-group">
                <div class="fxt-transformY-50 fxt-transition-delay-2">
                    <input type="password" class="form-control @error('password') is-invalid @enderror" name="password"
                           placeholder="Mật khẩu" required autocomplete="new-password">
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
                <div class="fxt-transformY-50 fxt-transition-delay-2">
                    <input type="password" class="form-control" name="password_confirmation" placeholder="Xác nhận mật khẩu"
                           required autocomplete="new-password">
                    <i class="flaticon-padlock"></i>
                </div>
            </div>
            <div class="form-group">
                <div class="fxt-transformY-50 fxt-transition-delay-3">
                    <button type="submit" class="fxt-btn-fill">Đăng ký</button>
                </div>
            </div>
        </form>
    </div>
@endsection
