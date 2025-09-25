<?php $__env->startSection('content'); ?>
<h2>Apply for Leave</h2>
<form method="POST" action="<?php echo e(route('hr.leave.store')); ?>">
    <?php echo csrf_field(); ?>
    <div class="mb-3">
        <label class="form-label">Staff</label>
        <select name="staff_id" class="form-select" required>
            <option value="">Select Staff</option>
            <?php $__currentLoopData = $staff; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $s): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <option value="<?php echo e($s->id); ?>"><?php echo e($s->first_name); ?> <?php echo e($s->last_name); ?></option>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </select>
    </div>
    <div class="mb-3">
        <label class="form-label">Leave Type</label>
        <select name="type" class="form-select" required>
            <option value="">Select Type</option>
            <?php $__currentLoopData = $types; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $type): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <option value="<?php echo e($type->name); ?>"><?php echo e($type->name); ?></option>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </select>
    </div>
    <div class="mb-3">
        <label class="form-label">Start Date</label>
        <input type="date" name="start_date" class="form-control" required>
    </div>
    <div class="mb-3">
        <label class="form-label">End Date</label>
        <input type="date" name="end_date" class="form-control" required>
    </div>
    <div class="mb-3">
        <label class="form-label">Reason</label>
        <input type="text" name="reason" class="form-control">
    </div>
    <button type="submit" class="btn btn-success">Submit</button>
    <a href="<?php echo e(route('hr.leave.index')); ?>" class="btn btn-secondary">Cancel</a>
</form>
<?php $__env->stopSection(); ?> 
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\dunth\Documents\duncoschool\Modules/HR/resources/views/leave/create.blade.php ENDPATH**/ ?>