<?php $__env->startSection('content'); ?>
    <div class="container">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1 class="mb-0"><i class="fas fa-key"></i> Permissions</h1>
            <a href="<?php echo e(route('core.permissions.create')); ?>" class="btn btn-primary">
                <i class="fas fa-plus"></i> Add Permission
            </a>
        </div>
        
        <div class="card">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover align-middle">
                        <thead class="table-light">
                            <tr>
                                <th>Name</th>
                                <th>Display Name</th>
                                <th>Description</th>
                                <th>Module</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $__currentLoopData = $permissions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $permission): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <tr>
                                <td><?php echo e($permission->name); ?></td>
                                <td><?php echo e($permission->display_name ?? $permission->name); ?></td>
                                <td><?php echo e($permission->description ?? 'No description'); ?></td>
                                <td><?php echo e($permission->module ?? 'Core'); ?></td>
                                <td>
                                    <a href="<?php echo e(route('core.permissions.show', $permission->id)); ?>" class="btn btn-sm btn-info" title="View"><i class="fas fa-eye"></i></a>
                                    <a href="<?php echo e(route('core.permissions.edit', $permission->id)); ?>" class="btn btn-sm btn-warning" title="Edit"><i class="fas fa-edit"></i></a>
                                </td>
                            </tr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </tbody>
                    </table>
                </div>
                
                <?php if($permissions->hasPages()): ?>
                    <div class="d-flex justify-content-between align-items-center mt-4">
                        <div class="text-muted">
                            Showing <?php echo e($permissions->firstItem()); ?> to <?php echo e($permissions->lastItem()); ?> of <?php echo e($permissions->total()); ?> results
                        </div>
                        <div class="d-flex gap-2">
                            <?php if($permissions->onFirstPage()): ?>
                                <span class="btn btn-outline-secondary disabled">Previous</span>
                            <?php else: ?>
                                <a href="<?php echo e($permissions->previousPageUrl()); ?>" class="btn btn-outline-primary">Previous</a>
                            <?php endif; ?>
                            
                            <?php if($permissions->hasMorePages()): ?>
                                <a href="<?php echo e($permissions->nextPageUrl()); ?>" class="btn btn-outline-primary">Next</a>
                            <?php else: ?>
                                <span class="btn btn-outline-secondary disabled">Next</span>
                            <?php endif; ?>
                        </div>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
<?php $__env->stopSection(); ?> 
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\dunth\dunco school management system\duncoschool\dist\Modules\Core\resources\views\permissions\index.blade.php ENDPATH**/ ?>