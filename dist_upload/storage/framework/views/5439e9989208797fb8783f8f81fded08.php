

<?php $__env->startSection('content'); ?>
<div class="container">
    <h1>Add Grading Scale</h1>
    <form method="POST" action="<?php echo e(route('academic.grading.store')); ?>">
        <?php echo csrf_field(); ?>
        <div class="mb-3">
            <label for="name" class="form-label">Name</label>
            <input type="text" class="form-control" id="name" name="name" required>
        </div>
        <div class="mb-3">
            <label for="description" class="form-label">Description</label>
            <textarea class="form-control" id="description" name="description"></textarea>
        </div>
        <button type="submit" class="btn btn-primary">Save</button>
        <a href="<?php echo e(route('academic.grading.index')); ?>" class="btn btn-secondary">Cancel</a>
    </form>
</div>
<?php $__env->stopSection(); ?> 
<?php echo $__env->make('academic::layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\dunth\dunco school management system\duncoschool\Modules\Academic\resources\views\grading\create.blade.php ENDPATH**/ ?>