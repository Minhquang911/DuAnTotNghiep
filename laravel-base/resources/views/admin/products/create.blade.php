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

        /* Add Select2 custom styles */
        .select2-container--bootstrap-5 .select2-selection {
            min-height: 38px;
            border: 1px solid #ced4da;
            border-radius: 0.25rem;
        }

        .select2-container--bootstrap-5 .select2-selection--multiple .select2-selection__rendered {
            padding: 0.375rem 0.75rem;
        }

        .select2-container--bootstrap-5 .select2-selection--multiple .select2-selection__choice {
            background-color: #4e73df;
            border: none;
            color: white;
            padding: 2px 8px;
            margin: 2px;
            border-radius: 3px;
        }

        .select2-container--bootstrap-5 .select2-selection--multiple .select2-selection__choice__remove {
            color: white;
            margin-right: 5px;
        }

        .select2-container--bootstrap-5 .select2-selection--multiple .select2-selection__choice__remove:hover {
            color: #fff;
            background: rgba(255, 255, 255, 0.2);
        }

        .select2-container--bootstrap-5 .select2-dropdown {
            border-color: #ced4da;
            box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15);
        }

        .select2-container--bootstrap-5 .select2-results__option--highlighted[aria-selected] {
            background-color: #4e73df;
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
                                <i class="fas fa-plus me-2"></i>Thêm sản phẩm mới
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

                        <form action="{{ route('admin.products.store') }}" method="POST" enctype="multipart/form-data">
                            @csrf

                            <!-- Thông tin cơ bản -->
                            <div class="row mb-4">
                                <div class="col-12">
                                    <h4 class="mb-3">Thông tin cơ bản</h4>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group mb-3">
                                        <label for="title">Tên sách <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control @error('title') is-invalid @enderror"
                                            id="title" name="title" value="{{ old('title') }}"
                                            placeholder="Nhập tên sách">
                                        @error('title')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="form-group mb-3">
                                        <label for="author">Tác giả <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control @error('author') is-invalid @enderror"
                                            id="author" name="author" value="{{ old('author') }}"
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
                                                    {{ old('category_id') == $category->id ? 'selected' : '' }}>
                                                    {{ $category->name }}
                                                </option>
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
                                                    {{ old('publisher_id') == $publisher->id ? 'selected' : '' }}>
                                                    {{ $publisher->name }}
                                                </option>
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
                                            id="isbn" name="isbn" value="{{ old('isbn') }}"
                                            placeholder="Nhập mã ISBN">
                                        @error('isbn')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="form-group mb-3">
                                        <label for="published_at">Ngày xuất bản <span class="text-danger">*</span></label>
                                        <input type="date"
                                            class="form-control @error('published_at') is-invalid @enderror"
                                            id="published_at" name="published_at" value="{{ old('published_at') }}">
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
                                                    id="length_cm" name="length_cm" value="{{ old('length_cm') }}"
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
                                                    id="width_cm" name="width_cm" value="{{ old('width_cm') }}"
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
                                                    id="weight_g" name="weight_g" value="{{ old('weight_g') }}"
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
                                                    id="page_count" name="page_count" value="{{ old('page_count') }}"
                                                    placeholder="VD: 320">
                                                @error('page_count')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>

                                    <div class="form-group mb-3">
                                        <label for="cover_image">Ảnh bìa <span class="text-danger">*</span></label>
                                        <input type="file"
                                            class="form-control @error('cover_image') is-invalid @enderror"
                                            id="cover_image" name="cover_image" accept="image/*">
                                        @error('cover_image')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                        <div class="mt-2">
                                            <img id="preview" src="#" alt="Preview"
                                                class="preview-image d-none">
                                        </div>
                                    </div>
                                </div>

                                <div class="col-12">
                                    <div class="form-group mb-3">
                                        <label for="description">Mô tả <span class="text-danger">*</span></label>
                                        <textarea class="form-control @error('description') is-invalid @enderror" id="description" name="description"
                                            rows="4" placeholder="Nhập mô tả sách">{{ old('description') }}</textarea>
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
                                        <input class="form-check-input" type="checkbox" id="is_active" name="is_active"
                                            value="1" {{ old('is_active', true) ? 'checked' : '' }}>
                                        <label class="form-check-label" for="is_active">Đang hoạt động</label>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-check form-switch mb-3">
                                        <input class="form-check-input" type="checkbox" id="is_hot" name="is_hot"
                                            value="1" {{ old('is_hot') ? 'checked' : '' }}>
                                        <label class="form-check-label" for="is_hot">Sản phẩm hot</label>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-check form-switch mb-3">
                                        <input class="form-check-input" type="checkbox" id="is_new" name="is_new"
                                            value="1" {{ old('is_new') ? 'checked' : '' }}>
                                        <label class="form-check-label" for="is_new">Sản phẩm mới</label>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-check form-switch mb-3">
                                        <input class="form-check-input" type="checkbox" id="is_best_seller"
                                            name="is_best_seller" value="1"
                                            {{ old('is_best_seller') ? 'checked' : '' }}>
                                        <label class="form-check-label" for="is_best_seller">Bán chạy nhất</label>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-check form-switch mb-3">
                                        <input class="form-check-input" type="checkbox" id="is_recommended"
                                            name="is_recommended" value="1"
                                            {{ old('is_recommended') ? 'checked' : '' }}>
                                        <label class="form-check-label" for="is_recommended">Đề xuất</label>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-check form-switch mb-3">
                                        <input class="form-check-input" type="checkbox" id="is_featured"
                                            name="is_featured" value="1" {{ old('is_featured') ? 'checked' : '' }}>
                                        <label class="form-check-label" for="is_featured">Nổi bật</label>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-check form-switch mb-3">
                                        <input class="form-check-input" type="checkbox" id="is_promotion"
                                            name="is_promotion" value="1"
                                            {{ old('is_promotion') ? 'checked' : '' }}>
                                        <label class="form-check-label" for="is_promotion">Khuyến mãi</label>
                                    </div>
                                </div>
                            </div>

                            <!-- Phiên bản sản phẩm -->
                            <div class="row mb-4">
                                <div class="col-12">
                                    <div class="d-flex justify-content-between align-items-center mb-3">
                                        <h4 class="mb-0">Phiên bản sản phẩm</h4>
                                        <div>
                                            <button type="button" class="btn btn-success btn-sm me-2"
                                                data-bs-toggle="modal" data-bs-target="#quickGenerateModal">
                                                <i class="fas fa-magic me-1"></i> Tạo nhanh phiên bản
                                            </button>
                                            <button type="button" class="btn btn-primary btn-sm" id="addVariant">
                                                <i class="fas fa-plus me-1"></i> Thêm phiên bản
                                            </button>
                                        </div>
                                    </div>

                                    <!-- Quick Generate Modal -->
                                    <div class="modal fade" id="quickGenerateModal" tabindex="-1">
                                        <div class="modal-dialog modal-lg">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title">Tạo nhanh phiên bản sản phẩm</h5>
                                                    <button type="button" class="btn-close"
                                                        data-bs-dismiss="modal"></button>
                                                </div>
                                                <div class="modal-body">
                                                    <div class="row">
                                                        <div class="col-md-6">
                                                            <div class="form-group mb-3">
                                                                <label>Định dạng <span class="text-danger">*</span></label>
                                                                <select class="form-select" id="quick-formats" multiple>
                                                                    @foreach ($formats as $format)
                                                                        <option value="{{ $format->id }}"
                                                                            data-code="{{ $format->code }}">
                                                                            {{ $format->name }}</option>
                                                                    @endforeach
                                                                </select>
                                                                <div class="form-text">Chọn một hoặc nhiều định dạng</div>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <div class="form-group mb-3">
                                                                <label>Ngôn ngữ <span class="text-danger">*</span></label>
                                                                <select class="form-select" id="quick-languages" multiple>
                                                                    @foreach ($languages as $language)
                                                                        <option value="{{ $language->id }}"
                                                                            data-code="{{ $language->code }}">
                                                                            {{ $language->name }}</option>
                                                                    @endforeach
                                                                </select>
                                                                <div class="form-text">Chọn một hoặc nhiều ngôn ngữ</div>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="row">
                                                        <div class="col-md-4">
                                                            <div class="form-group mb-3">
                                                                <label>Giá cơ bản <span
                                                                        class="text-danger">*</span></label>
                                                                <input type="number" class="form-control"
                                                                    id="quick-base-price" min="0" step="1000"
                                                                    placeholder="Nhập giá cơ bản">
                                                            </div>
                                                        </div>
                                                        <div class="col-md-4">
                                                            <div class="form-group mb-3">
                                                                <label>Giá khuyến mãi</label>
                                                                <input type="number" class="form-control"
                                                                    id="quick-promotion-price" min="0"
                                                                    step="1000" placeholder="Nhập giá khuyến mãi">
                                                            </div>
                                                        </div>
                                                        <div class="col-md-4">
                                                            <div class="form-group mb-3">
                                                                <label>Số lượng mặc định <span
                                                                        class="text-danger">*</span></label>
                                                                <input type="number" class="form-control"
                                                                    id="quick-default-stock" min="0"
                                                                    value="0" placeholder="Nhập số lượng">
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="alert alert-info">
                                                        <h6 class="alert-heading">Số phiên bản sẽ được tạo: <span
                                                                id="variant-count">0</span></h6>
                                                        <p class="mb-0">Số lượng phiên bản = Số định dạng × Số ngôn ngữ
                                                        </p>
                                                    </div>

                                                    <div id="preview-variants" class="d-none">
                                                        <h6>Xem trước các phiên bản sẽ được tạo:</h6>
                                                        <div class="table-responsive">
                                                            <table class="table table-sm table-bordered">
                                                                <thead>
                                                                    <tr>
                                                                        <th>Định dạng</th>
                                                                        <th>Ngôn ngữ</th>
                                                                        <th>SKU</th>
                                                                        <th>Giá</th>
                                                                        <th>Giá KM</th>
                                                                        <th>Số lượng</th>
                                                                    </tr>
                                                                </thead>
                                                                <tbody id="preview-variants-body"></tbody>
                                                            </table>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary"
                                                        data-bs-dismiss="modal">Hủy</button>
                                                    <button type="button" class="btn btn-primary"
                                                        id="generate-variants">Tạo phiên bản</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div id="variants-container">
                                        <!-- Variants will be added here dynamically -->
                                    </div>
                                </div>
                            </div>

                            <div class="form-group mt-4">
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-save me-1"></i> Lưu sản phẩm
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
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.min.css"
        rel="stylesheet" />
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
    <script>
        $(document).ready(function() {
            // Initialize Select2 for multiple select with custom options
            $('#quick-formats, #quick-languages').select2({
                placeholder: "Chọn các tùy chọn",
                allowClear: true,
                width: '100%',
                theme: 'bootstrap-5',
                language: {
                    noResults: function() {
                        return "Không tìm thấy kết quả";
                    },
                    searching: function() {
                        return "Đang tìm kiếm...";
                    }
                },
                templateResult: formatOption,
                templateSelection: formatOption
            });

            // Custom formatting for options
            function formatOption(option) {
                if (!option.id) return option.text;
                return $('<span><i class="fas fa-check-circle me-2"></i>' + option.text + '</span>');
            }

            // Ensure Select2 dropdowns are properly styled
            $('.select2-container--bootstrap-5').css('width', '100%');

            // Preview variants when selections change
            function updatePreview() {
                const formats = $('#quick-formats').select2('data');
                const languages = $('#quick-languages').select2('data');
                const basePrice = $('#quick-base-price').val();
                const promotionPrice = $('#quick-promotion-price').val();
                const defaultStock = $('#quick-default-stock').val();
                const isbn = $('#isbn').val();

                if (!formats.length || !languages.length || !basePrice || !defaultStock || !isbn) {
                    $('#preview-variants').addClass('d-none');
                    $('#variant-count').text('0');
                    return;
                }

                const variantCount = formats.length * languages.length;
                $('#variant-count').text(variantCount);

                let previewHtml = '';
                formats.forEach(format => {
                    languages.forEach(language => {
                        const sku = `${isbn}-${format.id}-${language.element.dataset.code}`
                            .toUpperCase();
                        previewHtml += `
                        <tr>
                            <td>${format.text}</td>
                            <td>${language.text}</td>
                            <td>${sku}</td>
                            <td>${Number(basePrice).toLocaleString('vi-VN')}đ</td>
                            <td>${promotionPrice ? Number(promotionPrice).toLocaleString('vi-VN') + 'đ' : '-'}</td>
                            <td>${defaultStock}</td>
                        </tr>
                    `;
                    });
                });

                $('#preview-variants-body').html(previewHtml);
                $('#preview-variants').removeClass('d-none');
            }

            // Update preview on any change
            $('#quick-formats, #quick-languages, #quick-base-price, #quick-promotion-price, #quick-default-stock, #isbn')
                .on('change keyup', updatePreview);

            // Generate variants
            $('#generate-variants').click(function() {
                const formats = $('#quick-formats').select2('data');
                const languages = $('#quick-languages').select2('data');
                const basePrice = $('#quick-base-price').val();
                const promotionPrice = $('#quick-promotion-price').val();
                const defaultStock = $('#quick-default-stock').val();
                const isbn = $('#isbn').val();

                if (!formats.length || !languages.length || !basePrice || !defaultStock || !isbn) {
                    alert('Vui lòng điền đầy đủ thông tin cần thiết!');
                    return;
                }

                // Clear existing variants
                $('#variants-container').empty();
                variantCount = 0;

                // Generate new variants
                formats.forEach(format => {
                    languages.forEach(language => {
                        const sku = `${isbn}-${format.id}-${language.element.dataset.code}`
                            .toUpperCase();
                        const variantHtml = `
                        <div class="variant-card" id="variant-${variantCount}">
                            <div class="variant-header">
                                <h5 class="mb-0">Phiên bản #${variantCount + 1}</h5>
                                <button type="button" class="btn btn-danger btn-sm remove-variant" data-variant="${variantCount}">
                                    <i class="fas fa-times"></i>
                                </button>
                            </div>
                            <div class="row">
                                <div class="col-md-3">
                                    <div class="form-group mb-3">
                                        <label>Định dạng <span class="text-danger">*</span></label>
                                        <select class="form-select" name="variants[${variantCount}][format_id]" required>
                                            <option value="${format.id}" selected>${format.text}</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group mb-3">
                                        <label>Ngôn ngữ <span class="text-danger">*</span></label>
                                        <select class="form-select" name="variants[${variantCount}][language_id]" required>
                                            <option value="${language.id}" selected>${language.text}</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group mb-3">
                                        <label>Mã SKU <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control" name="variants[${variantCount}][sku]" 
                                            value="${sku}" required readonly>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group mb-3">
                                        <label>Giá <span class="text-danger">*</span></label>
                                        <input type="number" class="form-control" name="variants[${variantCount}][price]" 
                                            value="${basePrice}" min="0" step="1000" required>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group mb-3">
                                        <label>Giá khuyến mãi</label>
                                        <input type="number" class="form-control" name="variants[${variantCount}][promotion_price]" 
                                            value="${promotionPrice}" min="0" step="1000">
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group mb-3">
                                        <label>Số lượng <span class="text-danger">*</span></label>
                                        <input type="number" class="form-control" name="variants[${variantCount}][stock]" 
                                            value="${defaultStock}" min="0" required>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group mb-3">
                                        <label>Trạng thái</label>
                                        <div class="form-check form-switch mt-2">
                                            <input class="form-check-input" type="checkbox" 
                                                name="variants[${variantCount}][is_active]" value="1" checked>
                                            <label class="form-check-label">Đang hoạt động</label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    `;
                        $('#variants-container').append(variantHtml);
                        variantCount++;
                    });
                });

                // Close modal
                $('#quickGenerateModal').modal('hide');
            });

            // Preview image
            $('#cover_image').change(function() {
                const file = this.files[0];
                if (file) {
                    const reader = new FileReader();
                    reader.onload = function(e) {
                        $('#preview').attr('src', e.target.result).removeClass('d-none');
                    }
                    reader.readAsDataURL(file);
                }
            });

            // Add variant
            let variantCount = 0;
            const formats = @json($formats);
            const languages = @json($languages);

            function addVariant() {
                const variantHtml = `
                <div class="variant-card" id="variant-${variantCount}">
                    <div class="variant-header">
                        <h5 class="mb-0">Phiên bản #${variantCount + 1}</h5>
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
                                    ${formats.map(format => `
                                                                <option value="${format.id}">${format.name}</option>
                                                            `).join('')}
                                </select>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group mb-3">
                                <label>Ngôn ngữ <span class="text-danger">*</span></label>
                                <select class="form-select" name="variants[${variantCount}][language_id]" required>
                                    <option value="">Chọn ngôn ngữ</option>
                                    ${languages.map(language => `
                                                                <option value="${language.id}">${language.name}</option>
                                                            `).join('')}
                                </select>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group mb-3">
                                <label>Mã SKU <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" name="variants[${variantCount}][sku]" 
                                    placeholder="Nhập mã SKU" required>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group mb-3">
                                <label>Giá <span class="text-danger">*</span></label>
                                <input type="number" class="form-control" name="variants[${variantCount}][price]" 
                                    placeholder="Nhập giá" min="0" step="1000" required>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group mb-3">
                                <label>Giá khuyến mãi</label>
                                <input type="number" class="form-control" name="variants[${variantCount}][promotion_price]" 
                                    placeholder="Nhập giá khuyến mãi" min="0" step="1000">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group mb-3">
                                <label>Số lượng <span class="text-danger">*</span></label>
                                <input type="number" class="form-control" name="variants[${variantCount}][stock]" 
                                    placeholder="Nhập số lượng" min="0" required>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group mb-3">
                                <label>Trạng thái</label>
                                <div class="form-check form-switch mt-2">
                                    <input class="form-check-input" type="checkbox" 
                                        name="variants[${variantCount}][is_active]" value="1" checked>
                                    <label class="form-check-label">Đang hoạt động</label>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            `;
                $('#variants-container').append(variantHtml);
                variantCount++;
            }

            // Add first variant by default
            addVariant();

            // Add variant button click
            $('#addVariant').click(function() {
                addVariant();
            });

            // Remove variant
            $(document).on('click', '.remove-variant', function() {
                const variantId = $(this).data('variant');
                $(`#variant-${variantId}`).remove();
                if ($('.variant-card').length === 0) {
                    addVariant();
                }
            });

            // Auto-generate SKU khi chọn thủ công
            $(document).on('change',
                'select[name^="variants"][name$="[format_id]"], select[name^="variants"][name$="[language_id]"]',
                function() {
                    const variantIndex = $(this).attr('name').match(/\[(\d+)\]/)[1];
                    const formatId = $(`select[name="variants[${variantIndex}][format_id]"]`).val();
                    const languageId = $(`select[name="variants[${variantIndex}][language_id]"]`).val();
                    const isbn = $('#isbn').val();
                    const format = formats.find(f => f.id == formatId);
                    const language = languages.find(l => l.id == languageId);
                    if (format && language && isbn) {
                        const sku = `${isbn}-${formatId}-${language.code}`.toUpperCase();
                        $(`input[name="variants[${variantIndex}][sku]"]`).val(sku);
                    }
                });

            let albumImages = [];

            // Xử lý thêm ảnh
            $('#addImagesBtn').click(function() {
                const input = $('<input type="file" multiple accept="image/*" style="display: none">');
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

            // Cập nhật preview ảnh
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

            // Xử lý xóa ảnh album
            $(document).on('click', '.remove-image-btn', function() {
                const index = $(this).data('index');
                albumImages.splice(index, 1);
                updateAlbumPreview();
            });
        });
    </script>
@endpush