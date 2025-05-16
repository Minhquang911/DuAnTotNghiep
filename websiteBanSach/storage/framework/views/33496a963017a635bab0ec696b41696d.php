<?php $__env->startSection('title', 'Quản lý mã khuyến mãi'); ?>

<?php $__env->startSection('content'); ?>
<h3>Danh sách mã khuyến mãi</h3>

<a href="<?php echo e(route('promotions.create')); ?>" class="btn btn-success mb-3">+ Thêm mới</a>

<?php if(session('success')): ?> <div class="alert alert-success"><?php echo e(session('success')); ?></div> <?php endif; ?>
<?php if(session('error')): ?> <div class="alert alert-danger"><?php echo e(session('error')); ?></div> <?php endif; ?>

<table class="table table-bordered">
    <thead>
        <tr>
            <th>Mã</th><th>Loại</th><th>Giá trị</th><th>Tối thiểu</th><th>Lượt dùng</th><th>Đã dùng</th><th>Trạng thái</th><th>Hành động</th>
        </tr>
    </thead>
    <tbody>
        <?php $__currentLoopData = $promotions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $promo): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <tr>
            <td><?php echo e($promo->code); ?></td>
            <td><?php echo e($promo->discount_type); ?></td>
            <td><?php echo e($promo->discount_value); ?></td>
            <td><?php echo e($promo->min_order_amount); ?></td>
            <td><?php echo e($promo->max_usage); ?></td>
            <td><?php echo e($promo->usage_count); ?></td>
            <td><?php echo e($promo->is_active ? '✔️' : '❌'); ?></td>
            <td class="d-flex gap-1">
                <a href="<?php echo e(route('promotions.edit', $promo->id)); ?>" class="btn btn-sm btn-warning">Sửa</a>
                <form method="POST" action="<?php echo e(route('promotions.destroy', $promo->id)); ?>" onsubmit="return confirm('Xoá?')">
                    <?php echo csrf_field(); ?> <?php echo method_field('DELETE'); ?>
                    <button class="btn btn-sm btn-danger">Xoá</button>
                </form>
            </td>
        </tr>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </tbody>
</table>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('admin.layout.AdminLayout', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\xamp\htdocs\DuAnTotNghiep\websiteBanSach\resources\views/admin/promotions/index.blade.php ENDPATH**/ ?>