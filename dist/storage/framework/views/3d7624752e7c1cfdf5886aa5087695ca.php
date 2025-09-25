

<?php $__env->startSection('content'); ?>
<div class="container py-4">
    <h2>Notifications</h2>
    <ul class="list-group mb-3">
        <?php $__empty_1 = true; $__currentLoopData = $notifications; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $notification): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
            <li class="list-group-item">
                <?php echo e($notification->data['message'] ?? $notification->type); ?>

                <span class="badge bg-<?php echo e($notification->read_at ? 'secondary' : 'primary'); ?> float-end">
                    <?php echo e($notification->read_at ? 'Read' : 'Unread'); ?>

                </span>
            </li>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
            <li class="list-group-item text-muted">No notifications found.</li>
        <?php endif; ?>
    </ul>
    <?php echo e($notifications->links()); ?>

</div>
<?php $__env->stopSection(); ?> 
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\dunth\dunco school management system\duncoschool\dist\resources\views\notifications\index.blade.php ENDPATH**/ ?>