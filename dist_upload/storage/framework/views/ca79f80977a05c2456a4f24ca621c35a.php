

<?php $__env->startSection('content'); ?>
    <div class="container">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1>School Details</h1>
            <div>
                <a href="<?php echo e(route('core.schools.edit', $school->id)); ?>" class="btn btn-warning">
                    <i class="fas fa-edit"></i> Edit School
                </a>
                <a href="<?php echo e(route('core.schools.index')); ?>" class="btn btn-secondary">
                    <i class="fas fa-arrow-left"></i> Back to Schools
                </a>
            </div>
        </div>

        <div class="row">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title mb-0">
                            <i class="fas fa-school"></i> School Information
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="row mb-3">
                            <div class="col-md-3"><strong>Name:</strong></div>
                            <div class="col-md-9"><?php echo e($school->name); ?></div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-md-3"><strong>Code:</strong></div>
                            <div class="col-md-9"><?php echo e($school->code); ?></div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-md-3"><strong>Motto:</strong></div>
                            <div class="col-md-9"><?php echo e($school->motto ?? 'No motto set'); ?></div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-md-3"><strong>Domain:</strong></div>
                            <div class="col-md-9"><?php echo e($school->domain); ?></div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-md-3"><strong>Theme:</strong></div>
                            <div class="col-md-9">
                                <span class="badge bg-<?php echo e($school->theme); ?>"><?php echo e(ucfirst($school->theme)); ?></span>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-md-3"><strong>Status:</strong></div>
                            <div class="col-md-9">
                                <?php if($school->is_active): ?>
                                    <span class="badge bg-success">Active</span>
                                <?php else: ?>
                                    <span class="badge bg-danger">Inactive</span>
                                <?php endif; ?>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-md-3"><strong>Created:</strong></div>
                            <div class="col-md-9"><?php echo e($school->created_at->format('F j, Y \a\t g:i A')); ?></div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-md-3"><strong>Last Updated:</strong></div>
                            <div class="col-md-9"><?php echo e($school->updated_at->format('F j, Y \a\t g:i A')); ?></div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title mb-0">
                            <i class="fas fa-image"></i> School Logo
                        </h5>
                    </div>
                    <div class="card-body text-center">
                        <?php if($school->logo): ?>
                            <img src="<?php echo e(asset('storage/' . $school->logo)); ?>" alt="School Logo" class="img-fluid rounded" style="max-height: 200px;">
                        <?php else: ?>
                            <div class="bg-light rounded p-4">
                                <i class="fas fa-school fa-3x text-muted"></i>
                                <p class="text-muted mt-2">No logo uploaded</p>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>

                <div class="card mt-3">
                    <div class="card-header">
                        <h5 class="card-title mb-0">
                            <i class="fas fa-chart-bar"></i> Statistics
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="row text-center">
                            <div class="col-6">
                                <h4 class="text-primary"><?php echo e($school->users->count()); ?></h4>
                                <small class="text-muted">Users</small>
                            </div>
                            <div class="col-6">
                                <h4 class="text-success"><?php echo e($school->auditLogs->count()); ?></h4>
                                <small class="text-muted">Audit Logs</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <?php if($school->users->count() > 0): ?>
        <div class="row mt-4">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title mb-0">
                            <i class="fas fa-users"></i> School Users (<?php echo e($school->users->count()); ?>)
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th>Name</th>
                                        <th>Email</th>
                                        <th>Roles</th>
                                        <th>Created</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php $__currentLoopData = $school->users->take(10); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $user): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <tr>
                                        <td><?php echo e($user->name); ?></td>
                                        <td><?php echo e($user->email); ?></td>
                                        <td>
                                            <?php $__currentLoopData = $user->roles; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $role): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                <span class="badge bg-secondary"><?php echo e($role->display_name ?? $role->name); ?></span>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                        </td>
                                        <td><?php echo e($user->created_at->format('M j, Y')); ?></td>
                                    </tr>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </tbody>
                            </table>
                        </div>
                        <?php if($school->users->count() > 10): ?>
                            <div class="text-center mt-3">
                                <small class="text-muted">Showing first 10 users of <?php echo e($school->users->count()); ?> total</small>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
        <?php endif; ?>
    </div>
<?php $__env->stopSection(); ?> 
<?php echo $__env->make('core::layouts.master', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\dunth\dunco school management system\duncoschool\Modules\Core\resources\views\schools\show.blade.php ENDPATH**/ ?>