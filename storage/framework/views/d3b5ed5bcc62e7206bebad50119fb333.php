

<?php $__env->startSection('content'); ?>
<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h2>Teacher Availabilities</h2>
        <a href="<?php echo e(route('teacher_availabilities.create')); ?>" class="btn btn-primary">Add Availability</a>
    </div>
    <?php if(session('success')): ?>
        <div class="alert alert-success"><?php echo e(session('success')); ?></div>
    <?php endif; ?>
    <div class="mb-3">
        <button class="btn btn-outline-secondary me-2" id="gridViewBtn">
            <i class="fas fa-th"></i> Weekly Grid View
        </button>
        <button class="btn btn-outline-secondary" id="calendarViewBtn">
            <i class="fas fa-calendar-alt"></i> Calendar View
        </button>
    </div>
    <div id="weeklyGridView" style="display:none;">
        <div class="table-responsive">
            <table class="table table-bordered align-middle text-center">
                <thead class="table-light">
                    <tr>
                        <th>Teacher</th>
                        <th>Monday</th>
                        <th>Tuesday</th>
                        <th>Wednesday</th>
                        <th>Thursday</th>
                        <th>Friday</th>
                        <th>Saturday</th>
                        <th>Sunday</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $days = ['Monday','Tuesday','Wednesday','Thursday','Friday','Saturday','Sunday'];
                    $teachers = $availabilities->pluck('teacher')->unique('id')->filter();
                    ?>
                    <?php $__currentLoopData = $teachers; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $teacher): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <tr>
                        <th class="text-start"><?php echo e($teacher->first_name); ?> <?php echo e($teacher->last_name); ?></th>
                        <?php $__currentLoopData = $days; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $day): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <td>
                                <?php
                                    $slots = $teacher->availabilities->where('day_of_week', $day);
                                ?>
                                <?php if($slots->isEmpty()): ?>
                                    <span class="text-muted">—</span>
                                <?php else: ?>
                                    <?php $__currentLoopData = $slots; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $slot): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <div class="badge bg-success mb-1"><?php echo e($slot->start_time); ?> - <?php echo e($slot->end_time); ?></div><br>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                <?php endif; ?>
                            </td>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </tbody>
            </table>
        </div>
    </div>
    <div id="calendarView" style="display:none;">
        <form class="mb-3">
            <label for="calendarTeacher" class="form-label">Select Teacher</label>
            <select id="calendarTeacher" class="form-select" onchange="showTeacherCalendar()">
                <option value="">-- Select --</option>
                <?php $__currentLoopData = $teachers; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $teacher): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <option value="<?php echo e($teacher->id); ?>"><?php echo e($teacher->first_name); ?> <?php echo e($teacher->last_name); ?></option>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </select>
        </form>
        <div id="teacherCalendarContainer">
            <div class="alert alert-info">Select a teacher to view their weekly availability.</div>
        </div>
    </div>
    <script>
        document.getElementById('gridViewBtn').addEventListener('click', function() {
            document.getElementById('weeklyGridView').style.display = '';
            document.getElementById('calendarView').style.display = 'none';
        });
        document.getElementById('calendarViewBtn').addEventListener('click', function() {
            document.getElementById('weeklyGridView').style.display = 'none';
            document.getElementById('calendarView').style.display = '';
        });

        function showTeacherCalendar() {
            var teacherId = document.getElementById('calendarTeacher').value;
            var container = document.getElementById('teacherCalendarContainer');
            if (!teacherId) {
                container.innerHTML = '<div class="alert alert-info">Select a teacher to view their weekly availability.</div>';
                return;
            }
            var teachers = <?php echo json_encode($teachers->keyBy('id'), 15, 512) ?>;
            var availabilities = <?php echo json_encode($availabilities->groupBy('teacher_id'), 15, 512) ?>;
            var teacher = teachers[teacherId];
            var slots = availabilities[teacherId] || [];
            var days = ['Monday','Tuesday','Wednesday','Thursday','Friday','Saturday','Sunday'];
            var html = '<h5 class="mb-3">' + teacher.first_name + ' ' + teacher.last_name + ' - Weekly Availability</h5>';
            html += '<table class="table table-bordered text-center"><thead><tr><th>Day</th><th>Available Times</th></tr></thead><tbody>';
            days.forEach(function(day) {
                html += '<tr><th>' + day + '</th><td>';
                var found = false;
                slots.forEach(function(slot) {
                    if (slot.day_of_week === day) {
                        html += '<span class="badge bg-success mb-1">' + slot.start_time + ' - ' + slot.end_time + '</span> ';
                        found = true;
                    }
                });
                if (!found) html += '<span class="text-muted">—</span>';
                html += '</td></tr>';
            });
            html += '</tbody></table>';
            container.innerHTML = html;
        }
    </script>
    <table class="table table-bordered table-striped">
        <thead>
            <tr>
                <th>ID</th>
                <th>Teacher</th>
                <th>Day</th>
                <th>Start</th>
                <th>End</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php $__empty_1 = true; $__currentLoopData = $availabilities; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $a): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                <tr>
                    <td><?php echo e($a->id); ?></td>
                    <td><?php echo e($a->teacher ? $a->teacher->first_name . ' ' . $a->teacher->last_name : $a->teacher_id); ?></td>
                    <td><span class="badge bg-primary"><?php echo e($a->day_of_week); ?></span></td>
                    <td><span class="badge bg-success"><?php echo e($a->start_time); ?></span></td>
                    <td><span class="badge bg-danger"><?php echo e($a->end_time); ?></span></td>
                    <td>
                        <a href="<?php echo e(route('teacher_availabilities.show', $a->id)); ?>" class="btn btn-sm btn-info">View</a>
                        <a href="<?php echo e(route('teacher_availabilities.edit', $a->id)); ?>" class="btn btn-sm btn-warning">Edit</a>
                        <form action="<?php echo e(route('teacher_availabilities.destroy', $a->id)); ?>" method="POST" style="display:inline-block;">
                            <?php echo csrf_field(); ?>
                            <?php echo method_field('DELETE'); ?>
                            <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Delete this availability?')">Delete</button>
                        </form>
                    </td>
                </tr>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                <tr><td colspan="6" class="text-center">No availabilities found.</td></tr>
            <?php endif; ?>
        </tbody>
    </table>
    <?php echo e($availabilities->links()); ?>

</div>
<?php $__env->stopSection(); ?> 
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\dunth\Documents\duncoschool\Modules/Timetable/resources/views/teacher_availabilities/index.blade.php ENDPATH**/ ?>