

<?php $__env->startSection('content'); ?>
<div class="container py-5">
    <h1 class="mb-4">Edit Subject</h1>
    <form method="POST" action="<?php echo e(route('academic.subjects.update', $subject->id)); ?>">
        <?php echo csrf_field(); ?>
        <?php echo method_field('PUT'); ?>
        <div class="mb-3">
            <label for="name" class="form-label">Subject Name</label>
            <input type="text" class="form-control" id="name" name="name" value="<?php echo e(old('name', $subject->name)); ?>" required>
        </div>
        <div class="mb-3">
            <label for="code" class="form-label">Subject Code</label>
            <input type="text" class="form-control" id="code" name="code" value="<?php echo e(old('code', $subject->code)); ?>" required>
        </div>
        <div class="mb-3">
            <label for="description" class="form-label">Description</label>
            <textarea class="form-control" id="description" name="description"><?php echo e(old('description', $subject->description)); ?></textarea>
        </div>
        <div class="mb-3">
            <label for="credits" class="form-label">Credits</label>
            <input type="number" class="form-control" id="credits" name="credits" min="1" max="10" value="<?php echo e(old('credits', $subject->credits)); ?>" required>
        </div>
        <button type="submit" class="btn btn-primary">Update Subject</button>
        <a href="<?php echo e(route('academic.subjects.index')); ?>" class="btn btn-secondary ms-2">Cancel</a>
    </form>
</div>
<?php $__env->stopSection(); ?> 
<?php echo $__env->make('academic::components.layouts.master', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\dunth\dunco school management system\duncoschool\Modules\Academic\resources\views\subjects\edit.blade.php ENDPATH**/ ?>