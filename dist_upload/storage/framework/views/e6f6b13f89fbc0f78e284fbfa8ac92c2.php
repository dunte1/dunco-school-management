

<?php $__env->startSection('content'); ?>
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="page-title-box">
                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="<?php echo e(route('dashboard')); ?>">Dashboard</a></li>
                        <li class="breadcrumb-item active">Routes</li>
                    </ol>
                </div>
                <h4 class="page-title">Transport Routes</h4>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h4 class="header-title">All Routes</h4>
                    <a href="<?php echo e(route('transport.routes.create')); ?>" class="btn btn-primary">
                        <i class="fas fa-plus"></i> Add New Route
                    </a>
                </div>
                <div class="card-body">
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <form action="<?php echo e(route('transport.routes.index')); ?>" method="GET" class="d-flex">
                                <input type="text" name="search" class="form-control me-2" 
                                       placeholder="Search routes..." 
                                       value="<?php echo e(request('search')); ?>">
                                <button type="submit" class="btn btn-outline-primary">
                                    <i class="fas fa-search"></i>
                                </button>
                            </form>
                        </div>
                    </div>

                    <div class="table-responsive">
                        <table class="table table-centered table-hover mb-0">
                            <thead>
                                <tr>
                                    <th>Route Name</th>
                                    <th>Start Point</th>
                                    <th>End Point</th>
                                    <th>Stops</th>
                                    <th>Distance</th>
                                    <th>Status</th>
                                    <th>Vehicles</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $__empty_1 = true; $__currentLoopData = $routes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $route): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                <tr>
                                    <td>
                                        <a href="<?php echo e(route('transport.routes.show', $route)); ?>" class="text-body fw-bold">
                                            <?php echo e($route->name); ?>

                                        </a>
                                    </td>
                                    <td><?php echo e($route->start_point); ?></td>
                                    <td><?php echo e($route->end_point); ?></td>
                                    <td>
                                        <span class="badge bg-info"><?php echo e($route->stops->count()); ?> stops</span>
                                    </td>
                                    <td><?php echo e($route->distance ?? 0); ?> km</td>
                                    <td>
                                        <?php if($route->status == 'active'): ?>
                                            <span class="badge bg-success">Active</span>
                                        <?php else: ?>
                                            <span class="badge bg-danger">Inactive</span>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <span class="badge bg-primary"><?php echo e($route->vehicles->count()); ?> vehicles</span>
                                    </td>
                                    <td>
                                        <div class="btn-group" role="group">
                                            <a href="<?php echo e(route('transport.routes.show', $route)); ?>" 
                                               class="btn btn-sm btn-outline-primary" title="View">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            <a href="<?php echo e(route('transport.routes.edit', $route)); ?>" 
                                               class="btn btn-sm btn-outline-secondary" title="Edit">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <form action="<?php echo e(route('transport.routes.destroy', $route)); ?>" 
                                                  method="POST" class="d-inline" 
                                                  onsubmit="return confirm('Are you sure you want to delete this route?')">
                                                <?php echo csrf_field(); ?>
                                                <?php echo method_field('DELETE'); ?>
                                                <button type="submit" class="btn btn-sm btn-outline-danger" title="Delete">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                <tr>
                                    <td colspan="8" class="text-center py-4">
                                        <div class="text-muted">
                                            <i class="fas fa-route fa-3x mb-3"></i>
                                            <h5>No routes found</h5>
                                            <p>Start by creating your first transport route.</p>
                                            <a href="<?php echo e(route('transport.routes.create')); ?>" class="btn btn-primary">
                                                <i class="fas fa-plus"></i> Create Route
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>

                    <?php if($routes->hasPages()): ?>
                    <div class="row mt-3">
                        <div class="col-12">
                            <?php echo e($routes->links()); ?>

                        </div>
                    </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?> 
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\dunth\dunco school management system\duncoschool\Modules\Transport\resources\views\routes\index.blade.php ENDPATH**/ ?>