<?php $__env->startSection('title', 'Fee Structures - Finance Module'); ?>

<?php $__env->startSection('content'); ?>
<div class="container-fluid">
    <!-- Header Section -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h2 mb-0">Fee Structures</h1>
            <p class="text-muted mb-0">Manage school fees and fee categories</p>
        </div>
        <div class="d-flex gap-2">
            <a href="<?php echo e(route('finance.fee-types.index')); ?>" class="btn btn-success">
                <i class="fas fa-list me-2"></i>Manage Fee Types
            </a>
            <a href="<?php echo e(route('finance.fee-categories.index')); ?>" class="btn btn-info">
                <i class="fas fa-tags me-2"></i>Manage Categories
            </a>
            <a href="<?php echo e(route('finance.fees.create')); ?>" class="btn btn-primary">
                <i class="fas fa-plus me-2"></i>Add Fee
            </a>
        </div>
    </div>

    <?php if(session('success')): ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="fas fa-check-circle me-2"></i><?php echo e(session('success')); ?>

            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>
    
    <?php if($errors->any()): ?>
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="fas fa-exclamation-triangle me-2"></i>
            <ul class="mb-0 ps-3">
                <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <li><?php echo e($error); ?></li>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </ul>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>

    <!-- Fees Table -->
    <div class="card border-0 shadow-sm">
        <div class="card-header bg-white">
            <h5 class="mb-0"><i class="fas fa-layer-group me-2"></i>Fee Structures List</h5>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead class="table-light">
                        <tr>
                            <th class="px-4 py-3">Name</th>
                            <th class="px-4 py-3">Category</th>
                            <th class="px-4 py-3">Type</th>
                            <th class="px-4 py-3">Amount</th>
                            <th class="px-4 py-3 text-center">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $__empty_1 = true; $__currentLoopData = $fees; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $fee): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <tr>
                            <td class="px-4 py-3">
                                <div>
                                    <strong><?php echo e($fee->name); ?></strong>
                                    <?php if($fee->description): ?>
                                        <br><small class="text-muted"><?php echo e($fee->description); ?></small>
                                    <?php endif; ?>
                                </div>
                            </td>
                            <td class="px-4 py-3">
                                <span class="badge bg-primary"><?php echo e($fee->category->name ?? 'Uncategorized'); ?></span>
                            </td>
                            <td class="px-4 py-3">
                                <span class="badge bg-info"><?php echo e($fee->type->name ?? 'No Type'); ?></span>
                            </td>
                            <td class="px-4 py-3">
                                <strong class="text-success">KES <?php echo e(number_format($fee->amount, 2)); ?></strong>
                            </td>
                            <td class="px-4 py-3 text-center">
                                <div class="btn-group" role="group">
                                    <a href="<?php echo e(route('finance.fees.edit', $fee)); ?>" class="btn btn-sm btn-outline-primary" title="Edit">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <form action="<?php echo e(route('finance.fees.destroy', $fee)); ?>" method="POST" class="d-inline">
                                        <?php echo csrf_field(); ?>
                                        <?php echo method_field('DELETE'); ?>
                                        <button type="submit" class="btn btn-sm btn-outline-danger" onclick="return confirm('Are you sure you want to delete this fee?')" title="Delete">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <tr>
                            <td colspan="5" class="text-center py-5">
                                <div class="text-muted">
                                    <i class="fas fa-inbox fa-3x mb-3"></i>
                                    <h5>No Fee Structures Found</h5>
                                    <p>Start by creating your first fee structure.</p>
                                    <a href="<?php echo e(route('finance.fees.create')); ?>" class="btn btn-primary">
                                        <i class="fas fa-plus me-2"></i>Add First Fee
                                    </a>
                                </div>
                            </td>
                        </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?> 
<?php echo $__env->make('finance::layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\dunth\Documents\duncoschool\Modules/Finance/resources/views/fees/index.blade.php ENDPATH**/ ?>