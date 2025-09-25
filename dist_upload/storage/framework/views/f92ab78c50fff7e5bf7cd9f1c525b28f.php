

<?php $__env->startSection('content'); ?>
<div class="container py-4">
    <div class="attendance-header-gradient rounded shadow-sm mb-4 p-3 d-flex align-items-center">
        <h1 class="mb-0 text-uppercase attendance-accent"><i class="bi bi-calendar-check"></i> Class Attendance</h1>
    </div>
    <form method="GET" action="" class="mb-3 d-flex flex-wrap align-items-end gap-3">
        <div class="me-4">
            <label class="form-label text-muted text-uppercase">Attendance Type</label><br>
            <div class="form-check form-check-inline">
                <input class="form-check-input" type="radio" name="attendance_type" id="student_attendance" value="student" <?php echo e(request('attendance_type', 'student') == 'student' ? 'checked' : ''); ?> onchange="this.form.submit()">
                <label class="form-check-label" for="student_attendance">Student</label>
            </div>
            <div class="form-check form-check-inline">
                <input class="form-check-input" type="radio" name="attendance_type" id="staff_attendance" value="staff" <?php echo e(request('attendance_type') == 'staff' ? 'checked' : ''); ?> onchange="this.form.submit()">
                <label class="form-check-label" for="staff_attendance">Staff</label>
            </div>
        </div>
        <div>
            <div class="form-floating">
                <select name="class_id" id="class_id" class="form-select" style="min-width:200px;">
                    <option value="">Select Class</option>
                    <?php $__currentLoopData = $classes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $class): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <option value="<?php echo e($class->id); ?>" <?php if(request('class_id') == $class->id): ?> selected <?php endif; ?>><?php echo e($class->name); ?></option>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </select>
                <label for="class_id">Class</label>
            </div>
        </div>
        <div>
            <div class="form-floating">
                <input type="date" name="date" id="date" value="<?php echo e($selectedDate); ?>" class="form-control" style="min-width:150px;">
                <label for="date">Date</label>
            </div>
        </div>
        <div>
            <div class="form-floating">
                <select name="period" id="period" class="form-select" style="min-width:120px;">
                    <option value="">Select Period</option>
                    <option value="AM" <?php if(request('period') == 'AM'): ?> selected <?php endif; ?>>AM</option>
                    <option value="PM" <?php if(request('period') == 'PM'): ?> selected <?php endif; ?>>PM</option>
                    <?php for($i=1; $i<=8; $i++): ?>
                        <option value="Period <?php echo e($i); ?>" <?php if(request('period') == 'Period '.$i): ?> selected <?php endif; ?>>Period <?php echo e($i); ?></option>
                    <?php endfor; ?>
                </select>
                <label for="period">Period</label>
            </div>
        </div>
        <button type="submit" class="btn btn-primary btn-modern rounded-pill px-4"><i class="bi bi-search"></i> View</button>
    </form>
    <?php if(request('attendance_type', 'student') == 'student' && $selectedClass): ?>
        <!-- Modern Attendance Form -->
        <form id="attendance-form" method="POST" action="<?php echo e(route('academic.attendance.mark')); ?>" class="card-modern p-4 mb-4" novalidate>
            <?php echo csrf_field(); ?>
            <input type="hidden" name="class_id" value="<?php echo e($selectedClass->id); ?>">
            <input type="hidden" name="date" value="<?php echo e($selectedDate); ?>">
            <input type="hidden" name="period" value="<?php echo e(request('period')); ?>">
            <div class="table-responsive">
                <table class="table table-modern align-middle">
                    <thead>
                        <tr>
                            <th>Student</th>
                            <th>Status</th>
                            <th>Remarks</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $__currentLoopData = $selectedClass->students; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $student): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <tr>
                            <td><span class="fw-semibold"><?php echo e($student->full_name ?? $student->name); ?></span></td>
                            <td>
                                <div class="form-floating">
                                    <select name="attendance[<?php echo e($student->id); ?>][status]" class="form-select status-badge" required>
                                        <option value="present" <?php if(optional($attendanceRecords[$student->id] ?? null)->status == 'present'): echo 'selected'; endif; ?>>Present</option>
                                        <option value="absent" <?php if(optional($attendanceRecords[$student->id] ?? null)->status == 'absent'): echo 'selected'; endif; ?>>Absent</option>
                                        <option value="late" <?php if(optional($attendanceRecords[$student->id] ?? null)->status == 'late'): echo 'selected'; endif; ?>>Late</option>
                                        <option value="excused" <?php if(optional($attendanceRecords[$student->id] ?? null)->status == 'excused'): echo 'selected'; endif; ?>>Excused</option>
                                        <option value="sick" <?php if(optional($attendanceRecords[$student->id] ?? null)->status == 'sick'): echo 'selected'; endif; ?>>Sick</option>
                                        <option value="on_leave" <?php if(optional($attendanceRecords[$student->id] ?? null)->status == 'on_leave'): echo 'selected'; endif; ?>>On Leave</option>
                                        <option value="suspended" <?php if(optional($attendanceRecords[$student->id] ?? null)->status == 'suspended'): echo 'selected'; endif; ?>>Suspended</option>
                                    </select>
                                    <label>Status</label>
                                </div>
                                <input type="hidden" name="attendance[<?php echo e($student->id); ?>][student_id]" value="<?php echo e($student->id); ?>">
                            </td>
                            <td>
                                <div class="form-floating">
                                    <input type="text" name="attendance[<?php echo e($student->id); ?>][remarks]" value="<?php echo e(optional($attendanceRecords[$student->id] ?? null)->remarks); ?>" class="form-control" placeholder="Remarks">
                                    <label>Remarks</label>
                                </div>
                            </td>
                        </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </tbody>
                </table>
            </div>
            <div class="d-flex justify-content-end gap-2 mt-3">
                <button type="button" class="btn btn-outline-success btn-modern" onclick="markAll('present')">
                    <i class="bi bi-check-circle"></i> Mark All Present
                </button>
                <button type="button" class="btn btn-outline-danger btn-modern" onclick="markAll('absent')">
                    <i class="bi bi-x-circle"></i> Mark All Absent
                </button>
                <button type="submit" class="btn btn-primary btn-modern px-5">
                    <span class="spinner-border spinner-border-sm d-none" role="status" aria-hidden="true"></span>
                    <i class="bi bi-save"></i> Save Attendance
                </button>
            </div>
        </form>
        <!-- Modern Bulk Import Form -->
        <form method="POST" action="<?php echo e(route('academic.attendance.bulk-import')); ?>" enctype="multipart/form-data" class="card-modern p-4 mb-4">
            <?php echo csrf_field(); ?>
            <input type="hidden" name="class_id" value="<?php echo e($selectedClass->id); ?>">
            <input type="hidden" name="date" value="<?php echo e($selectedDate); ?>">
            <div class="mb-2">
                <label for="attendance-import-file" class="form-label">Bulk Import (Excel/CSV)</label>
                <div class="input-group">
                    <span class="input-group-text"><i class="bi bi-upload"></i></span>
                    <input type="file" name="file" id="attendance-import-file" accept=".csv,.xlsx,.xls" class="form-control">
                </div>
            </div>
            <button type="submit" class="btn btn-primary btn-modern px-4"><i class="bi bi-upload"></i> Import Attendance</button>
        </form>
    <?php elseif(request('attendance_type') == 'staff' && $staffList && count($staffList)): ?>
        <!-- Modern Staff Attendance Form (same structure as above, with staff fields) -->
        <form id="staff-attendance-form" method="POST" action="<?php echo e(route('academic.attendance.markStaff')); ?>" class="card-modern p-4 mb-4" novalidate>
            <?php echo csrf_field(); ?>
            <input type="hidden" name="date" value="<?php echo e($selectedDate); ?>">
            <input type="hidden" name="period" value="<?php echo e(request('period')); ?>">
            <div class="table-responsive">
                <table class="table table-modern align-middle">
                    <thead>
                        <tr>
                            <th>Staff</th>
                            <th>Status</th>
                            <th>Remarks</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $__currentLoopData = $staffList; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $staff): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <tr>
                            <td><span class="fw-semibold"><?php echo e($staff->first_name); ?> <?php echo e($staff->last_name); ?></span></td>
                            <td>
                                <div class="form-floating">
                                    <select name="attendance[<?php echo e($staff->id); ?>][status]" class="form-select status-badge" required>
                                        <option value="present">Present</option>
                                        <option value="absent">Absent</option>
                                        <option value="late">Late</option>
                                        <option value="excused">Excused</option>
                                        <option value="sick">Sick</option>
                                        <option value="on_leave">On Leave</option>
                                        <option value="suspended">Suspended</option>
                                    </select>
                                    <label>Status</label>
                                </div>
                                <input type="hidden" name="attendance[<?php echo e($staff->id); ?>][staff_id]" value="<?php echo e($staff->id); ?>">
                            </td>
                            <td>
                                <div class="form-floating">
                                    <input type="text" name="attendance[<?php echo e($staff->id); ?>][remarks]" class="form-control" placeholder="Remarks">
                                    <label>Remarks</label>
                                </div>
                            </td>
                        </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </tbody>
                </table>
            </div>
            <div class="d-flex justify-content-end gap-2 mt-3">
                <button type="button" class="btn btn-outline-success btn-modern" onclick="markAllStaff('present')">
                    <i class="bi bi-check-circle"></i> Mark All Present
                </button>
                <button type="button" class="btn btn-outline-danger btn-modern" onclick="markAllStaff('absent')">
                    <i class="bi bi-x-circle"></i> Mark All Absent
                </button>
                <button type="submit" class="btn btn-primary btn-modern px-5">
                    <span class="spinner-border spinner-border-sm d-none" role="status" aria-hidden="true"></span>
                    <i class="bi bi-save"></i> Save Staff Attendance
                </button>
            </div>
        </form>
    <?php elseif(request('attendance_type') == 'staff'): ?>
        <div class="alert alert-warning mt-4">No staff found for this school.</div>
    <?php elseif(request('class_id')): ?>
        <div class="alert alert-warning mt-4">No students found for this class.</div>
    <?php endif; ?>
    <!-- Modern Absence Excuse Request Form -->
    <div class="card-modern p-4 mt-5">
        <h3 class="mb-3 attendance-accent"><i class="bi bi-envelope-exclamation"></i> Absence Excuse Request</h3>
        <form method="POST" action="<?php echo e(route('academic.attendance.excuse.submit')); ?>" enctype="multipart/form-data" class="row g-3" novalidate>
            <?php echo csrf_field(); ?>
            <div class="col-md-4">
                <div class="form-floating">
                    <input type="date" name="date" id="excuse_date" class="form-control" required>
                    <label for="excuse_date">Date</label>
                </div>
            </div>
            <div class="col-md-4">
                <div class="form-floating">
                    <select name="period" id="excuse_period" class="form-select" required>
                        <option value="">Select Period</option>
                        <option value="AM">AM</option>
                        <option value="PM">PM</option>
                        <?php for($i=1; $i<=8; $i++): ?>
                            <option value="Period <?php echo e($i); ?>">Period <?php echo e($i); ?></option>
                        <?php endfor; ?>
                    </select>
                    <label for="excuse_period">Period</label>
                </div>
            </div>
            <div class="col-md-4">
                <label for="excuse_document" class="form-label">Document (optional)</label>
                <div class="input-group">
                    <span class="input-group-text"><i class="bi bi-upload"></i></span>
                    <input type="file" name="document" id="excuse_document" class="form-control" accept=".pdf,.jpg,.jpeg,.png,.doc,.docx">
                </div>
            </div>
            <div class="col-12">
                <div class="form-floating">
                    <textarea name="reason" id="excuse_reason" rows="2" class="form-control" required></textarea>
                    <label for="excuse_reason">Reason</label>
                </div>
            </div>
            <div class="col-12 d-flex justify-content-end">
                <button type="submit" class="btn btn-primary btn-modern px-4">
                    <i class="bi bi-send"></i> Submit Excuse
                </button>
            </div>
        </form>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('styles'); ?>
<style>
body { background: #0a1931; color: #eaf6fb; }
.attendance-header-gradient {
    background: linear-gradient(90deg, #11224d 0%, #1565c0 100%);
}
.attendance-accent {
    color: #1ea7ff;
    letter-spacing: 1px;
}
.attendance-card {
    box-shadow: 0 8px 32px 0 rgba(30,167,255,0.10), 0 1.5px 6px 0 rgba(21,101,192,0.10);
}
.table-dark th, .table-dark td { color: #eaf6fb; }
.btn-primary { background: #1ea7ff; border: none; transition: background 0.2s; }
.btn-primary:hover, .btn-primary:focus { background: #1565c0; color: #fff; }
.btn-success { background: #00bcd4; border: none; transition: background 0.2s; }
.btn-success:hover, .btn-success:focus { background: #0097a7; color: #fff; }
.btn-outline-success:hover, .btn-outline-success:focus { background: #00bcd4; color: #0a1931; }
.btn-outline-danger:hover, .btn-outline-danger:focus { background: #e53935; color: #fff; }
.form-select, .form-control { background: #11224d; color: #eaf6fb; border: 1px solid #22336b; transition: border-color 0.2s, box-shadow 0.2s; }
.form-select:focus, .form-control:focus { border-color: #1ea7ff; box-shadow: 0 0 0 0.2rem #1ea7ff40; }
.form-select:hover, .form-control:hover { border-color: #6ec1e4; }
.attendance-status-select option[value="present"] { color: #00bcd4; }
.attendance-status-select option[value="absent"] { color: #e53935; }
.attendance-status-select option[value="late"] { color: #ffb300; }
.attendance-status-select option[value="excused"] { color: #6ec1e4; }
.attendance-status-select option[value="sick"] { color: #8bc34a; }
.attendance-status-select option[value="on_leave"] { color: #3949ab; }
.attendance-status-select option[value="suspended"] { color: #9c27b0; }
</style>
<?php $__env->stopPush(); ?>

<?php $__env->startPush('scripts'); ?>
<script>
function markAll(status) {
    document.querySelectorAll('#attendance-form select[name$="[status]"]').forEach(function(select) {
        select.value = status;
    });
}
function markAllStaff(status) {
    document.querySelectorAll('#staff-attendance-form select[name$="[status]"]').forEach(function(select) {
        select.value = status;
    });
}
</script>
<?php $__env->stopPush(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\dunth\dunco school management system\duncoschool\Modules\Academic\resources\views\attendance\index.blade.php ENDPATH**/ ?>