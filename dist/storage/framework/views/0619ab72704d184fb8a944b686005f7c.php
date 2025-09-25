

<?php $__env->startSection('content'); ?>
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h1 class="h3 mb-0">HR Dashboard</h1>
                <div class="text-muted">Welcome back, <?php echo e(Auth::user()->name); ?>!</div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="card shadow">
                <div class="card-body text-center py-5">
                    <i class="fas fa-users-cog fa-3x text-muted mb-3"></i>
                    <h4 class="text-muted">HR Dashboard</h4>
                    <p class="text-muted">This dashboard is under development.</p>
                    <p class="text-muted">Please contact the administrator for more information.</p>
                </div>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>





<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\dunth\dunco school management system\duncoschool\dist\resources\views\dashboard\hr.blade.php ENDPATH**/ ?>