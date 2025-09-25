

<?php $__env->startSection('content'); ?>
<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h3 class="fw-bold">Class Schedules</h3>
        <a href="<?php echo e(route('class_schedules.create')); ?>" class="btn btn-primary">
            <i class="fas fa-plus"></i> Create New Class Schedule
        </a>
    </div>
    <?php if(session('success')): ?>
        <div class="alert alert-success"><?php echo e(session('success')); ?></div>
    <?php endif; ?>
    <?php if($errors->any()): ?>
        <div class="alert alert-danger">
            <ul class="mb-0">
                <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <li><?php echo e($error); ?></li>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </ul>
        </div>
    <?php endif; ?>
    <form method="GET" class="row g-3 mb-4">
        <div class="col-md-3">
            <select name="academic_class_id" class="form-select" onchange="this.form.submit()">
                <option value="">All Classes</option>
                <?php $__currentLoopData = $classes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $class): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <option value="<?php echo e($class->id); ?>" <?php if(request('academic_class_id') == $class->id): ?> selected <?php endif; ?>><?php echo e($class->name); ?></option>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </select>
        </div>
        <div class="col-md-3">
            <select name="teacher_id" class="form-select" onchange="this.form.submit()">
                <option value="">All Teachers</option>
                <?php $__currentLoopData = $teachers; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $teacher): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <option value="<?php echo e($teacher->id); ?>" <?php if(request('teacher_id') == $teacher->id): ?> selected <?php endif; ?>><?php echo e($teacher->first_name); ?> <?php echo e($teacher->last_name); ?></option>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </select>
        </div>
        <div class="col-md-3">
            <select name="room_id" class="form-select" onchange="this.form.submit()">
                <option value="">All Rooms</option>
                <?php $__currentLoopData = $rooms; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $room): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <option value="<?php echo e($room->id); ?>" <?php if(request('room_id') == $room->id): ?> selected <?php endif; ?>><?php echo e($room->name); ?></option>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </select>
        </div>
        <div class="col-md-3">
            <select name="day_of_week" class="form-select" onchange="this.form.submit()">
                <option value="">All Days</option>
                <?php $__currentLoopData = ['Monday','Tuesday','Wednesday','Thursday','Friday','Saturday','Sunday']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $day): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <option value="<?php echo e($day); ?>" <?php if(request('day_of_week') == $day): ?> selected <?php endif; ?>><?php echo e($day); ?></option>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </select>
        </div>
    </form>
    <div class="table-responsive">
        <table class="table table-bordered table-hover align-middle">
            <thead class="table-light">
                <tr>
                    <th>Class</th>
                    <th>Teacher</th>
                    <th>Room</th>
                    <th>Timetable</th>
                    <th>Day</th>
                    <th>Start</th>
                    <th>End</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php $__empty_1 = true; $__currentLoopData = $schedules; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $schedule): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <tr>
                        <td><?php echo e($schedule->academicClass->name ?? '-'); ?></td>
                        <td><?php echo e($schedule->teacher->first_name ?? ''); ?> <?php echo e($schedule->teacher->last_name ?? ''); ?></td>
                        <td><?php echo e($schedule->room->name ?? '-'); ?></td>
                        <td><?php echo e($schedule->timetable->name ?? '-'); ?></td>
                        <td><?php echo e($schedule->day_of_week); ?></td>
                        <td><?php echo e($schedule->start_time); ?></td>
                        <td><?php echo e($schedule->end_time); ?></td>
                        <td>
                            <a href="<?php echo e(route('class_schedules.show', $schedule->id)); ?>" class="btn btn-sm btn-info">View</a>
                            <a href="<?php echo e(route('class_schedules.edit', $schedule->id)); ?>" class="btn btn-sm btn-warning">Edit</a>
                            <form action="<?php echo e(route('class_schedules.destroy', $schedule->id)); ?>" method="POST" class="d-inline" onsubmit="return confirm('Are you sure?')">
                                <?php echo csrf_field(); ?>
                                <?php echo method_field('DELETE'); ?>
                                <button class="btn btn-sm btn-danger">Delete</button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <tr><td colspan="8" class="text-center">No class schedules found.</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
        <div><?php echo e($schedules->links()); ?></div>
    </div>
</div>
<?php $__env->stopSection(); ?> 
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\dunth\Documents\duncoschool\Modules/Timetable/resources/views/class_schedules/index.blade.php ENDPATH**/ ?>