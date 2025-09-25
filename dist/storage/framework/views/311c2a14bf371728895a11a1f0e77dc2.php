<?php $__env->startSection('content'); ?>
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h1 class="h3 mb-0">Parent Dashboard</h1>
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
                                My Children</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800"><?php echo e($stats['total_children']); ?></div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-child fa-2x text-gray-300"></i>
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
                                Total Fees Due</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">$<?php echo e(number_format($stats['total_fees_due'], 2)); ?></div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-dollar-sign fa-2x text-gray-300"></i>
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
                                Recent Activities</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800"><?php echo e($stats['recent_activities']->count()); ?></div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-chart-line fa-2x text-gray-300"></i>
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
                                Messages</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">0</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-envelope fa-2x text-gray-300"></i>
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
                            <a href="#" class="btn btn-primary btn-block" onclick="alert('Children module coming soon!')">
                                <i class="fas fa-child me-2"></i>My Children
                            </a>
                        </div>
                        <div class="col-md-3 mb-3">
                            <a href="#" class="btn btn-success btn-block" onclick="alert('Finance module coming soon!')">
                                <i class="fas fa-dollar-sign me-2"></i>Pay Fees
                            </a>
                        </div>
                        <div class="col-md-3 mb-3">
                            <a href="#" class="btn btn-warning btn-block" onclick="alert('Communication module coming soon!')">
                                <i class="fas fa-comments me-2"></i>Messages
                            </a>
                        </div>
                        <div class="col-md-3 mb-3">
                            <a href="#" class="btn btn-info btn-block" onclick="alert('Profile module coming soon!')">
                                <i class="fas fa-user me-2"></i>My Profile
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
                    <h6 class="m-0 font-weight-bold text-primary">Recent Activities</h6>
                </div>
                <div class="card-body">
                    <?php if($stats['recent_activities'] && $stats['recent_activities']->count() > 0): ?>
                        <div class="table-responsive">
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th>Child</th>
                                        <th>Activity</th>
                                        <th>Date</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php $__currentLoopData = $stats['recent_activities']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $activity): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <tr>
                                        <td><?php echo e($activity->student->name ?? 'N/A'); ?></td>
                                        <td><?php echo e($activity->description ?? 'N/A'); ?></td>
                                        <td><?php echo e($activity->created_at ? \Carbon\Carbon::parse($activity->created_at)->format('M d, Y') : 'N/A'); ?></td>
                                        <td>
                                            <span class="badge bg-success">Completed</span>
                                        </td>
                                    </tr>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </tbody>
                            </table>
                        </div>
                    <?php else: ?>
                        <p class="text-muted">No recent activities found.</p>
                        <p class="text-muted">Your children's recent activities will appear here.</p>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="card shadow">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Children Information</h6>
                </div>
                <div class="card-body">
                    <?php if($children && $children->count() > 0): ?>
                        <?php $__currentLoopData = $children; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $child): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <div class="mb-3 p-3 border rounded">
                            <h6 class="font-weight-bold"><?php echo e($child->name ?? 'N/A'); ?></h6>
                            <p class="mb-1"><strong>Class:</strong> <?php echo e($child->currentClass->name ?? 'Not Assigned'); ?></p>
                            <p class="mb-1"><strong>Student ID:</strong> <?php echo e($child->student_id ?? 'N/A'); ?></p>
                            <p class="mb-0"><strong>Status:</strong> 
                                <span class="badge bg-success">Active</span>
                            </p>
                        </div>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    <?php else: ?>
                        <p class="text-muted">No children found.</p>
                        <p class="text-muted">Please contact administrator to link your children.</p>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\dunth\dunco school management system\duncoschool\dist\resources\views\dashboard\parent.blade.php ENDPATH**/ ?>