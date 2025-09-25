

<?php $__env->startSection('content'); ?>
<div class="container py-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="mb-0">Academic Results</h1>
    </div>
    <div class="card shadow-sm border-0 mt-4 animate__animated animate__fadeIn">
        <div class="card-body">
            <h4 class="mb-3">Results Overview</h4>
            <p>This is the Academic Results page. Display student results, analytics, and performance summaries here.</p>
            <!-- Add results table, charts, or analytics widgets here -->
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?> 
<?php echo $__env->make('academic::components.layouts.master', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\dunth\dunco school management system\duncoschool\dist\Modules\Academic\resources\views\reports\results.blade.php ENDPATH**/ ?>