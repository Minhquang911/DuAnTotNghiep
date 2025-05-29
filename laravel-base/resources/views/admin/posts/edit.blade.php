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

        .current-image {
            border: 2px solid #e3e6f0;
            padding: 0.25rem;
            border-radius: 0.25rem;
            margin-bottom: 1rem;
        }

        .current-image-label {
            font-size: 0.875rem;
            color: #6c757d;
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
                                <i class="fas fa-edit me-2"></i>Chỉnh Sửa Bài Viết
                            </h3>
                            <a href="{{ route('admin.posts.index') }}" class="btn btn-light btn-sm">
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

                        <form action="{{ route('admin.posts.update', $post->id) }}" method="POST"
                              enctype="multipart/form-data">
                            @csrf
                            @method('PUT')
                            <div class="row">
                                <div class="col-md-8">
                                    <div class="form-group mb-3">
                                        <label for="title">Tiêu đề bài viết <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control @error('title') is-invalid @enderror"
                                               id="title" name="title" value="{{ old('title', $post->title) }}"
                                               placeholder="Nhập tiêu đề bài viết">
                                        @error('title')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="form-group mb-3">
                                        <label for="content">Nội dung bài viết <span class="text-danger">*</span></label>
                                        <textarea class="form-control @error('content') is-invalid @enderror" id="content" name="content" rows="6"
                                                  placeholder="Nhập nội dung bài viết">{{ old('content', $post->content) }}</textarea>
                                        @error('content')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <div class="form-group mb-3">
                                        <label for="image">Hình ảnh bài viết <span class="text-danger">*</span></label>
                                        <div class="image-preview-container">
                                            @if ($post->image)
                                                <img id="imagePreview" class="image-preview mb-2"
                                                     src="{{ asset('storage/' . $post->image) }}" alt="Preview">
                                            @else
                                                <img id="imagePreview" class="image-preview mb-2" src="#"
                                                     alt="Preview">
                                            @endif
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
                                        <label for="is_published">Trạng thái</label>
                                        <div class="form-check form-switch">
                                            <input class="form-check-input" type="checkbox" id="is_published"
                                                   name="is_published" value="1"
                                                {{ old('is_published', $post->is_published) ? 'checked' : '' }}>
                                            <label class="form-check-label" for="is_published">Hiển thị bài viết</label>
                                        </div>
                                        @error('is_published')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <div class="form-group mt-4">
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-save me-1"></i> Lưu bài viết
                                </button>
                                <a href="{{ route('admin.posts.index') }}" class="btn btn-secondary">
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
    <script src="https://cdn.ckeditor.com/ckeditor5/40.1.0/classic/ckeditor.js"></script>

    <script>
        ClassicEditor
            .create(document.querySelector('#content'))
            .catch(error => {
                console.error(error);
            });
    </script>

    <script>
        function previewImage(input) {
            const preview = document.getElementById('imagePreview');
            const removeBtn = document.querySelector('.remove-image');
            const currentImage = document.querySelector('.current-image');

            if (input.files && input.files[0]) {
                const reader = new FileReader();

                reader.onload = function(e) {
                    preview.src = e.target.result;
                    preview.style.display = 'block';
                    removeBtn.style.display = 'block';
                    if (currentImage) {
                        currentImage.style.display = 'none';
                    }
                }

                reader.readAsDataURL(input.files[0]);
            }
        }

        function removeImage() {
            const preview = document.getElementById('imagePreview');
            const input = document.getElementById('image');
            const removeBtn = document.querySelector('.remove-image');
            const currentImage = document.querySelector('.current-image');

            preview.src = '#';
            preview.style.display = 'none';
            removeBtn.style.display = 'none';
            input.value = '';
            if (currentImage) {
                currentImage.style.display = 'block';
            }
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
