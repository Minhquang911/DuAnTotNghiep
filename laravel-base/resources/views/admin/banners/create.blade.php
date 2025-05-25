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

        .image-preview {
            max-width: 100%;
            height: 200px;
            object-fit: cover;
            border-radius: 0.25rem;
            display: none;
        }

        .image-preview-container {
            position: relative;
            margin-bottom: 1rem;
        }

        .image-preview-container .remove-image {
            position: absolute;
            top: 0.5rem;
            right: 0.5rem;
            background: rgba(255, 255, 255, 0.9);
            border-radius: 50%;
            padding: 0.25rem;
            cursor: pointer;
            display: none;
        }

        .image-preview-container:hover .remove-image {
            display: block;
        }

        .custom-file-label {
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
        }
    </style>

    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header bg-gradient-primary">
                        <div class="d-flex justify-content-between align-items-center">
                            <h3 class="card-title text-white fw-bold">
                                <i class="fas fa-plus me-2"></i>Thêm Banner Mới
                            </h3>
                            <a href="{{ route('admin.banners.index') }}" class="btn btn-light btn-sm">
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

                        <form action="{{ route('admin.banners.store') }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <div class="row">
                                <div class="col-md-8">
                                    <div class="form-group mb-3">
                                        <label for="title">Tiêu đề banner <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control @error('title') is-invalid @enderror"
                                            id="title" name="title" value="{{ old('title') }}"
                                            placeholder="Nhập tiêu đề banner">
                                        @error('title')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="form-group mb-3">
                                        <label for="description">Mô tả</label>
                                        <textarea class="form-control @error('description') is-invalid @enderror" id="description" name="description"
                                            rows="3" placeholder="Nhập mô tả banner">{{ old('description') }}</textarea>
                                        @error('description')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="form-group mb-3">
                                        <label for="link">Link</label>
                                        <div class="input-group">
                                            <span class="input-group-text">
                                                <i class="fas fa-link"></i>
                                            </span>
                                            <input type="url" class="form-control @error('link') is-invalid @enderror"
                                                id="link" name="link" value="{{ old('link') }}"
                                                placeholder="https://example.com">
                                        </div>
                                        @error('link')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group mb-3">
                                                <label for="start_date">Ngày bắt đầu <span
                                                        class="text-danger">*</span></label>
                                                <input type="datetime-local"
                                                    class="form-control @error('start_date') is-invalid @enderror"
                                                    id="start_date" name="start_date" value="{{ old('start_date') }}">
                                                @error('start_date')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group mb-3">
                                                <label for="end_date">Ngày kết thúc <span
                                                        class="text-danger">*</span></label>
                                                <input type="datetime-local"
                                                    class="form-control @error('end_date') is-invalid @enderror"
                                                    id="end_date" name="end_date" value="{{ old('end_date') }}">
                                                @error('end_date')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <div class="form-group mb-3">
                                        <label for="image">Hình ảnh banner <span class="text-danger">*</span></label>
                                        <div class="image-preview-container">
                                            <img id="imagePreview" class="image-preview mb-2" src="#" alt="Preview">
                                            <button type="button" class="remove-image btn btn-sm btn-danger"
                                                onclick="removeImage()">
                                                <i class="fas fa-times"></i>
                                            </button>
                                        </div>
                                        <div class="custom-file">
                                            <input type="file" class="form-control @error('image') is-invalid @enderror"
                                                id="image" name="image" accept="image/*"
                                                onchange="previewImage(this)">
                                            <div class="form-text">Kích thước đề xuất: 1920x600px. Định dạng: JPG, PNG, GIF
                                            </div>
                                        </div>
                                        @error('image')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="form-group mb-3">
                                        <label for="is_active">Trạng thái</label>
                                        <div class="form-check form-switch">
                                            <input class="form-check-input" type="checkbox" id="is_active"
                                                name="is_active" value="1" {{ old('is_active', 1) ? 'checked' : '' }}>
                                            <label class="form-check-label" for="is_active">Hiển thị banner</label>
                                        </div>
                                        @error('is_active')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <div class="form-group mt-4">
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-save me-1"></i> Lưu banner
                                </button>
                                <a href="{{ route('admin.banners.index') }}" class="btn btn-secondary">
                                    <i class="fas fa-times me-1"></i> Hủy
                                </a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        function previewImage(input) {
            const preview = document.getElementById('imagePreview');
            const removeBtn = document.querySelector('.remove-image');

            if (input.files && input.files[0]) {
                const reader = new FileReader();

                reader.onload = function(e) {
                    preview.src = e.target.result;
                    preview.style.display = 'block';
                    removeBtn.style.display = 'block';
                }

                reader.readAsDataURL(input.files[0]);
            }
        }

        function removeImage() {
            const preview = document.getElementById('imagePreview');
            const input = document.getElementById('image');
            const removeBtn = document.querySelector('.remove-image');

            preview.src = '#';
            preview.style.display = 'none';
            removeBtn.style.display = 'none';
            input.value = '';
        }

        // Validate end date must be after start date
        document.getElementById('end_date').addEventListener('change', function() {
            const startDate = new Date(document.getElementById('start_date').value);
            const endDate = new Date(this.value);

            if (endDate <= startDate) {
                alert('Ngày kết thúc phải sau ngày bắt đầu!');
                this.value = '';
            }
        });

        document.getElementById('start_date').addEventListener('change', function() {
            const startDate = new Date(this.value);
            const endDate = new Date(document.getElementById('end_date').value);

            if (endDate && endDate <= startDate) {
                alert('Ngày kết thúc phải sau ngày bắt đầu!');
                document.getElementById('end_date').value = '';
            }
        });
    </script>
@endpush