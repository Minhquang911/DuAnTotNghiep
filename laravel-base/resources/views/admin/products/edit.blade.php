@extends('layouts.admin.AdminLayout')

@section('content')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">
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

        .form-switch {
            padding-left: 2.5em;
        }

        .form-switch .form-check-input {
            width: 2.5em;
            margin-left: -2.5em;
            background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='-4 -4 8 8'%3e%3ccircle r='3' fill='rgba%280, 0, 0, 0.25%29'/%3e%3c/svg%3e");
            background-position: left center;
            border-radius: 2em;
            transition: background-position .15s ease-in-out;
        }

        .form-switch .form-check-input:checked {
            background-position: right center;
            background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='-4 -4 8 8'%3e%3ccircle r='3' fill='%23fff'/%3e%3c/svg%3e");
        }

        .variant-card {
            border: 1px solid #e3e6f0;
            border-radius: 0.35rem;
            padding: 1rem;
            margin-bottom: 1rem;
            background-color: #f8f9fc;
        }

        .variant-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 1rem;
            padding-bottom: 0.5rem;
            border-bottom: 1px solid #e3e6f0;
        }

        .preview-image {
            max-width: 200px;
            max-height: 200px;
            object-fit: contain;
        }
    </style>

    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                @if (session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <i class="fas fa-check-circle me-2"></i>
                        {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                @if (session('error'))
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <i class="fas fa-exclamation-circle me-2"></i>
                        {{ session('error') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                <div class="card">
                    <div class="card-header bg-gradient-primary">
                        <div class="d-flex justify-content-between align-items-center">
                            <h3 class="card-title text-white fw-bold">
                                <i class="fas fa-edit me-2"></i>Chỉnh sửa sản phẩm
                            </h3>
                            <a href="{{ route('admin.products.index') }}" class="btn btn-light btn-sm">
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

                        <form action="{{ route('admin.products.update', $product->id) }}" method="POST"
                              enctype="multipart/form-data">
                            @csrf
                            @method('PUT')
                            <div class="row mb-4">
                                <div class="col-12">
                                    <h4 class="mb-3">Thông tin cơ bản</h4>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group mb-3">
                                        <label for="title">Tên sách <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control @error('title') is-invalid @enderror"
                                               id="title" name="title" value="{{ old('title', $product->title) }}"
                                               placeholder="Nhập tên sách">
                                        @error('title')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="form-group mb-3">
                                        <label for="author">Tác giả <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control @error('author') is-invalid @enderror"
                                               id="author" name="author" value="{{ old('author', $product->author) }}"
                                               placeholder="Nhập tên tác giả">
                                        @error('author')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="form-group mb-3">
                                        <label for="category_id">Danh mục <span class="text-danger">*</span></label>
                                        <select class="form-select @error('category_id') is-invalid @enderror"
                                                id="category_id" name="category_id">
                                            <option value="">Chọn danh mục</option>
                                            @foreach ($categories as $category)
                                                <option value="{{ $category->id }}"
                                                    {{ old('category_id', $product->category_id) == $category->id ? 'selected' : '' }}>
                                                    {{ $category->name }}</option>
                                            @endforeach
                                        </select>
                                        @error('category_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="form-group mb-3">
                                        <label for="publisher_id">Nhà xuất bản <span class="text-danger">*</span></label>
                                        <select class="form-select @error('publisher_id') is-invalid @enderror"
                                                id="publisher_id" name="publisher_id">
                                            <option value="">Chọn nhà xuất bản</option>
                                            @foreach ($publishers as $publisher)
                                                <option value="{{ $publisher->id }}"
                                                    {{ old('publisher_id', $product->publisher_id) == $publisher->id ? 'selected' : '' }}>
                                                    {{ $publisher->name }}</option>
                                            @endforeach
                                        </select>
                                        @error('publisher_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group mb-3">
                                        <label for="isbn">ISBN <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control @error('isbn') is-invalid @enderror"
                                               id="isbn" name="isbn" value="{{ old('isbn', $product->isbn) }}"
                                               placeholder="Nhập mã ISBN">
                                        @error('isbn')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="form-group mb-3">
                                        <label for="published_at">Ngày xuất bản <span class="text-danger">*</span></label>
                                        <input type="date"
                                               class="form-control @error('published_at') is-invalid @enderror"
                                               id="published_at" name="published_at"
                                               value="{{ old('published_at', $product->published_at ? $product->published_at->format('Y-m-d') : '') }}">
                                        @error('published_at')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group mb-3">
                                                <label for="length_cm">Chiều dài (cm)</label>
                                                <input type="number" step="0.01" min="0"
                                                       class="form-control @error('length_cm') is-invalid @enderror"
                                                       id="length_cm" name="length_cm"
                                                       value="{{ old('length_cm', $product->length_cm) }}"
                                                       placeholder="VD: 20.5">
                                                @error('length_cm')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group mb-3">
                                                <label for="width_cm">Chiều rộng (cm)</label>
                                                <input type="number" step="0.01" min="0"
                                                       class="form-control @error('width_cm') is-invalid @enderror"
                                                       id="width_cm" name="width_cm"
                                                       value="{{ old('width_cm', $product->width_cm) }}"
                                                       placeholder="VD: 14.5">
                                                @error('width_cm')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group mb-3">
                                                <label for="weight_g">Trọng lượng (g)</label>
                                                <input type="number" min="0"
                                                       class="form-control @error('weight_g') is-invalid @enderror"
                                                       id="weight_g" name="weight_g"
                                                       value="{{ old('weight_g', $product->weight_g) }}"
                                                       placeholder="VD: 350">
                                                @error('weight_g')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group mb-3">
                                                <label for="page_count">Số trang</label>
                                                <input type="number" min="1"
                                                       class="form-control @error('page_count') is-invalid @enderror"
                                                       id="page_count" name="page_count"
                                                       value="{{ old('page_count', $product->page_count) }}"
                                                       placeholder="VD: 320">
                                                @error('page_count')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group mb-3">
                                        <label for="cover_image">Ảnh bìa</label>
                                        <input type="file"
                                               class="form-control @error('cover_image') is-invalid @enderror"
                                               id="cover_image" name="cover_image" accept="image/*">
                                        @error('cover_image')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                        <div class="mt-2">
                                            @if ($product->cover_image_url)
                                                <img id="preview" src="{{ $product->cover_image_url }}" alt="Preview"
                                                     class="preview-image">
                                            @else
                                                <img id="preview" src="#" alt="Preview"
                                                     class="preview-image d-none">
                                            @endif
                                        </div>
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div class="form-group mb-3">
                                        <label for="description">Mô tả <span class="text-danger">*</span></label>
                                        <textarea class="form-control @error('description') is-invalid @enderror" id="description" name="description"
                                                  rows="4" placeholder="Nhập mô tả sách">{{ old('description', $product->description) }}</textarea>
                                        @error('description')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                            <!-- Album hình ảnh -->
                            <div class="row mb-4">
                                <div class="col-12">
                                    <div class="d-flex justify-content-between align-items-center mb-3">
                                        <h4 class="mb-0">
                                            <i class="fas fa-images me-2"></i>Album hình ảnh
                                        </h4>
                                        <button type="button" class="btn btn-primary btn-sm" id="addImagesBtn">
                                            <i class="fas fa-plus me-1"></i> Thêm ảnh
                                        </button>
                                    </div>

                                    <div class="card">
                                        <div class="card-body">
                                            <!-- Existing Images -->
                                            @if ($product->albums->count() > 0)
                                                <div class="row g-3 mb-3">
                                                    @foreach ($product->albums as $image)
                                                        <div class="col-md-3 col-sm-4 col-6">
                                                            <div class="card h-100">
                                                                <img src="{{ asset('storage/' . $image->image) }}"
                                                                     class="card-img-top" alt="Album image"
                                                                     style="height: 200px; object-fit: cover;">
                                                                <div class="card-body p-2">
                                                                    <div
                                                                        class="d-flex justify-content-between align-items-center">
                                                                        <small class="text-muted">
                                                                            <i class="far fa-clock me-1"></i>
                                                                            {{ $image->created_at ? $image->created_at->format('d/m/Y') : '' }}
                                                                        </small>
                                                                        <button type="button"
                                                                                class="btn btn-sm btn-outline-danger delete-image-btn"
                                                                                data-image-id="{{ $image->id }}"
                                                                                data-bs-toggle="tooltip" title="Xóa ảnh">
                                                                            <i class="fas fa-trash"></i>
                                                                        </button>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    @endforeach
                                                </div>
                                            @endif

                                            <!-- New Images Preview -->
                                            <div class="row g-3" id="album-preview">
                                                <!-- Preview images will be added here -->
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Hidden input for storing image data -->
                                    <input type="hidden" name="album_images" id="album-images-data">
                                </div>
                            </div>
                            <!-- Trạng thái sản phẩm -->
                            <div class="row mb-4">
                                <div class="col-12">
                                    <h4 class="mb-3">Trạng thái sản phẩm</h4>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-check form-switch mb-3">
                                        <input type="hidden" name="is_active" value="0">
                                        <input class="form-check-input" type="checkbox" id="is_active" name="is_active"
                                               value="1" {{ old('is_active', $product->is_active) ? 'checked' : '' }}>
                                        <label class="form-check-label" for="is_active">Đang hoạt động</label>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-check form-switch mb-3">
                                        <input type="hidden" name="is_hot" value="0">
                                        <input class="form-check-input" type="checkbox" id="is_hot" name="is_hot"
                                               value="1" {{ old('is_hot', $product->is_hot) ? 'checked' : '' }}>
                                        <label class="form-check-label" for="is_hot">Sản phẩm hot</label>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-check form-switch mb-3">
                                        <input type="hidden" name="is_new" value="0">
                                        <input class="form-check-input" type="checkbox" id="is_new" name="is_new"
                                               value="1" {{ old('is_new', $product->is_new) ? 'checked' : '' }}>
                                        <label class="form-check-label" for="is_new">Sản phẩm mới</label>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-check form-switch mb-3">
                                        <input type="hidden" name="is_best_seller" value="0">
                                        <input class="form-check-input" type="checkbox" id="is_best_seller"
                                               name="is_best_seller" value="1"
                                            {{ old('is_best_seller', $product->is_best_seller) ? 'checked' : '' }}>
                                        <label class="form-check-label" for="is_best_seller">Bán chạy nhất</label>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-check form-switch mb-3">
                                        <input type="hidden" name="is_recommended" value="0">
                                        <input class="form-check-input" type="checkbox" id="is_recommended"
                                               name="is_recommended" value="1"
                                            {{ old('is_recommended', $product->is_recommended) ? 'checked' : '' }}>
                                        <label class="form-check-label" for="is_recommended">Đề xuất</label>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-check form-switch mb-3">
                                        <input type="hidden" name="is_featured" value="0">
                                        <input class="form-check-input" type="checkbox" id="is_featured"
                                               name="is_featured" value="1"
                                            {{ old('is_featured', $product->is_featured) ? 'checked' : '' }}>
                                        <label class="form-check-label" for="is_featured">Nổi bật</label>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-check form-switch mb-3">
                                        <input type="hidden" name="is_promotion" value="0">
                                        <input class="form-check-input" type="checkbox" id="is_promotion"
                                               name="is_promotion" value="1"
                                            {{ old('is_promotion', $product->is_promotion) ? 'checked' : '' }}>
                                        <label class="form-check-label" for="is_promotion">Khuyến mãi</label>
                                    </div>
                                </div>
                            </div>
                            <!-- Biến thể sản phẩm -->
                            <div class="row mb-4">
                                <div class="col-12">
                                    <div class="d-flex justify-content-between align-items-center mb-3">
                                        <h4 class="mb-0">Biến thể sản phẩm</h4>
                                        <button type="button" class="btn btn-primary btn-sm" id="addVariant">
                                            <i class="fas fa-plus me-1"></i> Thêm biến thể
                                        </button>
                                    </div>
                                    <div id="variants-container">
                                        @foreach ($product->variants as $i => $variant)
                                            <div class="variant-card" id="variant-{{ $i }}">
                                                <div class="variant-header">
                                                    <h5 class="mb-0">Biến thể #{{ $i + 1 }}</h5>
                                                    <button type="button" class="btn btn-danger btn-sm remove-variant"
                                                            data-variant="{{ $i }}">
                                                        <i class="fas fa-times"></i>
                                                    </button>
                                                </div>
                                                <div class="row">
                                                    <input type="hidden" name="variants[{{ $i }}][id]"
                                                           value="{{ $variant->id }}">
                                                    <div class="col-md-3">
                                                        <div class="form-group mb-3">
                                                            <label>Định dạng <span class="text-danger">*</span></label>
                                                            <select class="form-select"
                                                                    name="variants[{{ $i }}][format_id]" required>
                                                                <option value="">Chọn định dạng</option>
                                                                @foreach ($formats as $format)
                                                                    <option value="{{ $format->id }}"
                                                                        {{ $variant->format_id == $format->id ? 'selected' : '' }}>
                                                                        {{ $format->name }}</option>
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-3">
                                                        <div class="form-group mb-3">
                                                            <label>Ngôn ngữ <span class="text-danger">*</span></label>
                                                            <select class="form-select"
                                                                    name="variants[{{ $i }}][language_id]"
                                                                    required>
                                                                <option value="">Chọn ngôn ngữ</option>
                                                                @foreach ($languages as $language)
                                                                    <option value="{{ $language->id }}"
                                                                        {{ $variant->language_id == $language->id ? 'selected' : '' }}>
                                                                        {{ $language->name }}</option>
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-3">
                                                        <div class="form-group mb-3">
                                                            <label>Mã SKU <span class="text-danger">*</span></label>
                                                            <input type="text" class="form-control"
                                                                   name="variants[{{ $i }}][sku]"
                                                                   value="{{ $variant->sku }}" required>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-3">
                                                        <div class="form-group mb-3">
                                                            <label>Giá <span class="text-danger">*</span></label>
                                                            <input type="number" class="form-control"
                                                                   name="variants[{{ $i }}][price]"
                                                                   value="{{ $variant->price }}" min="0"
                                                                   step="1000" required>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-3">
                                                        <div class="form-group mb-3">
                                                            <label>Giá khuyến mãi</label>
                                                            <input type="number" class="form-control"
                                                                   name="variants[{{ $i }}][promotion_price]"
                                                                   value="{{ $variant->promotion_price }}" min="0"
                                                                   step="1000">
                                                        </div>
                                                    </div>
                                                    <div class="col-md-3">
                                                        <div class="form-group mb-3">
                                                            <label>Số lượng <span class="text-danger">*</span></label>
                                                            <input type="number" class="form-control"
                                                                   name="variants[{{ $i }}][stock]"
                                                                   value="{{ $variant->stock }}" min="0" required>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-3">
                                                        <div class="form-group mb-3">
                                                            <label>Trạng thái</label>
                                                            <div class="form-check form-switch">
                                                                <input type="hidden" name="variants[{{ $i }}][is_active]" value="0">
                                                                <input class="form-check-input" type="checkbox" id="variant_is_active_{{ $i }}"
                                                                       name="variants[{{ $i }}][is_active]" value="1"
                                                                    {{ $variant->is_active ? 'checked' : '' }}>
                                                                <label class="form-check-label" for="variant_is_active_{{ $i }}">Đang bán</label>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                            <div class="form-group mt-4">
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-save me-1"></i> Lưu thay đổi
                                </button>
                                <a href="{{ route('admin.products.index') }}" class="btn btn-secondary">
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
    <script src="https://cdnjs.cloudflare.com/ajax/libs/speakingurl/14.0.1/speakingurl.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const nameInput = document.getElementById('name');
            const slugInput = document.getElementById('slug');
            const codeInput = document.getElementById('code');
            nameInput.addEventListener('input', function() {
                const slug = getSlug(this.value);
                slugInput.value = slug;
            });
            codeInput.addEventListener('input', function() {
                this.value = this.value.toLowerCase();
            });

            function getSlug(text) {
                return speakingurl(text, {
                    lang: 'vi',
                    separator: '-',
                    symbols: true
                });
            }
        });
    </script>
    <script>
        $(document).ready(function() {
            let variantCount = {{ count($product->variants) }};
            const formats = @json($formats);
            const languages = @json($languages);

            function addVariant() {
                const variantHtml = `
                    <div class="variant-card" id="variant-${variantCount}">
                        <div class="variant-header">
                            <h5 class="mb-0">Biến thể #${variantCount + 1}</h5>
                            <button type="button" class="btn btn-danger btn-sm remove-variant" data-variant="${variantCount}">
                                <i class="fas fa-times"></i>
                            </button>
                        </div>
                        <div class="row">
                            <div class="col-md-3">
                                <div class="form-group mb-3">
                                    <label>Định dạng <span class="text-danger">*</span></label>
                                    <select class="form-select" name="variants[${variantCount}][format_id]" required>
                                        <option value="">Chọn định dạng</option>
                                        ${formats.map(format => `<option value="${format.id}">${format.name}</option>`).join('')}
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group mb-3">
                                    <label>Ngôn ngữ <span class="text-danger">*</span></label>
                                    <select class="form-select" name="variants[${variantCount}][language_id]" required>
                                        <option value="">Chọn ngôn ngữ</option>
                                        ${languages.map(language => `<option value="${language.id}">${language.name}</option>`).join('')}
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group mb-3">
                                    <label>Mã SKU <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" name="variants[${variantCount}][sku]" placeholder="Nhập mã SKU" required>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group mb-3">
                                    <label>Giá <span class="text-danger">*</span></label>
                                    <input type="number" class="form-control" name="variants[${variantCount}][price]" placeholder="Nhập giá" min="0" step="1000" required>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group mb-3">
                                    <label>Giá khuyến mãi</label>
                                    <input type="number" class="form-control" name="variants[${variantCount}][promotion_price]" placeholder="Nhập giá khuyến mãi" min="0" step="1000">
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group mb-3">
                                    <label>Số lượng <span class="text-danger">*</span></label>
                                    <input type="number" class="form-control" name="variants[${variantCount}][stock]" placeholder="Nhập số lượng" min="0" required>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group mb-3">
                                    <label>Trạng thái</label>
                                    <div class="form-check form-switch">
                                        <input type="hidden" name="variants[${variantCount}][is_active]" value="0">
                                        <input class="form-check-input" type="checkbox" name="variants[${variantCount}][is_active]" value="1" checked>
                                        <label class="form-check-label">Đang bán</label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                `;
                $('#variants-container').append(variantHtml);
                variantCount++;
            }
            $('#addVariant').click(function() {
                addVariant();
            });
            $(document).on('click', '.remove-variant', function() {
                const variantId = $(this).data('variant');
                $(`#variant-${variantId}`).remove();
                if ($('.variant-card').length === 0) {
                    addVariant();
                }
            });

            // Album Images Management
            let albumImages = [];

            // Thêm ảnh
            $('#addImagesBtn').click(function() {
                $('.hidden-album-input').remove(); // xóa input cũ nếu có
                const input = $(
                    '<input type="file" multiple accept="image/*" style="display: none" class="hidden-album-input">'
                );
                $('body').append(input);
                input.click();

                input.on('change', function(e) {
                    const files = e.target.files;
                    for (let i = 0; i < files.length; i++) {
                        const file = files[i];
                        if (file.type.startsWith('image/')) {
                            const reader = new FileReader();
                            reader.onload = function(e) {
                                const imageData = {
                                    id: 'temp_' + Date.now() + '_' + i,
                                    file: file,
                                    preview: e.target.result
                                };
                                albumImages.push(imageData);
                                updateAlbumPreview();
                            }
                            reader.readAsDataURL(file);
                        }
                    }
                });
            });

            // Update album preview
            function updateAlbumPreview() {
                const preview = $('#album-preview');
                preview.empty();

                albumImages.forEach((image, index) => {
                    const html = `
                        <div class="col-md-3 col-sm-4 col-6">
                            <div class="card h-100">
                                <img src="${image.preview}" class="card-img-top" alt="Preview"
                                    style="height: 200px; object-fit: cover;">
                                <div class="card-body p-2">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <small class="text-muted">
                                            <i class="far fa-image me-1"></i>
                                            ${image.file.name}
                                        </small>
                                        <button type="button" class="btn btn-sm btn-outline-danger remove-image-btn"
                                            data-index="${index}">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    `;
                    preview.append(html);
                });

                // Update hidden input with image data
                $('#album-images-data').val(JSON.stringify(albumImages.map(img => ({
                    name: img.file.name,
                    type: img.file.type,
                    size: img.file.size
                }))));
            }

            // Remove image from album
            $(document).on('click', '.remove-image-btn', function() {
                const index = $(this).data('index');
                albumImages.splice(index, 1);
                updateAlbumPreview();
            });

            // Delete existing image
            $(document).on('click', '.delete-image-btn', function() {
                const imageId = $(this).data('image-id');
                const $btn = $(this);

                if (confirm('Bạn có chắc chắn muốn xóa ảnh này?')) {
                    $btn.prop('disabled', true);

                    $.ajax({
                        url: `/admin/products/images/${imageId}`,
                        method: 'DELETE',
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        },
                        success: function(response) {
                            if (response.success) {
                                toastr.success('Xóa ảnh thành công');
                                $btn.closest('.col-md-3').fadeOut(400, function() {
                                    $(this).remove();
                                    if ($('.delete-image-btn').length === 0) {
                                        $('#album-preview').closest('.card-body')
                                            .prepend(
                                                '<div class="text-center py-4 text-muted">Chưa có hình ảnh nào.</div>'
                                            );
                                    }
                                });
                            } else {
                                toastr.error(response.message || 'Có lỗi xảy ra khi xóa ảnh');
                            }
                        },
                        error: function(xhr) {
                            if (xhr.status === 419) {
                                toastr.error(
                                    'Phiên làm việc đã hết hạn. Vui lòng tải lại trang và thử lại.'
                                );
                            } else {
                                toastr.error('Có lỗi xảy ra khi xóa ảnh');
                            }
                        },
                        complete: function() {
                            $btn.prop('disabled', false);
                        }
                    });
                }
            });
        });
    </script>
@endpush
