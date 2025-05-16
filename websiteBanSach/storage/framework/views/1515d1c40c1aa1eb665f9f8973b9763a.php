

<?php $__env->startSection('content'); ?>
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Chi tiết sách</h3>
                    <div class="card-tools">
                        <a href="<?php echo e(route('admin.books.index')); ?>" class="btn btn-default">
                            <i class="fas fa-arrow-left"></i> Quay lại
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-4">
                            <?php if($book->cover_image): ?>
                                <img src="<?php echo e(asset($book->cover_image)); ?>" 
                                     alt="<?php echo e($book->title); ?>" 
                                     class="img-fluid rounded"
                                     style="max-width: 100%; height: auto;">
                            <?php else: ?>
                                <div class="text-center p-3 bg-light rounded">
                                    <i class="fas fa-book fa-3x text-muted"></i>
                                    <p class="mt-2 text-muted">Không có ảnh bìa</p>
                                </div>
                            <?php endif; ?>
                        </div>
                        <div class="col-md-8">
                            <table class="table table-bordered">
                                <tr>
                                    <th style="width: 200px;">ID</th>
                                    <td><?php echo e($book->id); ?></td>
                                </tr>
                                <tr>
                                    <th>Tên sách</th>
                                    <td><?php echo e($book->title); ?></td>
                                </tr>
                                <tr>
                                    <th>Tác giả</th>
                                    <td><?php echo e($book->author); ?></td>
                                </tr>
                                <tr>
                                    <th>Nhà xuất bản</th>
                                    <td><?php echo e($book->publisher); ?></td>
                                </tr>
                                <tr>
                                    <th>Danh mục</th>
                                    <td><?php echo e($book->category->name); ?></td>
                                </tr>
                                <tr>
                                    <th>Giá bán</th>
                                    <td><?php echo e(number_format($book->price)); ?> VNĐ</td>
                                </tr>
                                <tr>
                                    <th>Số lượng tồn kho</th>
                                    <td><?php echo e($book->stock); ?></td>
                                </tr>
                                <tr>
                                    <th>Trạng thái</th>
                                    <td>
                                        <?php if($book->status == 'còn hàng'): ?>
                                            <span class="badge bg-success">Còn hàng</span>
                                        <?php elseif($book->status == 'hết hàng'): ?>
                                            <span class="badge bg-danger">Hết hàng</span>
                                        <?php else: ?>
                                            <span class="badge bg-warning">Đang nhập</span>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                                <tr>
                                    <th>Ngày tạo</th>
                                    <td><?php echo e($book->created_at->format('d/m/Y H:i:s')); ?></td>
                                </tr>
                                <tr>
                                    <th>Cập nhật lần cuối</th>
                                    <td><?php echo e($book->updated_at->format('d/m/Y H:i:s')); ?></td>
                                </tr>
                            </table>

                            <div class="mt-4">
                                <h5>Mô tả sách:</h5>
                                <div class="p-3 bg-light rounded">
                                    <?php echo e($book->description); ?>

                                </div>
                            </div>

                            <div class="mt-4">
                                <a href="<?php echo e(route('admin.books.edit', $book)); ?>" class="btn btn-primary">
                                    <i class="fas fa-edit"></i> Chỉnh sửa
                                </a>
                                <form action="<?php echo e(route('admin.books.destroy', $book)); ?>" method="POST" class="d-inline">
                                    <?php echo csrf_field(); ?>
                                    <?php echo method_field('DELETE'); ?>
                                    <button type="submit" class="btn btn-danger" onclick="return confirm('Bạn có chắc muốn xóa sách này?')">
                                        <i class="fas fa-trash"></i> Xóa
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?> 
<?php echo $__env->make('admin.layout.AdminLayout', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\xamp\htdocs\DuAnTotNghiep\websiteBanSach\resources\views/admin/books/show.blade.php ENDPATH**/ ?>