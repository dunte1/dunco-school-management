
<?php $__env->startSection('content'); ?>
<div class="container py-4">
    <h2 class="mb-4 text-uppercase" style="color:#6ec1e4;">Attendance Statistics</h2>
    <form method="GET" action="" class="mb-3 d-flex flex-wrap align-items-end gap-3">
        <div>
            <label for="class_id" class="form-label text-muted text-uppercase">Class</label>
            <select name="class_id" id="class_id" class="form-select rounded-pill bg-dark text-white" style="min-width:200px;">
                <option value="">Select Class</option>
                <?php $__currentLoopData = $classes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $class): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <option value="<?php echo e($class->id); ?>" <?php if(request('class_id') == $class->id): ?> selected <?php endif; ?>><?php echo e($class->name); ?></option>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </select>
        </div>
        <div>
            <label for="start_date" class="form-label text-muted text-uppercase">Start Date</label>
            <input type="date" name="start_date" id="start_date" value="<?php echo e(request('start_date')); ?>" class="form-control rounded-pill bg-dark text-white" style="min-width:150px;">
        </div>
        <div>
            <label for="end_date" class="form-label text-muted text-uppercase">End Date</label>
            <input type="date" name="end_date" id="end_date" value="<?php echo e(request('end_date')); ?>" class="form-control rounded-pill bg-dark text-white" style="min-width:150px;">
        </div>
        <button type="submit" class="btn btn-primary rounded-pill px-4">View Statistics</button>
    </form>
    <?php if($selectedClass && $statistics): ?>
    <div class="mb-3 d-flex gap-2">
        <form method="POST" action="<?php echo e(route('academic.attendance.export')); ?>" class="d-inline">
            <?php echo csrf_field(); ?>
            <input type="hidden" name="class_id" value="<?php echo e(request('class_id')); ?>">
            <input type="hidden" name="start_date" value="<?php echo e(request('start_date')); ?>">
            <input type="hidden" name="end_date" value="<?php echo e(request('end_date')); ?>">
            <input type="hidden" name="format" value="csv">
            <button type="submit" class="btn btn-outline-primary rounded-pill">Export CSV</button>
        </form>
        <form method="POST" action="<?php echo e(route('academic.attendance.export')); ?>" class="d-inline">
            <?php echo csrf_field(); ?>
            <input type="hidden" name="class_id" value="<?php echo e(request('class_id')); ?>">
            <input type="hidden" name="start_date" value="<?php echo e(request('start_date')); ?>">
            <input type="hidden" name="end_date" value="<?php echo e(request('end_date')); ?>">
            <input type="hidden" name="format" value="xlsx">
            <button type="submit" class="btn btn-outline-success rounded-pill">Export Excel</button>
        </form>
    </div>
    <div class="bg-dark rounded shadow p-4 mt-3">
        <h4 class="text-info mb-3"><?php echo e($selectedClass->name); ?> Statistics</h4>
        <ul class="list-group list-group-flush">
            <li class="list-group-item bg-dark text-white">Total Records: <span class="fw-bold"><?php echo e($statistics['total']); ?></span></li>
            <li class="list-group-item bg-dark text-white">Present: <span class="fw-bold text-success"><?php echo e($statistics['present']); ?></span></li>
            <li class="list-group-item bg-dark text-white">Absent: <span class="fw-bold text-danger"><?php echo e($statistics['absent']); ?></span></li>
            <li class="list-group-item bg-dark text-white">Late: <span class="fw-bold text-warning"><?php echo e($statistics['late']); ?></span></li>
            <li class="list-group-item bg-dark text-white">Excused: <span class="fw-bold text-info"><?php echo e($statistics['excused']); ?></span></li>
            <li class="list-group-item bg-dark text-white">Attendance Rate: <span class="fw-bold text-primary"><?php echo e($statistics['attendance_rate']); ?>%</span></li>
            <li class="list-group-item bg-dark text-white">Absent Rate: <span class="fw-bold text-danger"><?php echo e($statistics['absent_rate']); ?>%</span></li>
        </ul>
    </div>
    <?php elseif(request('class_id')): ?>
        <div class="alert alert-warning mt-4">No statistics found for this class and date range.</div>
    <?php endif; ?>
</div>
<?php $__env->stopSection(); ?>
<?php $__env->startPush('styles'); ?>
<style>
body { background: #0a1931; color: #eaf6fb; }
.list-group-item { border: none; }
.text-success { color: #00bcd4 !important; }
.text-danger { color: #e53935 !important; }
.text-warning { color: #ffb300 !important; }
.text-info { color: #6ec1e4 !important; }
.text-primary { color: #1565c0 !important; }
</style>
<?php $__env->stopPush(); ?> 
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\dunth\dunco school management system\duncoschool\Modules\Academic\resources\views\attendance\statistics.blade.php ENDPATH**/ ?>