

<?php $__env->startSection('title', 'Create Role'); ?>

<?php $__env->startSection('content'); ?>
<div class="container">
    <h1 class="mb-4">Create Role</h1>
    <form method="POST" action="<?php echo e(route('core.roles.store')); ?>">
        <?php echo csrf_field(); ?>
        <div class="mb-3">
            <label for="name" class="form-label">Name</label>
            <input type="text" class="form-control" id="name" name="name" value="<?php echo e(old('name')); ?>" required>
        </div>
        <div class="mb-3">
            <label for="display_name" class="form-label">Display Name</label>
            <input type="text" class="form-control" id="display_name" name="display_name" value="<?php echo e(old('display_name')); ?>">
        </div>
        <div class="mb-3">
            <label for="description" class="form-label">Description</label>
            <textarea class="form-control" id="description" name="description"><?php echo e(old('description')); ?></textarea>
        </div>
        <div class="mb-3">
            <label for="permissions" class="form-label">Permissions</label>
            <select class="form-select" id="permissions" name="permissions[]" multiple>
                <?php $__currentLoopData = $permissions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $permission): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <option value="<?php echo e($permission->id); ?>"><?php echo e($permission->display_name ?? $permission->name); ?></option>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </select>
            <small class="form-text text-muted">Hold Ctrl (Windows) or Command (Mac) to select multiple permissions.</small>
        </div>
        <button type="submit" class="btn btn-primary">Create Role</button>
        <a href="<?php echo e(route('core.roles.index')); ?>" class="btn btn-secondary">Cancel</a>
    </form>
</div>
<?php $__env->stopSection(); ?> 
<?php echo $__env->make('core::layouts.master', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\dunth\dunco school management system\duncoschool\Modules\Core\resources\views\roles\create.blade.php ENDPATH**/ ?>