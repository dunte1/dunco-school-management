

<?php $__env->startSection('content'); ?>
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="page-title-box">
                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="<?php echo e(route('dashboard')); ?>">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="<?php echo e(route('transport.vehicles.index')); ?>">Vehicles</a></li>
                        <li class="breadcrumb-item active">Vehicle Details</li>
                    </ol>
                </div>
                <h4 class="page-title">Vehicle Details</h4>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-8">
            <div class="card">
                <div class="card-header">
                    <h4 class="header-title">Vehicle Information</h4>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <table class="table table-borderless">
                                <tr>
                                    <td><strong>Vehicle Number:</strong></td>
                                    <td><?php echo e($vehicle->vehicle_number); ?></td>
                                </tr>
                                <tr>
                                    <td><strong>Registration Number:</strong></td>
                                    <td><?php echo e($vehicle->registration_number); ?></td>
                                </tr>
                                <tr>
                                    <td><strong>Vehicle Type:</strong></td>
                                    <td><span class="badge bg-primary"><?php echo e(ucfirst($vehicle->vehicle_type)); ?></span></td>
                                </tr>
                                <tr>
                                    <td><strong>Brand:</strong></td>
                                    <td><?php echo e($vehicle->brand); ?></td>
                                </tr>
                                <tr>
                                    <td><strong>Model:</strong></td>
                                    <td><?php echo e($vehicle->model); ?></td>
                                </tr>
                                <tr>
                                    <td><strong>Year:</strong></td>
                                    <td><?php echo e($vehicle->year); ?></td>
                                </tr>
                                <tr>
                                    <td><strong>Capacity:</strong></td>
                                    <td><?php echo e($vehicle->capacity); ?> passengers</td>
                                </tr>
                                <tr>
                                    <td><strong>Fuel Type:</strong></td>
                                    <td><span class="badge bg-info"><?php echo e(ucfirst($vehicle->fuel_type)); ?></span></td>
                                </tr>
                                <tr>
                                    <td><strong>Mileage:</strong></td>
                                    <td><?php echo e(number_format($vehicle->mileage)); ?> km</td>
                                </tr>
                                <tr>
                                    <td><strong>Status:</strong></td>
                                    <td>
                                        <?php if($vehicle->status == 'active'): ?>
                                            <span class="badge bg-success">Active</span>
                                        <?php elseif($vehicle->status == 'maintenance'): ?>
                                            <span class="badge bg-warning">Maintenance</span>
                                        <?php else: ?>
                                            <span class="badge bg-danger">Inactive</span>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                            </table>
                        </div>
                        <div class="col-md-6">
                            <table class="table table-borderless">
                                <tr>
                                    <td><strong>Insurance Expiry:</strong></td>
                                    <td>
                                        <?php if($vehicle->insurance_expiry->isPast()): ?>
                                            <span class="text-danger"><?php echo e($vehicle->insurance_expiry->format('d M Y')); ?> (Expired)</span>
                                        <?php elseif($vehicle->insurance_expiry->diffInDays(now()) <= 30): ?>
                                            <span class="text-warning"><?php echo e($vehicle->insurance_expiry->format('d M Y')); ?> (Expiring Soon)</span>
                                        <?php else: ?>
                                            <span class="text-success"><?php echo e($vehicle->insurance_expiry->format('d M Y')); ?></span>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                                <tr>
                                    <td><strong>Fitness Expiry:</strong></td>
                                    <td>
                                        <?php if($vehicle->fitness_expiry->isPast()): ?>
                                            <span class="text-danger"><?php echo e($vehicle->fitness_expiry->format('d M Y')); ?> (Expired)</span>
                                        <?php elseif($vehicle->fitness_expiry->diffInDays(now()) <= 30): ?>
                                            <span class="text-warning"><?php echo e($vehicle->fitness_expiry->format('d M Y')); ?> (Expiring Soon)</span>
                                        <?php else: ?>
                                            <span class="text-success"><?php echo e($vehicle->fitness_expiry->format('d M Y')); ?></span>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                                <tr>
                                    <td><strong>Permit Expiry:</strong></td>
                                    <td>
                                        <?php if($vehicle->permit_expiry->isPast()): ?>
                                            <span class="text-danger"><?php echo e($vehicle->permit_expiry->format('d M Y')); ?> (Expired)</span>
                                        <?php elseif($vehicle->permit_expiry->diffInDays(now()) <= 30): ?>
                                            <span class="text-warning"><?php echo e($vehicle->permit_expiry->format('d M Y')); ?> (Expiring Soon)</span>
                                        <?php else: ?>
                                            <span class="text-success"><?php echo e($vehicle->permit_expiry->format('d M Y')); ?></span>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                                <tr>
                                    <td><strong>Assigned Driver:</strong></td>
                                    <td>
                                        <?php if($vehicle->driver): ?>
                                            <a href="<?php echo e(route('transport.drivers.show', $vehicle->driver)); ?>"><?php echo e($vehicle->driver->name); ?></a>
                                        <?php else: ?>
                                            <span class="text-muted">No driver assigned</span>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                                <tr>
                                    <td><strong>School:</strong></td>
                                    <td><?php echo e($vehicle->school->name ?? 'N/A'); ?></td>
                                </tr>
                                <tr>
                                    <td><strong>Created:</strong></td>
                                    <td><?php echo e($vehicle->created_at->format('d M Y H:i')); ?></td>
                                </tr>
                                <tr>
                                    <td><strong>Last Updated:</strong></td>
                                    <td><?php echo e($vehicle->updated_at->format('d M Y H:i')); ?></td>
                                </tr>
                            </table>
                        </div>
                    </div>

                    <?php if($vehicle->description): ?>
                    <div class="row mt-3">
                        <div class="col-12">
                            <h5>Description:</h5>
                            <p><?php echo e($vehicle->description); ?></p>
                        </div>
                    </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="card">
                <div class="card-header">
                    <h4 class="header-title">Quick Actions</h4>
                </div>
                <div class="card-body">
                    <div class="d-grid gap-2">
                        <a href="<?php echo e(route('transport.vehicles.edit', $vehicle)); ?>" class="btn btn-primary">
                            <i class="fas fa-edit"></i> Edit Vehicle
                        </a>
                        <a href="<?php echo e(route('transport.trips.create')); ?>?vehicle_id=<?php echo e($vehicle->id); ?>" class="btn btn-success">
                            <i class="fas fa-plus"></i> Schedule Trip
                        </a>
                        <a href="<?php echo e(route('transport.vehicles.index')); ?>" class="btn btn-secondary">
                            <i class="fas fa-arrow-left"></i> Back to Vehicles
                        </a>
                    </div>
                </div>
            </div>

            <?php if($vehicle->routes->count() > 0): ?>
            <div class="card mt-3">
                <div class="card-header">
                    <h4 class="header-title">Assigned Routes</h4>
                </div>
                <div class="card-body">
                    <ul class="list-group list-group-flush">
                        <?php $__currentLoopData = $vehicle->routes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $route): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            <a href="<?php echo e(route('transport.routes.show', $route)); ?>"><?php echo e($route->name); ?></a>
                            <span class="badge bg-primary rounded-pill"><?php echo e($route->stops->count()); ?> stops</span>
                        </li>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </ul>
                </div>
            </div>
            <?php endif; ?>
        </div>
    </div>

    <?php if($vehicle->trips->count() > 0): ?>
    <div class="row mt-3">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="header-title">Recent Trips</h4>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-centered table-hover mb-0">
                            <thead>
                                <tr>
                                    <th>Trip Date</th>
                                    <th>Route</th>
                                    <th>Driver</th>
                                    <th>Status</th>
                                    <th>Passengers</th>
                                    <th>Distance</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $__currentLoopData = $vehicle->trips->take(10); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $trip): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <tr>
                                    <td><?php echo e($trip->trip_date->format('d M Y')); ?></td>
                                    <td><?php echo e($trip->route->name ?? 'N/A'); ?></td>
                                    <td><?php echo e($trip->driver->name ?? 'N/A'); ?></td>
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
                                    <td><?php echo e($trip->passenger_count ?? 0); ?></td>
                                    <td><?php echo e($trip->distance_covered ?? 0); ?> km</td>
                                    <td>
                                        <a href="<?php echo e(route('transport.trips.show', $trip)); ?>" class="btn btn-sm btn-outline-primary">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                    </td>
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
<?php $__env->stopSection(); ?> 
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\dunth\dunco school management system\duncoschool\dist\Modules\Transport\resources\views\vehicles\show.blade.php ENDPATH**/ ?>