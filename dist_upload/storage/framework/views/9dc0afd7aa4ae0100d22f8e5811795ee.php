

<?php $__env->startSection('content'); ?>
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="page-title-box">
                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="<?php echo e(route('dashboard')); ?>">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="<?php echo e(route('transport.trips.index')); ?>">Trips</a></li>
                        <li class="breadcrumb-item active">Trip Details</li>
                    </ol>
                </div>
                <h4 class="page-title">Trip Details</h4>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-8">
            <div class="card">
                <div class="card-header">
                    <h4 class="header-title">Trip Information</h4>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <table class="table table-borderless">
                                <tr>
                                    <td><strong>Trip Date:</strong></td>
                                    <td><?php echo e($trip->trip_date->format('d M Y')); ?></td>
                                </tr>
                                <tr>
                                    <td><strong>Start Time:</strong></td>
                                    <td><?php echo e($trip->start_time ?? 'N/A'); ?></td>
                                </tr>
                                <tr>
                                    <td><strong>End Time:</strong></td>
                                    <td><?php echo e($trip->end_time ?? 'N/A'); ?></td>
                                </tr>
                                <tr>
                                    <td><strong>Status:</strong></td>
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
                                </tr>
                                <tr>
                                    <td><strong>Passenger Count:</strong></td>
                                    <td><?php echo e($trip->passenger_count ?? 0); ?></td>
                                </tr>
                                <tr>
                                    <td><strong>Distance Covered:</strong></td>
                                    <td><?php echo e($trip->distance_covered ?? 0); ?> km</td>
                                </tr>
                                <tr>
                                    <td><strong>Fuel Consumed:</strong></td>
                                    <td><?php echo e($trip->fuel_consumed ?? 0); ?> liters</td>
                                </tr>
                                <tr>
                                    <td><strong>Created:</strong></td>
                                    <td><?php echo e($trip->created_at->format('d M Y H:i')); ?></td>
                                </tr>
                            </table>
                        </div>
                        <div class="col-md-6">
                            <table class="table table-borderless">
                                <tr>
                                    <td><strong>Route:</strong></td>
                                    <td>
                                        <?php if($trip->route): ?>
                                            <a href="<?php echo e(route('transport.routes.show', $trip->route)); ?>"><?php echo e($trip->route->name); ?></a>
                                        <?php else: ?>
                                            <span class="text-muted">N/A</span>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                                <tr>
                                    <td><strong>Vehicle:</strong></td>
                                    <td>
                                        <?php if($trip->vehicle): ?>
                                            <a href="<?php echo e(route('transport.vehicles.show', $trip->vehicle)); ?>"><?php echo e($trip->vehicle->vehicle_number); ?></a>
                                        <?php else: ?>
                                            <span class="text-muted">N/A</span>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                                <tr>
                                    <td><strong>Driver:</strong></td>
                                    <td>
                                        <?php if($trip->driver): ?>
                                            <a href="<?php echo e(route('transport.drivers.show', $trip->driver)); ?>"><?php echo e($trip->driver->name); ?></a>
                                        <?php else: ?>
                                            <span class="text-muted">N/A</span>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                                <tr>
                                    <td><strong>School:</strong></td>
                                    <td><?php echo e($trip->school->name ?? 'N/A'); ?></td>
                                </tr>
                                <tr>
                                    <td><strong>Last Updated:</strong></td>
                                    <td><?php echo e($trip->updated_at->format('d M Y H:i')); ?></td>
                                </tr>
                            </table>
                        </div>
                    </div>

                    <?php if($trip->notes): ?>
                    <div class="row mt-3">
                        <div class="col-12">
                            <h5>Notes:</h5>
                            <p><?php echo e($trip->notes); ?></p>
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
                        <a href="<?php echo e(route('transport.trips.edit', $trip)); ?>" class="btn btn-primary">
                            <i class="fas fa-edit"></i> Edit Trip
                        </a>
                        <a href="<?php echo e(route('transport.trips.index')); ?>" class="btn btn-secondary">
                            <i class="fas fa-arrow-left"></i> Back to Trips
                        </a>
                    </div>
                </div>
            </div>

            <?php if($trip->passengers->count() > 0): ?>
            <div class="card mt-3">
                <div class="card-header">
                    <h4 class="header-title">Passengers</h4>
                </div>
                <div class="card-body">
                    <ul class="list-group list-group-flush">
                        <?php $__currentLoopData = $trip->passengers; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $passenger): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            <span><?php echo e($passenger->name ?? 'Passenger ' . $loop->iteration); ?></span>
                            <span class="badge bg-primary rounded-pill"><?php echo e($passenger->pivot->pickup_location ?? 'N/A'); ?></span>
                        </li>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </ul>
                </div>
            </div>
            <?php endif; ?>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?> 
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\dunth\dunco school management system\duncoschool\Modules\Transport\resources\views\trips\show.blade.php ENDPATH**/ ?>