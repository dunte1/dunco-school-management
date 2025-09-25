

<?php $__env->startSection('content'); ?>
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="page-title-box">
                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="<?php echo e(route('dashboard')); ?>">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="<?php echo e(route('transport.index')); ?>">Transport</a></li>
                        <li class="breadcrumb-item active">Vehicles</li>
                    </ol>
                </div>
                <h4 class="page-title">Vehicles Management</h4>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <h5 class="card-title">All Vehicles</h5>
                        </div>
                        <div class="col-md-6 text-end">
                            <a href="<?php echo e(route('transport.vehicles.create')); ?>" class="btn btn-primary">
                                <i class="fas fa-plus me-2"></i>Add Vehicle
                            </a>
                        </div>
                    </div>

                    <!-- Search and Filters -->
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <form method="GET" action="<?php echo e(route('transport.vehicles.index')); ?>">
                                <div class="input-group">
                                    <input type="text" class="form-control" name="search" 
                                           placeholder="Search vehicles..." value="<?php echo e(request('search')); ?>">
                                    <button class="btn btn-outline-secondary" type="submit">
                                        <i class="fas fa-search"></i>
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>

                    <div class="table-responsive">
                        <table class="table table-centered table-hover mb-0">
                            <thead>
                                <tr>
                                    <th>Vehicle Number</th>
                                    <th>Type</th>
                                    <th>Brand/Model</th>
                                    <th>Driver</th>
                                    <th>Status</th>
                                    <th>Capacity</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $__empty_1 = true; $__currentLoopData = $vehicles; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $vehicle): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                <tr>
                                    <td>
                                        <strong><?php echo e($vehicle->vehicle_number); ?></strong>
                                        <br>
                                        <small class="text-muted"><?php echo e($vehicle->registration_number); ?></small>
                                    </td>
                                    <td>
                                        <span class="badge bg-info"><?php echo e(ucfirst($vehicle->vehicle_type)); ?></span>
                                    </td>
                                    <td><?php echo e($vehicle->brand); ?> <?php echo e($vehicle->model); ?> (<?php echo e($vehicle->year); ?>)</td>
                                    <td><?php echo e($vehicle->driver->name ?? 'Unassigned'); ?></td>
                                    <td>
                                        <span class="badge bg-<?php echo e($vehicle->status === 'active' ? 'success' : ($vehicle->status === 'maintenance' ? 'warning' : 'danger')); ?>">
                                            <?php echo e(ucfirst($vehicle->status)); ?>

                                        </span>
                                    </td>
                                    <td><?php echo e($vehicle->capacity); ?> seats</td>
                                    <td>
                                        <div class="btn-group" role="group">
                                            <a href="<?php echo e(route('transport.vehicles.show', $vehicle)); ?>" 
                                               class="btn btn-sm btn-outline-info">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            <a href="<?php echo e(route('transport.vehicles.edit', $vehicle)); ?>" 
                                               class="btn btn-sm btn-outline-primary">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <form method="POST" action="<?php echo e(route('transport.vehicles.destroy', $vehicle)); ?>" 
                                                  style="display: inline;" onsubmit="return confirm('Are you sure?')">
                                                <?php echo csrf_field(); ?>
                                                <?php echo method_field('DELETE'); ?>
                                                <button type="submit" class="btn btn-sm btn-outline-danger">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                <tr>
                                    <td colspan="7" class="text-center">No vehicles found.</td>
                                </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    <div class="row mt-3">
                        <div class="col-12">
                            <?php echo e($vehicles->links()); ?>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?> 
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\dunth\dunco school management system\duncoschool\dist\Modules\Transport\resources\views\vehicles\index.blade.php ENDPATH**/ ?>