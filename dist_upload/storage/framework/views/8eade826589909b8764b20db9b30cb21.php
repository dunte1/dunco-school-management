

<?php $__env->startSection('content'); ?>
<div class="container">
    <h1>Analytics Dashboard</h1>
    <div class="row mb-4">
        <div class="col-md-4">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Total Students</h5>
                    <h2><?php echo e($totalStudents); ?></h2>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Total Fees Collected</h5>
                    <h2><?php echo e(number_format($totalFeesCollected, 2)); ?></h2>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Outstanding Fees</h5>
                    <h2><?php echo e(number_format($totalOutstanding, 2)); ?></h2>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-6">
            <div class="card mb-4">
                <div class="card-body">
                    <h5 class="card-title">Enrollment by Class</h5>
                    <canvas id="enrollmentByClassChart"></canvas>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card mb-4">
                <div class="card-body">
                    <h5 class="card-title">Fee Collection Trend</h5>
                    <canvas id="feeCollectionTrendChart"></canvas>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            <div class="card mb-4">
                <div class="card-body">
                    <h5 class="card-title">Compliance (Missing Documents)</h5>
                    <canvas id="complianceChart"></canvas>
                </div>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts'); ?>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Enrollment by Class
    var ctx1 = document.getElementById('enrollmentByClassChart').getContext('2d');
    new Chart(ctx1, {
        type: 'bar',
        data: {
            labels: <?php echo json_encode(array_keys($enrollmentByClass->toArray())); ?>,
            datasets: [{
                label: 'Students',
                data: <?php echo json_encode(array_values($enrollmentByClass->toArray())); ?>,
                backgroundColor: 'rgba(54, 162, 235, 0.7)'
            }]
        },
        options: {responsive: true}
    });
    // Fee Collection Trend
    var ctx2 = document.getElementById('feeCollectionTrendChart').getContext('2d');
    new Chart(ctx2, {
        type: 'line',
        data: {
            labels: <?php echo json_encode(array_keys($feeTrend->toArray())); ?>,
            datasets: [{
                label: 'Fees Collected',
                data: <?php echo json_encode(array_values($feeTrend->toArray())); ?>,
                backgroundColor: 'rgba(75, 192, 192, 0.2)',
                borderColor: 'rgba(75, 192, 192, 1)',
                fill: true
            }]
        },
        options: {responsive: true}
    });
    // Compliance (Missing Documents)
    var ctx3 = document.getElementById('complianceChart').getContext('2d');
    new Chart(ctx3, {
        type: 'doughnut',
        data: {
            labels: ['Missing Documents', 'Compliant'],
            datasets: [{
                data: [<?php echo e($studentsMissingDocs); ?>, <?php echo e($totalStudents - $studentsMissingDocs); ?>],
                backgroundColor: ['#dc3545', '#28a745']
            }]
        },
        options: {responsive: true}
    });
});
</script>
<?php $__env->stopPush(); ?> 
<?php echo $__env->make('academic::layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\dunth\dunco school management system\duncoschool\Modules\Academic\resources\views\reports\dashboard.blade.php ENDPATH**/ ?>