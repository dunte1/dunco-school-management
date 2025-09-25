

<?php $__env->startSection('content'); ?>
<div class="container">
    <h1>Grading Scales</h1>
    <a href="<?php echo e(route('academic.grading.create')); ?>" class="btn btn-primary mb-3">Add Grading Scale</a>
    <?php $__currentLoopData = $scales; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $scale): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <div class="card mb-3">
            <div class="card-header d-flex justify-content-between align-items-center">
                <span><?php echo e($scale->name); ?></span>
                <a href="<?php echo e(route('academic.grading.edit', $scale->id)); ?>" class="btn btn-sm btn-secondary">Edit</a>
            </div>
            <div class="card-body">
                <p><?php echo e($scale->description); ?></p>
                <h5>Grades</h5>
                <ul>
                    <?php $__currentLoopData = $scale->grades; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $grade): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <li><?php echo e($grade->name); ?>: <?php echo e($grade->min_score); ?> - <?php echo e($grade->max_score); ?> (<?php echo e($grade->description); ?>)</li>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </ul>
            </div>
        </div>
    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
</div>
<?php $__env->stopSection(); ?> 
<?php echo $__env->make('academic::layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\dunth\dunco school management system\duncoschool\Modules\Academic\resources\views\grading\index.blade.php ENDPATH**/ ?>