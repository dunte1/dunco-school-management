

<?php $__env->startSection('content'); ?>
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="page-title-box">
                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="<?php echo e(route('dashboard')); ?>">Dashboard</a></li>
                        <li class="breadcrumb-item active">Trips</li>
                    </ol>
                </div>
                <h4 class="page-title">Transport Trips</h4>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h4 class="header-title">All Trips</h4>
                    <a href="<?php echo e(route('transport.trips.create')); ?>" class="btn btn-primary">
                        <i class="fas fa-plus"></i> Schedule New Trip
                    </a>
                </div>
                <div class="card-body">
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <form action="<?php echo e(route('transport.trips.index')); ?>" method="GET" class="d-flex">
                                <input type="text" name="search" class="form-control me-2" 
                                       placeholder="Search trips..." 
                                       value="<?php echo e(request('search')); ?>">
                                <button type="submit" class="btn btn-outline-primary">
                                    <i class="fas fa-search"></i>
                                </button>
                            </form>
                        </div>
                        <div class="col-md-6 text-end">
                            <div class="btn-group" role="group">
                                <a href="<?php echo e(route('transport.trips.index', ['status' => 'scheduled'])); ?>" 
                                   class="btn btn-outline-info <?php echo e(request('status') == 'scheduled' ? 'active' : ''); ?>">
                                    Scheduled
                                </a>
                                <a href="<?php echo e(route('transport.trips.index', ['status' => 'in_progress'])); ?>" 
                                   class="btn btn-outline-warning <?php echo e(request('status') == 'in_progress' ? 'active' : ''); ?>">
                                    In Progress
                                </a>
                                <a href="<?php echo e(route('transport.trips.index', ['status' => 'completed'])); ?>" 
                                   class="btn btn-outline-success <?php echo e(request('status') == 'completed' ? 'active' : ''); ?>">
                                    Completed
                                </a>
                            </div>
                        </div>
                    </div>

                    <div class="table-responsive">
                        <table class="table table-centered table-hover mb-0">
                            <thead>
                                <tr>
                                    <th>Trip Date</th>
                                    <th>Route</th>
                                    <th>Vehicle</th>
                                    <th>Driver</th>
                                    <th>Start Time</th>
                                    <th>End Time</th>
                                    <th>Status</th>
                                    <th>Passengers</th>
                                    <th>Distance</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $__empty_1 = true; $__currentLoopData = $trips; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $trip): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                <tr>
                                    <td>
                                        <span class="fw-bold"><?php echo e($trip->trip_date->format('d M Y')); ?></span>
                                    </td>
                                    <td>
                                        <?php if($trip->route): ?>
                                            <a href="<?php echo e(route('transport.routes.show', $trip->route)); ?>">
                                                <?php echo e($trip->route->name); ?>

                                            </a>
                                        <?php else: ?>
                                            <span class="text-muted">N/A</span>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <?php if($trip->vehicle): ?>
                                            <a href="<?php echo e(route('transport.vehicles.show', $trip->vehicle)); ?>">
                                                <?php echo e($trip->vehicle->vehicle_number); ?>

                                            </a>
                                        <?php else: ?>
                                            <span class="text-muted">N/A</span>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <?php if($trip->driver): ?>
                                            <a href="<?php echo e(route('transport.drivers.show', $trip->driver)); ?>">
                                                <?php echo e($trip->driver->name); ?>

                                            </a>
                                        <?php else: ?>
                                            <span class="text-muted">N/A</span>
                                        <?php endif; ?>
                                    </td>
                                    <td><?php echo e($trip->start_time ?? 'N/A'); ?></td>
                                    <td><?php echo e($trip->end_time ?? 'N/A'); ?></td>
                                    <td>
                                        <?php if($trip->status == 'scheduled'): ?>
                                            <span class="badge bg-info">Scheduled</span>
                                        <?php elseif($trip->status == 'in_progress'): ?>
                                            <span class="badge bg-warning">In Progress</span>
                                        <?php elseif($trip->status == 'completed'): ?>
                                            <span class="badge bg-success">Completed</span>
                                        <?php else: ?>
                                            <span class="badge bg-danger">Cancelled</span>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <span class="badge bg-primary"><?php echo e($trip->passenger_count ?? 0); ?></span>
                                    </td>
                                    <td><?php echo e($trip->distance_covered ?? 0); ?> km</td>
                                    <td>
                                        <div class="btn-group" role="group">
                                            <a href="<?php echo e(route('transport.trips.show', $trip)); ?>" 
                                               class="btn btn-sm btn-outline-primary" title="View">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            <a href="<?php echo e(route('transport.trips.edit', $trip)); ?>" 
                                               class="btn btn-sm btn-outline-secondary" title="Edit">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <form action="<?php echo e(route('transport.trips.destroy', $trip)); ?>" 
                                                  method="POST" class="d-inline" 
                                                  onsubmit="return confirm('Are you sure you want to delete this trip?')">
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
                                    <td colspan="10" class="text-center py-4">
                                        <div class="text-muted">
                                            <i class="fas fa-route fa-3x mb-3"></i>
                                            <h5>No trips found</h5>
                                            <p>Start by scheduling your first transport trip.</p>
                                            <a href="<?php echo e(route('transport.trips.create')); ?>" class="btn btn-primary">
                                                <i class="fas fa-plus"></i> Schedule Trip
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>

                    <?php if($trips->hasPages()): ?>
                    <div class="row mt-3">
                        <div class="col-12">
                            <?php echo e($trips->links()); ?>

                        </div>
                    </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?> 
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\dunth\dunco school management system\duncoschool\Modules\Transport\resources\views\trips\index.blade.php ENDPATH**/ ?>