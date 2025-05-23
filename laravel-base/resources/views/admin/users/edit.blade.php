@extends('layouts.admin.AdminLayout')

@section('content')
<style>
    .card-header.bg-gradient-primary {
        background: linear-gradient(45deg, #4e73df, #224abe);
    }
    .is-invalid {
        border-color: #dc3545 !important;
    }
    .invalid-feedback {
        display: block !important;
        color: #dc3545;
        font-size: 0.875em;
        margin-top: 0.25rem;
    }
    .alert-danger {
        color: #842029;
        background-color: #f8d7da;
        border-color: #f5c2c7;
        padding: 1rem;
        margin-bottom: 1rem;
        border-radius: 0.25rem;
    }
    .alert-danger ul {
        margin-bottom: 0;
        padding-left: 1.5rem;
    }
    .alert-heading {
        color: #842029;
        margin-bottom: 0.5rem;
    }
</style>

<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header bg-gradient-primary">
                    <div class="d-flex justify-content-between align-items-center">
                        <h3 class="card-title text-white fw-bold">
                            <i class="fas fa-user-edit me-2"></i>Chỉnh sửa người dùng
                        </h3>
                    <a href="{{ route('admin.users.index') }}" class="btn btn-light btn-sm">
                        <i class="fas fa-arrow-left me-1"></i> Quay lại
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <h5 class="alert-heading">Vui lòng kiểm tra lại các thông tin sau:</h5>
                            <ul class="mb-0">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form action="{{ route('admin.users.update', $user->id) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label for="name">Họ và tên <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control @error('name') is-invalid @enderror" 
                                        id="name" name="name" value="{{ old('name', $user->name) }}">
                                    @error('name')
                                        <div class="invalid-feedback d-block">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>

                                <div class="form-group mb-3">
                                    <label for="email">Email <span class="text-danger">*</span></label>
                                    <input type="email" class="form-control @error('email') is-invalid @enderror" 
                                        id="email" name="email" value="{{ old('email', $user->email) }}">
                                    @error('email')
                                        <div class="invalid-feedback d-block">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>

                                <div class="form-group mb-3">
                                    <label for="password">Mật khẩu mới</label>
                                    <input type="password" class="form-control @error('password') is-invalid @enderror" 
                                        id="password" name="password">
                                    <small class="text-muted">Để trống nếu không muốn thay đổi mật khẩu</small>
                                    @error('password')
                                        <div class="invalid-feedback d-block">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>

                                <div class="form-group mb-3">
                                    <label for="password_confirmation">Xác nhận mật khẩu mới</label>
                                    <input type="password" class="form-control @error('password_confirmation') is-invalid @enderror" 
                                        id="password_confirmation" name="password_confirmation">
                                    @error('password_confirmation')
                                        <div class="invalid-feedback d-block">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label for="phone">Số điện thoại</label>
                                    <input type="text" class="form-control @error('phone') is-invalid @enderror" 
                                        id="phone" name="phone" value="{{ old('phone', $user->phone) }}">
                                    @error('phone')
                                        <div class="invalid-feedback d-block">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>

                                <div class="form-group mb-3">
                                    <label for="gender">Giới tính</label>
                                    <select class="form-control @error('gender') is-invalid @enderror" 
                                        id="gender" name="gender">
                                        <option value="">Chọn giới tính</option>
                                        <option value="male" {{ old('gender', $user->gender) == 'male' ? 'selected' : '' }}>Nam</option>
                                        <option value="female" {{ old('gender', $user->gender) == 'female' ? 'selected' : '' }}>Nữ</option>
                                    </select>
                                    @error('gender')
                                        <div class="invalid-feedback d-block">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>

                                <div class="form-group mb-3">
                                    <label for="birthday">Ngày sinh</label>
                                    <input type="date" class="form-control @error('birthday') is-invalid @enderror" 
                                        id="birthday" name="birthday" value="{{ old('birthday', $user->birthday ? $user->birthday->format('Y-m-d') : '') }}">
                                    @error('birthday')
                                        <div class="invalid-feedback d-block">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>

                                <div class="form-group mb-3">
                                    <label for="avatar">Ảnh đại diện</label>
                                    @if($user->avatar)
                                        <div class="mb-2">
                                            <img src="{{ asset('storage/' . $user->avatar) }}" alt="Avatar" class="img-thumbnail" style="max-width: 200px">
                                        </div>
                                    @endif
                                    <input type="file" class="form-control @error('avatar') is-invalid @enderror" 
                                        id="avatar" name="avatar" accept="image/*">
                                    <small class="text-muted">Để trống nếu không muốn thay đổi ảnh</small>
                                    @error('avatar')
                                        <div class="invalid-feedback d-block">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>

                                <div class="form-group mb-3">
                                    <label for="is_active">Trạng thái</label>
                                    <select class="form-control @error('is_active') is-invalid @enderror" 
                                        id="is_active" name="is_active">
                                        <option value="1" {{ old('is_active', $user->is_active) == '1' ? 'selected' : '' }}>Hoạt động</option>
                                        <option value="0" {{ old('is_active', $user->is_active) == '0' ? 'selected' : '' }}>Khóa</option>
                                    </select>
                                    @error('is_active')
                                        <div class="invalid-feedback d-block">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <button type="submit" class="btn btn-primary">Cập nhật</button>
                            <a href="{{ route('admin.users.index') }}" class="btn btn-secondary">Quay lại</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 