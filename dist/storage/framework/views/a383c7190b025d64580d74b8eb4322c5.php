
<?php $__env->startSection('content'); ?>
<div class="container py-4">
    <h2 class="mb-4 text-uppercase" style="color:#6ec1e4;">Attendance Report</h2>
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
        <button type="submit" class="btn btn-primary rounded-pill px-4">View Report</button>
    </form>
    <?php if($students->count() && $dateRange): ?>
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
    <div class="table-responsive bg-dark rounded shadow p-3">
        <table class="table table-dark table-hover align-middle mb-0">
            <thead>
                <tr>
                    <th>Student</th>
                    <th>Present</th>
                    <th>Absent</th>
                    <th>Late</th>
                    <th>Excused</th>
                </tr>
            </thead>
            <tbody>
                <?php $__currentLoopData = $students; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $student): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <tr>
                    <td class="text-white"><?php echo e($student->full_name ?? $student->name); ?></td>
                    <td><?php echo e($attendanceData[$student->id]->where('status','present')->count() ?? 0); ?></td>
                    <td><?php echo e($attendanceData[$student->id]->where('status','absent')->count() ?? 0); ?></td>
                    <td><?php echo e($attendanceData[$student->id]->where('status','late')->count() ?? 0); ?></td>
                    <td><?php echo e($attendanceData[$student->id]->where('status','excused')->count() ?? 0); ?></td>
                </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </tbody>
        </table>
    </div>
    <?php elseif(request('class_id')): ?>
        <div class="alert alert-warning mt-4">No attendance data found for this class and date range.</div>
    <?php endif; ?>
</div>
<?php $__env->stopSection(); ?>
<?php $__env->startPush('styles'); ?>
<style>
body { background: #0a1931; color: #eaf6fb; }
.table-dark th, .table-dark td { color: #eaf6fb; }
.btn-primary { background: #1565c0; border: none; }
.form-select, .form-control { background: #11224d; color: #eaf6fb; border: 1px solid #22336b; }
.form-select:focus, .form-control:focus { border-color: #6ec1e4; box-shadow: 0 0 0 0.2rem #6ec1e480; }
</style>
<?php $__env->stopPush(); ?> 
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\dunth\dunco school management system\duncoschool\dist\Modules\Academic\resources\views\attendance\report.blade.php ENDPATH**/ ?>