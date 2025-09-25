

<?php $__env->startSection('content'); ?>
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="page-title-box">
                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="<?php echo e(route('dashboard')); ?>">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="<?php echo e(route('transport.routes.index')); ?>">Routes</a></li>
                        <li class="breadcrumb-item active">Route Details</li>
                    </ol>
                </div>
                <h4 class="page-title">Route Details</h4>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-8">
            <div class="card">
                <div class="card-header">
                    <h4 class="header-title">Route Information</h4>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <table class="table table-borderless">
                                <tr>
                                    <td><strong>Route Name:</strong></td>
                                    <td><?php echo e($route->name); ?></td>
                                </tr>
                                <tr>
                                    <td><strong>Start Point:</strong></td>
                                    <td><?php echo e($route->start_point); ?></td>
                                </tr>
                                <tr>
                                    <td><strong>End Point:</strong></td>
                                    <td><?php echo e($route->end_point); ?></td>
                                </tr>
                                <tr>
                                    <td><strong>Distance:</strong></td>
                                    <td><?php echo e($route->distance ?? 0); ?> km</td>
                                </tr>
                                <tr>
                                    <td><strong>Status:</strong></td>
                                    <td>
                                        <?php if($route->status == 'active'): ?>
                                            <span class="badge bg-success">Active</span>
                                        <?php else: ?>
                                            <span class="badge bg-danger">Inactive</span>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                                <tr>
                                    <td><strong>School:</strong></td>
                                    <td><?php echo e($route->school->name ?? 'N/A'); ?></td>
                                </tr>
                                <tr>
                                    <td><strong>Created:</strong></td>
                                    <td><?php echo e($route->created_at->format('d M Y H:i')); ?></td>
                                </tr>
                                <tr>
                                    <td><strong>Last Updated:</strong></td>
                                    <td><?php echo e($route->updated_at->format('d M Y H:i')); ?></td>
                                </tr>
                            </table>
                        </div>
                        <div class="col-md-6">
                            <?php if($route->description): ?>
                            <div class="mb-3">
                                <h5>Description:</h5>
                                <p><?php echo e($route->description); ?></p>
                            </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>

            <?php if($route->stops->count() > 0): ?>
            <div class="card mt-3">
                <div class="card-header">
                    <h4 class="header-title">Route Stops</h4>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-centered table-hover mb-0">
                            <thead>
                                <tr>
                                    <th>Stop Name</th>
                                    <th>Location</th>
                                    <th>Sequence</th>
                                    <th>Pickup Time</th>
                                    <th>Drop Time</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $__currentLoopData = $route->stops->sortBy('sequence'); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $stop): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <tr>
                                    <td><?php echo e($stop->stop_name); ?></td>
                                    <td><?php echo e($stop->location); ?></td>
                                    <td><span class="badge bg-primary"><?php echo e($stop->sequence); ?></span></td>
                                    <td><?php echo e($stop->pickup_time ?? 'N/A'); ?></td>
                                    <td><?php echo e($stop->drop_time ?? 'N/A'); ?></td>
                                </tr>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <?php endif; ?>
        </div>

        <div class="col-lg-4">
            <div class="card">
                <div class="card-header">
                    <h4 class="header-title">Quick Actions</h4>
                </div>
                <div class="card-body">
                    <div class="d-grid gap-2">
                        <a href="<?php echo e(route('transport.routes.edit', $route)); ?>" class="btn btn-primary">
                            <i class="fas fa-edit"></i> Edit Route
                        </a>
                        <a href="<?php echo e(route('transport.trips.create')); ?>?route_id=<?php echo e($route->id); ?>" class="btn btn-success">
                            <i class="fas fa-plus"></i> Schedule Trip
                        </a>
                        <a href="<?php echo e(route('transport.routes.index')); ?>" class="btn btn-secondary">
                            <i class="fas fa-arrow-left"></i> Back to Routes
                        </a>
                    </div>
                </div>
            </div>

            <?php if($route->vehicles->count() > 0): ?>
            <div class="card mt-3">
                <div class="card-header">
                    <h4 class="header-title">Assigned Vehicles</h4>
                </div>
                <div class="card-body">
                    <ul class="list-group list-group-flush">
                        <?php $__currentLoopData = $route->vehicles; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $vehicle): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            <a href="<?php echo e(route('transport.vehicles.show', $vehicle)); ?>"><?php echo e($vehicle->vehicle_number); ?></a>
                            <span class="badge bg-primary rounded-pill"><?php echo e($vehicle->vehicle_type); ?></span>
                        </li>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </ul>
                </div>
            </div>
            <?php endif; ?>
        </div>
    </div>

    <?php if($route->trips->count() > 0): ?>
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
                                    <th>Vehicle</th>
                                    <th>Driver</th>
                                    <th>Status</th>
                                    <th>Passengers</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $__currentLoopData = $route->trips->take(10); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $trip): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <tr>
                                    <td><?php echo e($trip->trip_date->format('d M Y')); ?></td>
                                    <td><?php echo e($trip->vehicle->vehicle_number ?? 'N/A'); ?></td>
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
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\dunth\dunco school management system\duncoschool\Modules\Transport\resources\views\routes\show.blade.php ENDPATH**/ ?>