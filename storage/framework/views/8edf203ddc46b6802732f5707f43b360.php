<?php $__env->startSection('content'); ?>
<div class="container py-4">
    <h1 class="mb-4">Communication Dashboard</h1>
    <div class="row g-4">
        <div class="col-md-3">
            <a href="<?php echo e(route('communication.inbox')); ?>" class="card text-decoration-none h-100">
                <div class="card-body text-center">
                    <i class="fas fa-inbox fa-2x mb-2"></i>
                    <h5 class="card-title">Inbox</h5>
                    <p class="card-text">View your received messages.</p>
                </div>
            </a>
        </div>
        <div class="col-md-3">
            <a href="<?php echo e(route('communication.outbox')); ?>" class="card text-decoration-none h-100">
                <div class="card-body text-center">
                    <i class="fas fa-paper-plane fa-2x mb-2"></i>
                    <h5 class="card-title">Outbox</h5>
                    <p class="card-text">View messages you have sent.</p>
                </div>
            </a>
        </div>
        <div class="col-md-3">
            <a href="<?php echo e(route('communication.compose')); ?>" class="card text-decoration-none h-100">
                <div class="card-body text-center">
                    <i class="fas fa-edit fa-2x mb-2"></i>
                    <h5 class="card-title">Compose</h5>
                    <p class="card-text">Send a new message.</p>
                </div>
            </a>
        </div>
        <div class="col-md-3">
            <a href="/settings-global" class="card text-decoration-none h-100">
                <div class="card-body text-center">
                    <i class="fas fa-cogs fa-2x mb-2"></i>
                    <h5 class="card-title">Global Settings</h5>
                    <p class="card-text">Manage communication configurations.</p>
                </div>
            </a>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('communication::layouts.master', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\dunth\Documents\duncoschool\Modules/Communication/resources/views/index.blade.php ENDPATH**/ ?>