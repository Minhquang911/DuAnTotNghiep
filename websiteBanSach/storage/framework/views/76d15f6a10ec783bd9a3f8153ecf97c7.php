

<?php $__env->startSection('content'); ?>
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Quản lý sách</h3>
                    <div class="card-tools">
                        <a href="<?php echo e(route('admin.books.create')); ?>" class="btn btn-primary">
                            <i class="fas fa-plus"></i> Thêm sách mới
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <?php if(session('success')): ?>
                        <div class="alert alert-success">
                            <?php echo e(session('success')); ?>

                        </div>
                    <?php endif; ?>

                    <table class="table table-bordered table-hover">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Ảnh bìa</th>
                                <th>Tên sách</th>
                                <th>Tác giả</th>
                                <th>Danh mục</th>
                                <th>Giá</th>
                                <th>Tồn kho</th>
                                <th>Trạng thái</th>
                                <th>Thao tác</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $__currentLoopData = $books; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $book): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <tr>
                                <td><?php echo e($book->id); ?></td>
                                <td>
                                    <?php if($book->cover_image): ?>
                                        <img src="<?php echo e(asset($book->cover_image)); ?>" alt="<?php echo e($book->title); ?>" style="max-width: 50px; height: auto;">
                                    <?php else: ?>
                                        <span class="text-muted">Không có ảnh</span>
                                    <?php endif; ?>
                                </td>
                                <td><?php echo e($book->title); ?></td>
                                <td><?php echo e($book->author); ?></td>
                                <td><?php echo e($book->category->name); ?></td>
                                <td><?php echo e(number_format($book->price)); ?> VNĐ</td>
                                <td><?php echo e($book->stock); ?></td>
                                <td>
                                    <?php if($book->status == 'còn hàng'): ?>
                                        <span class="badge bg-success">Còn hàng</span>
                                    <?php elseif($book->status == 'hết hàng'): ?>
                                        <span class="badge bg-danger">Hết hàng</span>
                                    <?php else: ?>
                                        <span class="badge bg-warning">Đang nhập</span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <a href="<?php echo e(route('admin.books.show', $book)); ?>" class="btn btn-sm btn-info">
                                        <i class="fas fa-eye"></i> Xem
                                    </a>
                                    <a href="<?php echo e(route('admin.books.edit', $book)); ?>" class="btn btn-sm btn-warning">
                                        <i class="fas fa-edit"></i> Sửa
                                    </a>
                                    <form action="<?php echo e(route('admin.books.destroy', $book)); ?>" method="POST" class="d-inline">
                                        <?php echo csrf_field(); ?>
                                        <?php echo method_field('DELETE'); ?>
                                        <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Bạn có chắc muốn xóa sách này?')">
                                            <i class="fas fa-trash"></i> Xóa
                                        </button>
                                    </form>
                                </td>
                            </tr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?> 
<?php echo $__env->make('admin.layout.AdminLayout', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\xamp\htdocs\DuAnTotNghiep\websiteBanSach\resources\views/admin/books/index.blade.php ENDPATH**/ ?>