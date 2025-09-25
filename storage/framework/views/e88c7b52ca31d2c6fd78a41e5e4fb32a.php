<?php $__env->startSection('content'); ?>
    <div class="container">
        <h1>Settings</h1>
        <?php if(session('success')): ?>
            <div class="alert alert-success"><?php echo e(session('success')); ?></div>
        <?php endif; ?>
        <a href="<?php echo e(route('settings.create')); ?>" class="btn btn-primary mb-3">Add Setting</a>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Key</th>
                    <th>Value</th>
                    <th>Type</th>
                    <th>Description</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php $__currentLoopData = $settings; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $setting): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <tr>
                        <td><?php echo e($setting->id); ?></td>
                        <td><?php echo e($setting->key); ?></td>
                        <td><?php echo e($setting->value); ?></td>
                        <td><?php echo e($setting->type); ?></td>
                        <td><?php echo e($setting->description); ?></td>
                        <td>
                            <a href="<?php echo e(route('settings.show', $setting->id)); ?>" class="btn btn-info btn-sm">Show</a>
                            <a href="<?php echo e(route('settings.edit', $setting->id)); ?>" class="btn btn-warning btn-sm">Edit</a>
                            <form action="<?php echo e(route('settings.destroy', $setting->id)); ?>" method="POST" style="display:inline-block;">
                                <?php echo csrf_field(); ?>
                                <?php echo method_field('DELETE'); ?>
                                <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure?')">Delete</button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </tbody>
        </table>
        <?php echo e($settings->links()); ?>

    </div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\dunth\Documents\duncoschool\Modules/Settings/resources/views/index.blade.php ENDPATH**/ ?>