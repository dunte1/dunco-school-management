
<?php $__env->startSection('content'); ?>
<div class="container py-4">
    <h2 class="mb-4 text-uppercase" style="color:#6ec1e4;"><?php echo e($student->full_name ?? $student->name); ?> - Attendance History</h2>
    <div class="mb-3">
        <span class="badge bg-success">Present: <?php echo e($presentDays); ?></span>
        <span class="badge bg-danger">Absent: <?php echo e($absentDays); ?></span>
        <span class="badge bg-warning text-dark">Late: <?php echo e($lateDays); ?></span>
        <span class="badge bg-info text-dark">Excused: <?php echo e($excusedDays); ?></span>
        <span class="badge bg-primary">Attendance Rate: <?php echo e($attendanceRate); ?>%</span>
    </div>
    <div class="table-responsive bg-dark rounded shadow p-3">
        <table class="table table-dark table-hover align-middle mb-0">
            <thead>
                <tr>
                    <th>Date</th>
                    <th>Class</th>
                    <th>Status</th>
                    <th>Remarks</th>
                </tr>
            </thead>
            <tbody>
                <?php $__currentLoopData = $attendanceRecords; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $record): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <tr>
                    <td><?php echo e($record->date->format('Y-m-d')); ?></td>
                    <td><?php echo e($record->class->name ?? '-'); ?></td>
                    <td><span class="badge bg-<?php echo e($record->status_color); ?> text-uppercase"><?php echo e(ucfirst($record->status)); ?></span></td>
                    <td><?php echo e($record->remarks); ?></td>
                </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </tbody>
        </table>
    </div>
    <div class="mt-3">
        <?php echo e($attendanceRecords->links()); ?>

    </div>
</div>
<?php $__env->stopSection(); ?>
<?php $__env->startPush('styles'); ?>
<style>
body { background: #0a1931; color: #eaf6fb; }
.table-dark th, .table-dark td { color: #eaf6fb; }
.badge.bg-success { background: #00bcd4; }
.badge.bg-danger { background: #e53935; }
.badge.bg-warning { background: #ffb300; color: #222; }
.badge.bg-info { background: #6ec1e4; color: #222; }
.badge.bg-primary { background: #1565c0; }
</style>
<?php $__env->stopPush(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\dunth\dunco school management system\duncoschool\Modules\Academic\resources\views\attendance\student-history.blade.php ENDPATH**/ ?>