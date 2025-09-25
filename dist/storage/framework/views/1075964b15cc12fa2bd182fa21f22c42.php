<?php $__env->startSection('content'); ?>
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h1 class="h3 mb-0">Student Dashboard</h1>
                <div class="text-muted">Welcome back, <?php echo e(Auth::user()->name); ?>!</div>
            </div>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="row mb-4">
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                Current Class</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                <?php echo e($stats['current_class'] ? $stats['current_class']->name : 'Not Assigned'); ?>

                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-graduation-cap fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-success shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                Total Subjects</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800"><?php echo e($stats['total_subjects']); ?></div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-book fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-warning shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                                Attendance Rate</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800"><?php echo e($stats['attendance_rate']); ?>%</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-user-check fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-info shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                                Upcoming Exams</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800"><?php echo e($stats['upcoming_exams']->count()); ?></div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-file-alt fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card shadow">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Quick Actions</h6>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-3 mb-3">
                            <a href="#" class="btn btn-primary btn-block" onclick="alert('Academics module coming soon!')">
                                <i class="fas fa-book me-2"></i>My Academics
                            </a>
                        </div>
                        <div class="col-md-3 mb-3">
                            <a href="#" class="btn btn-success btn-block" onclick="alert('Schedule module coming soon!')">
                                <i class="fas fa-calendar me-2"></i>My Schedule
                            </a>
                        </div>
                        <div class="col-md-3 mb-3">
                            <a href="#" class="btn btn-warning btn-block" onclick="alert('Study Materials module coming soon!')">
                                <i class="fas fa-file-pdf me-2"></i>Study Materials
                            </a>
                        </div>
                        <div class="col-md-3 mb-3">
                            <a href="#" class="btn btn-info btn-block" onclick="alert('Assignments module coming soon!')">
                                <i class="fas fa-tasks me-2"></i>Assignments
                            </a>
                        </div>
                    </div>
                    <div class="row mt-3">
                        <div class="col-md-3 mb-3">
                            <a href="#" class="btn btn-secondary btn-block" onclick="alert('Finance module coming soon!')">
                                <i class="fas fa-dollar-sign me-2"></i>Finance
                            </a>
                        </div>
                        <div class="col-md-3 mb-3">
                            <a href="#" class="btn btn-dark btn-block" onclick="alert('Communication module coming soon!')">
                                <i class="fas fa-comments me-2"></i>Communication
                            </a>
                        </div>
                        <div class="col-md-3 mb-3">
                            <a href="#" class="btn btn-outline-primary btn-block" onclick="alert('Profile module coming soon!')">
                                <i class="fas fa-user me-2"></i>My Profile
                            </a>
                        </div>
                        <div class="col-md-3 mb-3">
                            <a href="#" class="btn btn-outline-success btn-block" onclick="alert('Results module coming soon!')">
                                <i class="fas fa-chart-line me-2"></i>My Results
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Activities -->
    <div class="row">
        <div class="col-lg-8">
            <div class="card shadow">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Upcoming Exams</h6>
                </div>
                <div class="card-body">
                    <?php if($stats['upcoming_exams'] && $stats['upcoming_exams']->count() > 0): ?>
                        <div class="table-responsive">
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th>Exam</th>
                                        <th>Subject</th>
                                        <th>Date</th>
                                        <th>Duration</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php $__currentLoopData = $stats['upcoming_exams']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $exam): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <tr>
                                        <td><?php echo e($exam->title ?? 'N/A'); ?></td>
                                        <td><?php echo e($exam->subject->name ?? 'N/A'); ?></td>
                                        <td><?php echo e($exam->start_date ? \Carbon\Carbon::parse($exam->start_date)->format('M d, Y') : 'N/A'); ?></td>
                                        <td><?php echo e($exam->duration ?? 'N/A'); ?> minutes</td>
                                    </tr>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </tbody>
                            </table>
                        </div>
                    <?php else: ?>
                        <p class="text-muted">No upcoming exams found.</p>
                        <p class="text-muted">Your upcoming exams will appear here.</p>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="card shadow">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Student Information</h6>
                </div>
                <div class="card-body">
                    <?php if($stats['current_class']): ?>
                        <p><strong>Current Class:</strong> <?php echo e($stats['current_class']->name ?? 'N/A'); ?></p>
                        <p><strong>Class Teacher:</strong> <?php echo e($stats['current_class']->teacher->name ?? 'N/A'); ?></p>
                        <p><strong>Academic Year:</strong> <?php echo e($stats['current_class']->academic_year ?? 'N/A'); ?></p>
                        <p><strong>Subjects:</strong> <?php echo e($stats['total_subjects']); ?></p>
                        <p><strong>Attendance:</strong> 
                            <span class="badge bg-<?php echo e($stats['attendance_rate'] >= 80 ? 'success' : ($stats['attendance_rate'] >= 60 ? 'warning' : 'danger')); ?>">
                                <?php echo e($stats['attendance_rate']); ?>%
                            </span>
                        </p>
                    <?php else: ?>
                        <p class="text-muted">Student profile not found.</p>
                        <p class="text-muted">Please contact administrator to set up your student profile.</p>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\dunth\dunco school management system\duncoschool\dist\resources\views\dashboard\student.blade.php ENDPATH**/ ?>