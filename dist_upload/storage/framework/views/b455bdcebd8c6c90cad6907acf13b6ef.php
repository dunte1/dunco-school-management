

<?php $__env->startSection('content'); ?>
<div class="container">
    <h1>Bulk Import Preview</h1>
    <form method="POST" action="<?php echo e(route('academic.students.handle_bulk_import')); ?>">
        <?php echo csrf_field(); ?>
        <input type="hidden" name="confirm" value="1">
        <button type="submit" class="btn btn-success mb-3" <?php echo e(count($validRows) ? '' : 'disabled'); ?>>Import Valid Students</button>
    </form>
    <h4>Valid Rows (<?php echo e(count($validRows)); ?>)</h4>
    <table class="table table-bordered">
        <thead>
            <tr>
                <?php $__currentLoopData = array_keys($validRows[0] ?? []); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $col): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <?php if(strpos($col, '__') !== 0): ?>
                        <th><?php echo e($col); ?></th>
                    <?php endif; ?>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </tr>
        </thead>
        <tbody>
            <?php $__currentLoopData = $validRows; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $row): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <tr>
                    <?php $__currentLoopData = $row; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $col => $val): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <?php if(strpos($col, '__') !== 0): ?>
                            <td><?php echo e($val); ?></td>
                        <?php endif; ?>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </tr>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </tbody>
    </table>
    <h4 class="mt-4">Errors (<?php echo e(count($errors)); ?>)</h4>
    <a href="<?php echo e(route('academic.students.bulk_import_errors')); ?>" class="btn btn-outline-danger btn-sm mb-2">Download Error Report (CSV)</a>
    <table class="table table-bordered">
        <thead>
            <tr>
                <?php $__currentLoopData = array_keys($errors[0] ?? []); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $col): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <?php if(strpos($col, '__') !== 0): ?>
                        <th><?php echo e($col); ?></th>
                    <?php endif; ?>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                <th>Errors</th>
            </tr>
        </thead>
        <tbody>
            <?php $__currentLoopData = $errors; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $row): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <tr>
                    <?php $__currentLoopData = $row; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $col => $val): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <?php if(strpos($col, '__') !== 0): ?>
                            <td><?php echo e($val); ?></td>
                        <?php endif; ?>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    <td>
                        <?php if(isset($row['__errors'])): ?>
                            <ul>
                                <?php $__currentLoopData = $row['__errors']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $err): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <li class="text-danger"><?php echo e($err); ?></li>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </ul>
                        <?php endif; ?>
                    </td>
                </tr>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </tbody>
    </table>
</div>
<?php $__env->stopSection(); ?> 
<?php echo $__env->make('academic::layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\dunth\dunco school management system\duncoschool\Modules\Academic\resources\views\students\bulk_import_preview.blade.php ENDPATH**/ ?>