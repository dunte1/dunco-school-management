<?php $__env->startSection('content'); ?>
<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h2>Rooms</h2>
        <a href="<?php echo e(route('rooms.create')); ?>" class="btn btn-primary">Add Room</a>
    </div>
    <?php if(session('success')): ?>
        <div class="alert alert-success"><?php echo e(session('success')); ?></div>
    <?php endif; ?>
    <table class="table table-bordered table-striped">
        <thead>
            <tr>
                <th>ID</th>
                <th>Name</th>
                <th>Capacity</th>
                <th>Location</th>
                <th>Type</th>
                <th>Equipment</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php $__empty_1 = true; $__currentLoopData = $rooms; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $room): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                <tr>
                    <td><?php echo e($room->id); ?></td>
                    <td><?php echo e($room->name); ?></td>
                    <td><?php echo e($room->capacity); ?></td>
                    <td><?php echo e($room->location); ?></td>
                    <td><?php echo e($room->type ?? '-'); ?></td>
                    <td>
                        <?php if($room->equipment): ?>
                            <?php $__currentLoopData = explode(',', $room->equipment); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <span class="badge bg-info text-dark me-1"><?php echo e(trim($item)); ?></span>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        <?php else: ?>
                            <span class="text-muted">—</span>
                        <?php endif; ?>
                    </td>
                    <td>
                        <a href="<?php echo e(route('rooms.show', $room->id)); ?>" class="btn btn-sm btn-info">View</a>
                        <a href="<?php echo e(route('rooms.edit', $room->id)); ?>" class="btn btn-sm btn-warning">Edit</a>
                        <form action="<?php echo e(route('rooms.destroy', $room->id)); ?>" method="POST" style="display:inline-block;">
                            <?php echo csrf_field(); ?>
                            <?php echo method_field('DELETE'); ?>
                            <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Delete this room?')">Delete</button>
                        </form>
                    </td>
                </tr>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                <tr><td colspan="5" class="text-center">No rooms found.</td></tr>
            <?php endif; ?>
        </tbody>
    </table>
    <?php if(method_exists($rooms, 'links')): ?>
        <?php echo e($rooms->links()); ?>

    <?php endif; ?>
</div>
<?php $__env->stopSection(); ?> 
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\dunth\Documents\duncoschool\Modules/Timetable/resources/views/rooms/index.blade.php ENDPATH**/ ?>