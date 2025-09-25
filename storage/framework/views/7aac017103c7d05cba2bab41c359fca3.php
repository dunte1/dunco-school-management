

<?php $__env->startSection('content'); ?>
<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h2>Room Allocations</h2>
        <div>
            <a href="#" class="btn btn-primary">Add Allocation</a>
            <a href="<?php echo e(route('room_allocations.export.csv')); ?>" class="btn btn-outline-secondary">Export CSV</a>
            <a href="<?php echo e(route('room_allocations.export.pdf')); ?>" class="btn btn-outline-secondary">Export PDF</a>
        </div>
    </div>
    <?php if(session('success')): ?>
        <div class="alert alert-success"><?php echo e(session('success')); ?></div>
    <?php endif; ?>
    <table class="table table-bordered table-striped">
        <thead>
            <tr>
                <th>ID</th>
                <th>Room</th>
                <th>Class Schedule</th>
                <th>Allocation Date</th>
            </tr>
        </thead>
        <tbody>
            <?php $__empty_1 = true; $__currentLoopData = $allocations; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $a): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                <tr>
                    <td><?php echo e($a->id); ?></td>
                    <td><?php echo e($a->room_id); ?></td>
                    <td><?php echo e($a->class_schedule_id); ?></td>
                    <td><?php echo e($a->allocation_date); ?></td>
                </tr>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                <tr><td colspan="4" class="text-center">No room allocations found.</td></tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>
<?php $__env->stopSection(); ?> 
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\dunth\Documents\duncoschool\Modules/Timetable/resources/views/room_allocations_index.blade.php ENDPATH**/ ?>