

<?php $__env->startSection('title', 'Transport Reports'); ?>

<?php $__env->startSection('content'); ?>
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Transport Reports</h3>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="card">
                                <div class="card-header">
                                    <h5>Monthly Trips</h5>
                                </div>
                                <div class="card-body">
                                    <?php if(isset($monthlyTrips) && $monthlyTrips->count() > 0): ?>
                                        <canvas id="monthlyTripsChart"></canvas>
                                    <?php else: ?>
                                        <p class="text-muted">No trip data available.</p>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="card">
                                <div class="card-header">
                                    <h5>Vehicle Utilization</h5>
                                </div>
                                <div class="card-body">
                                    <?php if(isset($vehicleUtilization) && $vehicleUtilization->count() > 0): ?>
                                        <div class="table-responsive">
                                            <table class="table table-sm">
                                                <thead>
                                                    <tr>
                                                        <th>Vehicle</th>
                                                        <th>Trips This Month</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php $__currentLoopData = $vehicleUtilization; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $vehicle): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                    <tr>
                                                        <td><?php echo e($vehicle->vehicle_number); ?></td>
                                                        <td><?php echo e($vehicle->trips_count); ?></td>
                                                    </tr>
                                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                </tbody>
                                            </table>
                                        </div>
                                    <?php else: ?>
                                        <p class="text-muted">No vehicle utilization data available.</p>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="row mt-4">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-header">
                                    <h5>Driver Performance</h5>
                                </div>
                                <div class="card-body">
                                    <?php if(isset($driverPerformance) && $driverPerformance->count() > 0): ?>
                                        <div class="table-responsive">
                                            <table class="table table-sm">
                                                <thead>
                                                    <tr>
                                                        <th>Driver</th>
                                                        <th>Trips This Month</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php $__currentLoopData = $driverPerformance; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $driver): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                    <tr>
                                                        <td><?php echo e($driver->name); ?></td>
                                                        <td><?php echo e($driver->trips_count); ?></td>
                                                    </tr>
                                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                </tbody>
                                            </table>
                                        </div>
                                    <?php else: ?>
                                        <p class="text-muted">No driver performance data available.</p>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php $__env->startPush('scripts'); ?>
<script>
document.addEventListener('DOMContentLoaded', function() {
    <?php if(isset($monthlyTrips) && $monthlyTrips->count() > 0): ?>
    const ctx = document.getElementById('monthlyTripsChart').getContext('2d');
    const monthlyTripsData = <?php echo json_encode($monthlyTrips, 15, 512) ?>;
    
    const labels = monthlyTripsData.map(item => {
        const monthNames = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 
                           'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];
        return monthNames[item.month - 1];
    });
    
    const data = monthlyTripsData.map(item => item.count);
    
    new Chart(ctx, {
        type: 'bar',
        data: {
            labels: labels,
            datasets: [{
                label: 'Trips',
                data: data,
                backgroundColor: 'rgba(54, 162, 235, 0.2)',
                borderColor: 'rgba(54, 162, 235, 1)',
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            scales: {
                y: {
                    beginAtZero: true
                }
            }
        }
    });
    <?php endif; ?>
});
</script>
<?php $__env->stopPush(); ?>
<?php $__env->stopSection(); ?> 
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\dunth\dunco school management system\duncoschool\dist\Modules\Transport\resources\views\reports.blade.php ENDPATH**/ ?>