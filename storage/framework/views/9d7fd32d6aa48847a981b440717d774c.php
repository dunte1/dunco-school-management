<?php $__env->startSection('content'); ?>
<div class="container">
    <h1>Rooms</h1>
    <a href="<?php echo e(route('hostel.rooms.create')); ?>" class="btn btn-primary mb-3">Add Room</a>
    <?php if(session('success')): ?>
        <div class="alert alert-success"><?php echo e(session('success')); ?></div>
    <?php endif; ?>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Name</th>
                <th>Type</th>
                <th>Hostel</th>
                <th>Floor</th>
                <th>Status</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php $__currentLoopData = $rooms; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $room): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <tr>
                    <td><?php echo e($room->name); ?></td>
                    <td><?php echo e(ucfirst($room->type)); ?></td>
                    <td><?php echo e($room->hostel->name ?? 'N/A'); ?></td>
                    <td><?php echo e($room->floor->name ?? 'N/A'); ?></td>
                    <td><?php echo e(ucfirst($room->status)); ?></td>
                    <td>
                        <a href="<?php echo e(route('hostel.rooms.show', $room)); ?>" class="btn btn-info btn-sm">View</a>
                        <a href="<?php echo e(route('hostel.rooms.edit', $room)); ?>" class="btn btn-warning btn-sm">Edit</a>
                        <form action="<?php echo e(route('hostel.rooms.destroy', $room)); ?>" method="POST" style="display:inline-block;">
                            <?php echo csrf_field(); ?>
                            <?php echo method_field('DELETE'); ?>
                            <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure?')">Delete</button>
                        </form>
                    </td>
                </tr>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </tbody>
    </table>
    <?php echo e($rooms->links()); ?>

</div>
<?php $__env->stopSection(); ?> 
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\dunth\Documents\duncoschool\Modules/Hostel/resources/views/rooms/index.blade.php ENDPATH**/ ?>