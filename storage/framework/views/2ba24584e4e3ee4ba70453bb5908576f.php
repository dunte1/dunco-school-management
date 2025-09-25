

<?php $__env->startSection('title', 'Billing & Invoices - Finance Module'); ?>

<?php $__env->startSection('content'); ?>
<div class="container-fluid">
    <!-- Header Section -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h2 mb-0">Billing & Invoices</h1>
            <p class="text-muted mb-0">Manage student invoices and billing</p>
        </div>
        <div class="d-flex gap-2">
            <a href="<?php echo e(route('finance.index')); ?>" class="btn btn-outline-primary">
                <i class="fas fa-arrow-left me-2"></i>Back to Finance
            </a>
            <a href="<?php echo e(route('finance.billing.create')); ?>" class="btn btn-primary">
                <i class="fas fa-plus me-2"></i>Create Invoice
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

    <!-- Invoices Table -->
    <div class="card border-0 shadow-sm">
        <div class="card-header bg-white">
            <h5 class="mb-0"><i class="fas fa-file-invoice-dollar me-2"></i>Invoices List</h5>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead class="table-light">
                        <tr>
                            <th class="px-4 py-3">Student</th>
                            <th class="px-4 py-3">Total Amount</th>
                            <th class="px-4 py-3">Due Date</th>
                            <th class="px-4 py-3">Status</th>
                            <th class="px-4 py-3 text-center">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $__empty_1 = true; $__currentLoopData = $invoices; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $invoice): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <tr>
                            <td class="px-4 py-3">
                                <div>
                                    <strong><?php echo e($invoice->student->name ?? 'Unknown Student'); ?></strong>
                                    <?php if($invoice->student): ?>
                                        <br><small class="text-muted">ID: <?php echo e($invoice->student->id); ?></small>
                                    <?php endif; ?>
                                </div>
                            </td>
                            <td class="px-4 py-3">
                                <strong class="text-success">KES <?php echo e(number_format($invoice->total_amount, 2)); ?></strong>
                            </td>
                            <td class="px-4 py-3">
                                <span class="badge bg-info"><?php echo e($invoice->due_date ? \Carbon\Carbon::parse($invoice->due_date)->format('M d, Y') : 'No due date'); ?></span>
                            </td>
                            <td class="px-4 py-3">
                                <?php if($invoice->status === 'paid'): ?>
                                    <span class="badge bg-success">Paid</span>
                                <?php elseif($invoice->status === 'pending'): ?>
                                    <span class="badge bg-warning">Pending</span>
                                <?php elseif($invoice->status === 'overdue'): ?>
                                    <span class="badge bg-danger">Overdue</span>
                                <?php else: ?>
                                    <span class="badge bg-secondary"><?php echo e(ucfirst($invoice->status)); ?></span>
                                <?php endif; ?>
                            </td>
                            <td class="px-4 py-3 text-center">
                                <div class="btn-group" role="group">
                                    <a href="<?php echo e(route('finance.billing.show', $invoice)); ?>" class="btn btn-sm btn-outline-primary" title="View">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <a href="<?php echo e(route('finance.billing.edit', $invoice)); ?>" class="btn btn-sm btn-outline-secondary" title="Edit">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <form action="<?php echo e(route('finance.billing.destroy', $invoice)); ?>" method="POST" class="d-inline">
                                        <?php echo csrf_field(); ?>
                                        <?php echo method_field('DELETE'); ?>
                                        <button type="submit" class="btn btn-sm btn-outline-danger" onclick="return confirm('Are you sure you want to delete this invoice?')" title="Delete">
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
                                    <i class="fas fa-file-invoice-dollar fa-3x mb-3"></i>
                                    <h5>No Invoices Found</h5>
                                    <p>Start by creating your first invoice.</p>
                                    <a href="<?php echo e(route('finance.billing.create')); ?>" class="btn btn-primary">
                                        <i class="fas fa-plus me-2"></i>Create First Invoice
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
<?php echo $__env->make('finance::layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\dunth\Documents\duncoschool\Modules/Finance/resources/views/billing/index.blade.php ENDPATH**/ ?>