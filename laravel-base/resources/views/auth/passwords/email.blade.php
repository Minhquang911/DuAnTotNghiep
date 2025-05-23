@extends('layouts.app')

@section('content')
    <div class="fxt-form">
        <h2>Quên mật khẩu</h2>
        <p>Khôi phục mật khẩu của bạn</p>
        @if (session('status'))
            <div class="alert alert-success" role="alert">
                {{ session('status') }}
            </div>
        @endif
        <form method="POST" action="{{ route('password.email') }}">
            @csrf
            <div class="form-group">
                <div class="fxt-transformY-50 fxt-transition-delay-1">
                    <input type="email" class="form-control @error('email') is-invalid @enderror"
                           name="email" value="{{ old('email') }}" placeholder="Email" required autocomplete="email" autofocus>
                    @if(!$errors->has('email'))
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
                <div class="fxt-transformY-50 fxt-transition-delay-3">
                    <button type="submit" class="fxt-btn-fill">Gửi email khôi phục</button>
                </div>
            </div>
        </form>
    </div>
@endsection
