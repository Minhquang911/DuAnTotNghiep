

<?php $__env->startSection('content'); ?>
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Chỉnh sửa sách</h3>
                    <div class="card-tools">
                        <a href="<?php echo e(route('admin.books.index')); ?>" class="btn btn-default">
                            <i class="fas fa-arrow-left"></i> Quay lại
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <?php if($errors->any()): ?>
                        <div class="alert alert-danger">
                            <ul class="mb-0">
                                <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <li><?php echo e($error); ?></li>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </ul>
                        </div>
                    <?php endif; ?>

                    <form action="<?php echo e(route('admin.books.update', $book)); ?>" method="POST" enctype="multipart/form-data">
                        <?php echo csrf_field(); ?>
                        <?php echo method_field('PUT'); ?>
                        
                        <div class="form-group">
                            <label for="title">Tên sách <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="title" name="title" value="<?php echo e(old('title', $book->title)); ?>" required>
                        </div>

                        <div class="form-group">
                            <label for="description">Mô tả <span class="text-danger">*</span></label>
                            <textarea class="form-control" id="description" name="description" rows="4" required><?php echo e(old('description', $book->description)); ?></textarea>
                        </div>

                        <div class="form-group">
                            <label for="author">Tác giả <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="author" name="author" value="<?php echo e(old('author', $book->author)); ?>" required>
                        </div>

                        <div class="form-group">
                            <label for="publisher">Nhà xuất bản <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="publisher" name="publisher" value="<?php echo e(old('publisher', $book->publisher)); ?>" required>
                        </div>

                        <div class="form-group">
                            <label for="category_id">Danh mục <span class="text-danger">*</span></label>
                            <select class="form-control" id="category_id" name="category_id" required>
                                <option value="">Chọn danh mục</option>
                                <?php $__currentLoopData = $categories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $category): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <option value="<?php echo e($category->id); ?>" <?php echo e(old('category_id', $book->category_id) == $category->id ? 'selected' : ''); ?>>
                                        <?php echo e($category->name); ?>

                                    </option>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="price">Giá bán <span class="text-danger">*</span></label>
                            <input type="number" class="form-control" id="price" name="price" value="<?php echo e(old('price', $book->price)); ?>" min="0" required>
                        </div>

                        <div class="form-group">
                            <label for="stock">Số lượng tồn kho <span class="text-danger">*</span></label>
                            <input type="number" class="form-control" id="stock" name="stock" value="<?php echo e(old('stock', $book->stock)); ?>" min="0" required>
                        </div>

                        <div class="form-group">
                            <label for="status">Trạng thái <span class="text-danger">*</span></label>
                            <select class="form-control" id="status" name="status" required>
                                <option value="còn hàng" <?php echo e(old('status', $book->status) == 'còn hàng' ? 'selected' : ''); ?>>Còn hàng</option>
                                <option value="hết hàng" <?php echo e(old('status', $book->status) == 'hết hàng' ? 'selected' : ''); ?>>Hết hàng</option>
                                <option value="đang nhập" <?php echo e(old('status', $book->status) == 'đang nhập' ? 'selected' : ''); ?>>Đang nhập</option>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label for="cover_image" class="form-label">Ảnh bìa</label>
                        <div class="form-group">
                            <label for="cover_image">Ảnh bìa</label>
                            <?php if($book->cover_image): ?>
                                <div class="mb-2">
                                    <img src="<?php echo e(asset($book->cover_image)); ?>" alt="<?php echo e($book->title); ?>" style="max-width: 200px;">
                                </div>
                            <?php endif; ?>
                            <input type="file" class="form-control-file" id="cover_image" name="cover_image" accept="image/*">
                            <small class="form-text text-muted">Chấp nhận các file ảnh: jpg, jpeg, png, gif. Kích thước tối đa: 2MB</small>
                        </div>

                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save"></i> Cập nhật
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('styles'); ?>
<style>
    .text-danger { color: #dc3545; }
</style>
<?php $__env->stopPush(); ?> 
<?php echo $__env->make('admin.layout.AdminLayout', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\xamp\htdocs\DuAnTotNghiep\websiteBanSach\resources\views/admin/books/edit.blade.php ENDPATH**/ ?>