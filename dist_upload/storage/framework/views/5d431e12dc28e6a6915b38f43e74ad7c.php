

<?php $__env->startSection('content'); ?>
<div class="container py-5">
    <h1 class="mb-4">Subject Details</h1>
    <div class="card mb-4">
        <div class="card-body">
            <h4 class="card-title"><?php echo e($subject->name); ?></h4>
            <p class="card-text"><strong>Code:</strong> <?php echo e($subject->code); ?></p>
            <p class="card-text"><strong>Description:</strong> <?php echo e($subject->description); ?></p>
            <p class="card-text"><strong>Credits:</strong> <?php echo e($subject->credits); ?></p>
            <p class="card-text"><strong>Status:</strong> <?php echo e($subject->is_active ? 'Active' : 'Inactive'); ?></p>
        </div>
    </div>
    <a href="<?php echo e(route('academic.subjects.edit', $subject->id)); ?>" class="btn btn-warning">Edit</a>
    <a href="<?php echo e(route('academic.subjects.index')); ?>" class="btn btn-secondary ms-2">Back to List</a>
</div>
<?php $__env->stopSection(); ?> 
<?php echo $__env->make('academic::components.layouts.master', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\dunth\dunco school management system\duncoschool\Modules\Academic\resources\views\subjects\show.blade.php ENDPATH**/ ?>