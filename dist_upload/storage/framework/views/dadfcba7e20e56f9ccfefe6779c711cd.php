

<?php $__env->startSection('content'); ?>
<div class="container">
    <h1>Notification Templates</h1>
    <a href="<?php echo e(route('admin.notifications.create')); ?>" class="btn btn-primary mb-3">Create New Template</a>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Event</th>
                <th>Channel</th>
                <th>Subject</th>
                <th>Status</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php $__currentLoopData = $templates; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $tpl): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <tr>
                    <td><?php echo e($tpl->event); ?></td>
                    <td><?php echo e($tpl->channel); ?></td>
                    <td><?php echo e($tpl->subject); ?></td>
                    <td>
                        <?php if($tpl->is_active): ?>
                            <span class="badge bg-success">Active</span>
                        <?php else: ?>
                            <span class="badge bg-secondary">Inactive</span>
                        <?php endif; ?>
                    </td>
                    <td>
                        <a href="<?php echo e(route('admin.notifications.edit', $tpl->id)); ?>" class="btn btn-sm btn-warning">Edit</a>
                        <form method="POST" action="<?php echo e(route('admin.notifications.toggle', $tpl->id)); ?>" style="display:inline-block;">
                            <?php echo csrf_field(); ?>
                            <button type="submit" class="btn btn-sm btn-secondary"><?php echo e($tpl->is_active ? 'Deactivate' : 'Activate'); ?></button>
                        </form>
                    </td>
                </tr>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </tbody>
    </table>
</div>
<?php $__env->stopSection(); ?> 
<?php echo $__env->make('academic::layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\dunth\dunco school management system\duncoschool\Modules\Academic\resources\views\admin\notifications\index.blade.php ENDPATH**/ ?>