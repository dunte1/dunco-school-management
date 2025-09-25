<?php if (isset($component)) { $__componentOriginalad79281a5cb24e3b5a0a8a09fb4c0410 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalad79281a5cb24e3b5a0a8a09fb4c0410 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'core::components.layouts.master','data' => []] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('core::layouts.master'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?>
    <div class="container">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1>Role Details</h1>
            <div>
                <a href="<?php echo e(route('core.roles.edit', $role->id)); ?>" class="btn btn-warning">
                    <i class="fas fa-edit"></i> Edit Role
                </a>
                <a href="<?php echo e(route('core.roles.index')); ?>" class="btn btn-secondary">
                    <i class="fas fa-arrow-left"></i> Back to Roles
                </a>
            </div>
        </div>
        <div class="row">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title mb-0">Role Information</h5>
                    </div>
                    <div class="card-body">
                        <div class="row mb-3">
                            <div class="col-md-3"><strong>Name:</strong></div>
                            <div class="col-md-9"><?php echo e($role->name); ?></div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-md-3"><strong>Display Name:</strong></div>
                            <div class="col-md-9"><?php echo e($role->display_name); ?></div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-md-3"><strong>Description:</strong></div>
                            <div class="col-md-9"><?php echo e($role->description ?? 'No description'); ?></div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-md-3"><strong>Created:</strong></div>
                            <div class="col-md-9"><?php echo e($role->created_at ? $role->created_at->format('M d, Y H:i') : 'Unknown'); ?></div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-md-3"><strong>Last Updated:</strong></div>
                            <div class="col-md-9"><?php echo e($role->updated_at ? $role->updated_at->format('M d, Y H:i') : 'Unknown'); ?></div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title mb-0">Statistics</h5>
                    </div>
                    <div class="card-body">
                        <div class="row text-center">
                            <div class="col-6">
                                <h4 class="text-primary"><?php echo e($role->users ? $role->users->count() : 0); ?></h4>
                                <small class="text-muted">Users</small>
                            </div>
                            <div class="col-6">
                                <h4 class="text-success"><?php echo e($role->permissions ? $role->permissions->count() : 0); ?></h4>
                                <small class="text-muted">Permissions</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <?php if($role->permissions && $role->permissions->count() > 0): ?>
        <div class="row mt-4">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title mb-0">Permissions (<?php echo e($role->permissions->count()); ?>)</h5>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th>Name</th>
                                        <th>Display Name</th>
                                        <th>Description</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php $__currentLoopData = $role->permissions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $permission): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <tr>
                                        <td><?php echo e($permission->name); ?></td>
                                        <td><?php echo e($permission->display_name ?? $permission->name); ?></td>
                                        <td><?php echo e($permission->description ?? 'No description'); ?></td>
                                    </tr>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <?php endif; ?>
    </div>
 <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalad79281a5cb24e3b5a0a8a09fb4c0410)): ?>
<?php $attributes = $__attributesOriginalad79281a5cb24e3b5a0a8a09fb4c0410; ?>
<?php unset($__attributesOriginalad79281a5cb24e3b5a0a8a09fb4c0410); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalad79281a5cb24e3b5a0a8a09fb4c0410)): ?>
<?php $component = $__componentOriginalad79281a5cb24e3b5a0a8a09fb4c0410; ?>
<?php unset($__componentOriginalad79281a5cb24e3b5a0a8a09fb4c0410); ?>
<?php endif; ?> <?php /**PATH C:\Users\dunth\dunco school management system\duncoschool\dist\Modules\Core\resources\views\roles\show.blade.php ENDPATH**/ ?>