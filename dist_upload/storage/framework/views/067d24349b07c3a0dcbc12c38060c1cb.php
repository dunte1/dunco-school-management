

<?php $__env->startSection('content'); ?>
<div class="container">
    <h1>Edit Grading Scale</h1>
    <form method="POST" action="<?php echo e(route('academic.grading.update', $scale->id)); ?>">
        <?php echo csrf_field(); ?>
        <?php echo method_field('PUT'); ?>
        <div class="mb-3">
            <label for="name" class="form-label">Name</label>
            <input type="text" class="form-control" id="name" name="name" value="<?php echo e($scale->name); ?>" required>
        </div>
        <div class="mb-3">
            <label for="description" class="form-label">Description</label>
            <textarea class="form-control" id="description" name="description"><?php echo e($scale->description); ?></textarea>
        </div>
        <button type="submit" class="btn btn-primary">Update</button>
        <a href="<?php echo e(route('academic.grading.index')); ?>" class="btn btn-secondary">Cancel</a>
    </form>

    <hr>
    <h2>Grades</h2>
    <form method="POST" action="<?php echo e(route('academic.grading.grade.store', $scale->id)); ?>" class="mb-4">
        <?php echo csrf_field(); ?>
        <div class="row">
            <div class="col">
                <input type="text" name="name" class="form-control" placeholder="Grade Name (e.g. A)" required>
            </div>
            <div class="col">
                <input type="number" name="min_score" class="form-control" placeholder="Min Score" min="0" max="100" required>
            </div>
            <div class="col">
                <input type="number" name="max_score" class="form-control" placeholder="Max Score" min="0" max="100" required>
            </div>
            <div class="col">
                <input type="text" name="description" class="form-control" placeholder="Description">
            </div>
            <div class="col-auto">
                <button type="submit" class="btn btn-success">Add Grade</button>
            </div>
        </div>
    </form>
    <table class="table">
        <thead>
            <tr>
                <th>Name</th>
                <th>Min</th>
                <th>Max</th>
                <th>Description</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php $__currentLoopData = $scale->grades; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $grade): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <tr>
                    <form method="POST" action="<?php echo e(route('academic.grading.grade.update', [$scale->id, $grade->id])); ?>">
                        <?php echo csrf_field(); ?>
                        <?php echo method_field('PUT'); ?>
                        <td><input type="text" name="name" value="<?php echo e($grade->name); ?>" class="form-control" required></td>
                        <td><input type="number" name="min_score" value="<?php echo e($grade->min_score); ?>" class="form-control" min="0" max="100" required></td>
                        <td><input type="number" name="max_score" value="<?php echo e($grade->max_score); ?>" class="form-control" min="0" max="100" required></td>
                        <td><input type="text" name="description" value="<?php echo e($grade->description); ?>" class="form-control"></td>
                        <td>
                            <button type="submit" class="btn btn-primary btn-sm">Update</button>
                    </form>
                    <form method="POST" action="<?php echo e(route('academic.grading.grade.destroy', [$scale->id, $grade->id])); ?>" style="display:inline-block;">
                        <?php echo csrf_field(); ?>
                        <?php echo method_field('DELETE'); ?>
                        <button type="submit" class="btn btn-danger btn-sm">Delete</button>
                    </form>
                        </td>
                </tr>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </tbody>
    </table>
</div>
<?php $__env->stopSection(); ?> 
<?php echo $__env->make('academic::layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\dunth\dunco school management system\duncoschool\Modules\Academic\resources\views\grading\edit.blade.php ENDPATH**/ ?>