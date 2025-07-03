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
                                <i class="fas fa-edit me-2"></i>Chỉnh Sửa Khuyến Mãi
                            </h3>
                            <a href="{{ route('admin.promotions.index') }}" class="btn btn-light btn-sm">
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

                        <form action="{{ route('admin.promotions.update', $promotion->id) }}" method="POST"
                            enctype="multipart/form-data">
                            @csrf
                            @method('PUT')
                            <div class="row">
                                <div class="col-md-8">
                                    <div class="form-group mb-3">
                                        <label for="code">Mã khuyến mãi <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control @error('code') is-invalid @enderror"
                                            id="code" name="code" value="{{ old('code', $promotion->code) }}"
                                            placeholder="Nhập mã khuyến mãi (VD: WELCOME10)" maxlength="50">
                                        @error('code')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                        <small class="text-muted">Mã khuyến mãi phải là duy nhất và không được trùng lặp</small>
                                    </div>

                                    <div class="form-group mb-3">
                                        <label for="title">Tên khuyến mãi <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control @error('title') is-invalid @enderror"
                                            id="title" name="title" value="{{ old('title', $promotion->title) }}"
                                            placeholder="Nhập tên khuyến mãi" maxlength="255">
                                        @error('title')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="form-group mb-3">
                                        <label for="description">Mô tả khuyến mãi</label>
                                        <textarea class="form-control @error('description') is-invalid @enderror" id="description" name="description"
                                            rows="3" placeholder="Nhập mô tả chi tiết về khuyến mãi">{{ old('description', $promotion->description) }}</textarea>
                                        @error('description')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group mb-3">
                                                <label for="discount_type">Loại giảm giá <span
                                                        class="text-danger">*</span></label>
                                                <select class="form-control @error('discount_type') is-invalid @enderror"
                                                    id="discount_type" name="discount_type">
                                                    <option value="percent"
                                                        {{ old('discount_type', $promotion->discount_type) == 'percent' ? 'selected' : '' }}>
                                                        Phần trăm (%)</option>
                                                    <option value="fixed"
                                                        {{ old('discount_type', $promotion->discount_type) == 'fixed' ? 'selected' : '' }}>
                                                        Số tiền cố định</option>
                                                </select>
                                                @error('discount_type')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group mb-3">
                                                <label for="discount_value">Giá trị giảm giá <span
                                                        class="text-danger">*</span></label>
                                                <div class="input-group">
                                                    <input type="number"
                                                        class="form-control @error('discount_value') is-invalid @enderror"
                                                        id="discount_value" name="discount_value"
                                                        value="{{ old('discount_value', $promotion->discount_value) }}"
                                                        min="0" step="0.01">
                                                    <span class="input-group-text" id="discount_type_suffix">
                                                        {{ old('discount_type', $promotion->discount_type) == 'percent' ? '%' : 'VNĐ' }}
                                                    </span>
                                                </div>
                                                @error('discount_value')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group mb-3" id="max-discount-group">
                                                <label for="max_discount_value">Giảm giá tối đa</label>
                                                <div class="input-group">
                                                    <input type="number"
                                                        class="form-control @error('max_discount_value') is-invalid @enderror"
                                                        id="max_discount_value" name="max_discount_value"
                                                        value="{{ old('max_discount_value', $promotion->max_discount_value) }}"
                                                        min="0" step="1000">
                                                    <span class="input-group-text">VNĐ</span>
                                                </div>
                                                @error('max_discount_value')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group mb-3" id="min-order-group">
                                                <label for="min_order_value">Giá trị đơn hàng tối thiểu</label>
                                                <div class="input-group">
                                                    <input type="number"
                                                        class="form-control @error('min_order_value') is-invalid @enderror"
                                                        id="min_order_value" name="min_order_value"
                                                        value="{{ old('min_order_value', $promotion->min_order_value) }}"
                                                        min="0" step="1000">
                                                    <span class="input-group-text">VNĐ</span>
                                                </div>
                                                @error('min_order_value')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group mb-3">
                                                <label for="start_date">Ngày bắt đầu <span
                                                        class="text-danger">*</span></label>
                                                <input type="datetime-local"
                                                    class="form-control @error('start_date') is-invalid @enderror"
                                                    id="start_date" name="start_date"
                                                    value="{{ old('start_date', date('Y-m-d\TH:i', strtotime($promotion->start_date))) }}">
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
                                                    id="end_date" name="end_date"
                                                    value="{{ old('end_date', date('Y-m-d\TH:i', strtotime($promotion->end_date))) }}">
                                                @error('end_date')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <div class="row form-group mb-3">
                                        <div class="col-6">
                                            <label for="usage_limit">Số lượng</label>
                                            <input type="number" step="1"
                                                class="form-control @error('usage_limit') is-invalid @enderror"
                                                id="usage_limit" name="usage_limit"
                                                value="{{ old('usage_limit', $promotion->usage_limit) }}"
                                                placeholder="Nhập số lượng mã khuyến mãi">
                                            @error('usage_limit')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <div class="col-6">
                                            <label for="usage_limit">Số lượng đã sử dụng</label>
                                            <input type="number"
                                                class="form-control"
                                                id="used_count" name="used_count"
                                                value="{{ $promotion->used_count }}" disabled>
                                        </div>
                                    </div>

                                    <div class="form-group mb-3">
                                        <label for="image">Hình ảnh khuyến mãi</label>
                                        <div class="current-image">
                                            <div class="current-image-label">Hình ảnh đang sử dụng:</div>
                                            <img src="{{ Storage::url($promotion->image) }}" alt="Current Promotion"
                                                class="img-fluid rounded">
                                        </div>

                                        <div class="image-preview-container">
                                            <img id="imagePreview" class="image-preview mb-2" src="#"
                                                alt="Preview" style="display: none;">
                                            <button type="button" class="remove-image btn btn-sm btn-danger"
                                                onclick="removeImage()" style="display: none;">
                                                <i class="fas fa-times"></i>
                                            </button>
                                        </div>
                                        <div class="custom-file">
                                            <input type="file"
                                                class="form-control @error('image') is-invalid @enderror" id="image"
                                                name="image" accept="image/jpeg,image/png,image/gif">
                                            <div class="form-text">Định dạng: JPG, PNG, GIF. Kích thước tối đa: 2MB</div>
                                        </div>
                                        @error('image')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="form-group mb-3">
                                        <label for="is_active">Trạng thái</label>
                                        <div class="form-check form-switch">
                                            <input class="form-check-input" type="checkbox" id="is_active"
                                                name="is_active" value="1"
                                                {{ old('is_active', $promotion->is_active) ? 'checked' : '' }}>
                                            <label class="form-check-label" for="is_active">Kích hoạt khuyến mãi</label>
                                        </div>
                                        @error('is_active')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <div class="form-group mt-4">
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-save me-1"></i> Cập nhật khuyến mãi
                                </button>
                                <a href="{{ route('admin.promotions.index') }}" class="btn btn-secondary">
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

        // Update discount type suffix when type changes
        document.getElementById('discount_type').addEventListener('change', function() {
            const suffix = this.value === 'percent' ? '%' : 'VNĐ';
            document.getElementById('discount_type_suffix').textContent = suffix;
        });

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

        function toggleDiscountFields() {
            const discountType = document.getElementById('discount_type').value;
            const maxDiscountGroup = document.getElementById('max-discount-group');
            const minOrderGroup = document.getElementById('min-order-group');
            if (discountType === 'fixed') {
                maxDiscountGroup.style.display = 'none';
                minOrderGroup.style.display = 'none';
            } else {
                maxDiscountGroup.style.display = '';
                minOrderGroup.style.display = '';
            }
        }

        document.addEventListener('DOMContentLoaded', function() {
            toggleDiscountFields();
            document.getElementById('discount_type').addEventListener('change', toggleDiscountFields);
        });
    </script>
@endpush