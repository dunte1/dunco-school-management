

<?php $__env->startSection('content'); ?>
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Core Module Dashboard</h3>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-3">
                            <div class="info-box">
                                <span class="info-box-icon bg-info"><i class="fas fa-school"></i></span>
                                <div class="info-box-content">
                                    <span class="info-box-text">Schools</span>
                                    <span class="info-box-number"><?php echo e(\Modules\Core\Models\School::count()); ?></span>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="info-box">
                                <span class="info-box-icon bg-success"><i class="fas fa-users"></i></span>
                                <div class="info-box-content">
                                    <span class="info-box-text">Users</span>
                                    <span class="info-box-number"><?php echo e(\App\Models\User::count()); ?></span>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="info-box">
                                <span class="info-box-icon bg-warning"><i class="fas fa-user-shield"></i></span>
                                <div class="info-box-content">
                                    <span class="info-box-text">Roles</span>
                                    <span class="info-box-number"><?php echo e(\App\Models\Role::count()); ?></span>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="info-box">
                                <span class="info-box-icon bg-danger"><i class="fas fa-key"></i></span>
                                <div class="info-box-content">
                                    <span class="info-box-text">Permissions</span>
                                    <span class="info-box-number"><?php echo e(\App\Models\Permission::count()); ?></span>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="row mt-4">
                        <div class="col-md-6">
                            <div class="card">
                                <div class="card-header">
                                    <h5 class="card-title">Quick Actions</h5>
                                </div>
                                <div class="card-body">
                                    <div class="list-group">
                                        <a href="<?php echo e(route('core.schools.index')); ?>" class="list-group-item list-group-item-action">
                                            <i class="fas fa-school me-2"></i> Manage Schools
                                        </a>
                                        <a href="<?php echo e(route('core.users.index')); ?>" class="list-group-item list-group-item-action">
                                            <i class="fas fa-users me-2"></i> Manage Users
                                        </a>
                                        <a href="<?php echo e(route('core.roles.index')); ?>" class="list-group-item list-group-item-action">
                                            <i class="fas fa-user-shield me-2"></i> Manage Roles
                                        </a>
                                        <a href="<?php echo e(route('core.permissions.index')); ?>" class="list-group-item list-group-item-action">
                                            <i class="fas fa-key me-2"></i> Manage Permissions
                                        </a>
                                        <a href="<?php echo e(route('core.audit_logs.index')); ?>" class="list-group-item list-group-item-action">
                                            <i class="fas fa-history me-2"></i> View Audit Logs
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="card">
                                <div class="card-header">
                                    <h5 class="card-title">System Information</h5>
                                </div>
                                <div class="card-body">
                                    <table class="table table-sm">
                                        <tr>
                                            <td><strong>Laravel Version:</strong></td>
                                            <td><?php echo e(app()->version()); ?></td>
                                        </tr>
                                        <tr>
                                            <td><strong>PHP Version:</strong></td>
                                            <td><?php echo e(phpversion()); ?></td>
                                        </tr>
                                        <tr>
                                            <td><strong>Environment:</strong></td>
                                            <td><?php echo e(config('app.env')); ?></td>
                                        </tr>
                                        <tr>
                                            <td><strong>Debug Mode:</strong></td>
                                            <td><?php echo e(config('app.debug') ? 'Enabled' : 'Disabled'); ?></td>
                                        </tr>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?> 
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\dunth\dunco school management system\duncoschool\Modules\Core\resources\views\dashboard.blade.php ENDPATH**/ ?>