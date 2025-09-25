

<?php $__env->startSection('content'); ?>
<div class="container">
    <h1>Hostels</h1>
    <a href="<?php echo e(route('hostel.hostels.create')); ?>" class="btn btn-primary mb-3">Add Hostel</a>
    <?php if(session('success')): ?>
        <div class="alert alert-success"><?php echo e(session('success')); ?></div>
    <?php endif; ?>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Name</th>
                <th>Location</th>
                <th>Gender Restriction</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php $__currentLoopData = $hostels; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $hostel): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <tr>
                    <td><?php echo e($hostel->name); ?></td>
                    <td><?php echo e($hostel->location); ?></td>
                    <td><?php echo e(ucfirst($hostel->gender_restriction)); ?></td>
                    <td>
                        <a href="<?php echo e(route('hostel.hostels.show', $hostel)); ?>" class="btn btn-info btn-sm">View</a>
                        <a href="<?php echo e(route('hostel.hostels.edit', $hostel)); ?>" class="btn btn-warning btn-sm">Edit</a>
                        <form action="<?php echo e(route('hostel.hostels.destroy', $hostel)); ?>" method="POST" style="display:inline-block;">
                            <?php echo csrf_field(); ?>
                            <?php echo method_field('DELETE'); ?>
                            <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure?')">Delete</button>
                        </form>
                    </td>
                </tr>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </tbody>
    </table>
    <?php echo e($hostels->links()); ?>

</div>
<?php $__env->stopSection(); ?> 
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\dunth\Documents\duncoschool\Modules/Hostel/resources/views/hostels/index.blade.php ENDPATH**/ ?>