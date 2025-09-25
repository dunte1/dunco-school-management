

<?php $__env->startSection('title', 'User Details'); ?>

<?php $__env->startSection('content'); ?>
<div class="container">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1>User Details</h1>
        <div>
            <a href="<?php echo e(route('core.users.edit', $user->id)); ?>" class="btn btn-warning">
                <i class="fas fa-edit"></i> Edit User
            </a>
            <a href="<?php echo e(route('core.users.index')); ?>" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> Back to Users
            </a>
        </div>
    </div>

    <div class="row">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-user"></i> User Information
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row mb-3">
                        <div class="col-md-3"><strong>Name:</strong></div>
                        <div class="col-md-9"><?php echo e($user->name); ?></div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-3"><strong>Email:</strong></div>
                        <div class="col-md-9"><?php echo e($user->email); ?></div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-3"><strong>School:</strong></div>
                        <div class="col-md-9"><?php echo e($user->school->name ?? 'N/A'); ?></div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-3"><strong>Email Verified:</strong></div>
                        <div class="col-md-9">
                            <?php if($user->email_verified_at): ?>
                                <span class="badge bg-success">Yes (<?php echo e($user->email_verified_at->format('F j, Y')); ?>)</span>
                            <?php else: ?>
                                <span class="badge bg-warning">No</span>
                            <?php endif; ?>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-3"><strong>Created:</strong></div>
                        <div class="col-md-9"><?php echo e($user->created_at->format('F j, Y \a\t g:i A')); ?></div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-3"><strong>Last Updated:</strong></div>
                        <div class="col-md-9"><?php echo e($user->updated_at->format('F j, Y \a\t g:i A')); ?></div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-3"><strong>Last Login:</strong></div>
                        <div class="col-md-9">
                            <?php if($user->last_login_at): ?>
                                <?php echo e($user->last_login_at->format('F j, Y \a\t g:i A')); ?>

                            <?php else: ?>
                                <span class="text-muted">Never</span>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-user-tag"></i> Roles (<?php echo e($user->roles->count()); ?>)
                    </h5>
                </div>
                <div class="card-body">
                    <?php if($user->roles->count() > 0): ?>
                        <div class="list-group list-group-flush">
                            <?php $__currentLoopData = $user->roles; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $role): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <div class="list-group-item d-flex justify-content-between align-items-center">
                                    <div>
                                        <strong><?php echo e($role->display_name ?? $role->name); ?></strong>
                                        <?php if($role->description): ?>
                                            <br><small class="text-muted"><?php echo e($role->description); ?></small>
                                        <?php endif; ?>
                                    </div>
                                    <?php if($role->is_system): ?>
                                        <span class="badge bg-primary">System</span>
                                    <?php endif; ?>
                                </div>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </div>
                    <?php else: ?>
                        <p class="text-muted">No roles assigned to this user.</p>
                    <?php endif; ?>
                </div>
            </div>

            <div class="card mt-3">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-key"></i> Permissions
                    </h5>
                </div>
                <div class="card-body">
                    <?php
                        $userPermissions = $user->roles->flatMap->permissions->unique('id');
                    ?>
                    <?php if($userPermissions->count() > 0): ?>
                        <div class="list-group list-group-flush">
                            <?php $__currentLoopData = $userPermissions->take(5); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $permission): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <div class="list-group-item">
                                    <strong><?php echo e($permission->display_name ?? $permission->name); ?></strong>
                                    <?php if($permission->module): ?>
                                        <br><small class="text-muted"><?php echo e($permission->module); ?></small>
                                    <?php endif; ?>
                                </div>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            <?php if($userPermissions->count() > 5): ?>
                                <div class="list-group-item text-center">
                                    <small class="text-muted">+<?php echo e($userPermissions->count() - 5); ?> more permissions</small>
                                </div>
                            <?php endif; ?>
                        </div>
                    <?php else: ?>
                        <p class="text-muted">No permissions assigned to this user.</p>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?> 
<?php echo $__env->make('core::layouts.master', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\dunth\dunco school management system\duncoschool\dist\Modules\Core\resources\views\users\show.blade.php ENDPATH**/ ?>