<?php $__env->startSection('content'); ?>
<h2>Add Contract</h2>
<form method="POST" action="<?php echo e(route('hr.contract.store')); ?>">
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
        <label class="form-label">Contract Type</label>
        <select name="type" class="form-select" required>
            <option value="">Select Type</option>
            <option value="Permanent">Permanent</option>
            <option value="Contract">Contract</option>
            <option value="Probation">Probation</option>
        </select>
    </div>
    <div class="mb-3">
        <label class="form-label">Start Date</label>
        <input type="date" name="start_date" class="form-control" required>
    </div>
    <div class="mb-3">
        <label class="form-label">End Date</label>
        <input type="date" name="end_date" class="form-control">
    </div>
    <div class="mb-3">
        <label class="form-label">Duration (months)</label>
        <input type="number" name="duration_months" class="form-control">
    </div>
    <div class="mb-3">
        <label class="form-label">On Probation?</label>
        <select name="on_probation" class="form-select">
            <option value="0">No</option>
            <option value="1">Yes</option>
        </select>
    </div>
    <div class="mb-3">
        <label class="form-label">Probation End</label>
        <input type="date" name="probation_end" class="form-control">
    </div>
    <div class="mb-3">
        <label class="form-label">Renewal Reminder</label>
        <input type="date" name="renewal_reminder" class="form-control">
    </div>
    <div class="mb-3">
        <label class="form-label">Promotion (from → to)</label>
        <input type="text" name="promotion_from" class="form-control" placeholder="Old Position">
        <input type="text" name="promotion_to" class="form-control mt-2" placeholder="New Position">
        <input type="date" name="promotion_date" class="form-control mt-2" placeholder="Promotion Date">
        <input type="number" name="old_salary" class="form-control mt-2" placeholder="Old Salary">
        <input type="number" name="new_salary" class="form-control mt-2" placeholder="New Salary">
    </div>
    <div class="mb-3">
        <label class="form-label">Transfer (from → to department)</label>
        <select name="transfer_from_department" class="form-select">
            <option value="">From Department</option>
            <?php $__currentLoopData = $departments; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $d): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <option value="<?php echo e($d->id); ?>"><?php echo e($d->name); ?></option>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </select>
        <select name="transfer_to_department" class="form-select mt-2">
            <option value="">To Department</option>
            <?php $__currentLoopData = $departments; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $d): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <option value="<?php echo e($d->id); ?>"><?php echo e($d->name); ?></option>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </select>
    </div>
    <button type="submit" class="btn btn-success">Save</button>
    <a href="<?php echo e(route('hr.contract.index')); ?>" class="btn btn-secondary">Cancel</a>
</form>
<?php $__env->stopSection(); ?> 
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\dunth\Documents\duncoschool\Modules/HR/resources/views/contract/create.blade.php ENDPATH**/ ?>