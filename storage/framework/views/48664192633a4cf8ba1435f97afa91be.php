

<?php $__env->startSection('content'); ?>
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="mb-0">Add Department</h2>
        <a href="<?php echo e(route('hr.departments.index')); ?>" class="btn btn-secondary">Back</a>
    </div>
    <div class="card">
        <div class="card-body">
            <form method="POST" action="<?php echo e(route('hr.departments.store')); ?>">
                <?php echo csrf_field(); ?>
                <div class="mb-3">
                    <label class="form-label">Name</label>
                    <input type="text" name="name" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Type</label>
                    <input type="text" name="type" class="form-control">
                </div>
                <div class="mb-3">
                    <label class="form-label">School ID</label>
                    <input type="number" name="school_id" class="form-control">
                </div>
                <button type="submit" class="btn btn-success">Save</button>
                <a href="<?php echo e(route('hr.departments.index')); ?>" class="btn btn-secondary">Cancel</a>
            </form>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?> 
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\dunth\Documents\duncoschool\Modules/HR/resources/views/departments/create.blade.php ENDPATH**/ ?>