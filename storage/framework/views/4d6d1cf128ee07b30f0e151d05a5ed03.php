<?php $__env->startSection('content'); ?>
<h2>Add Payroll</h2>
<form method="POST" action="<?php echo e(route('hr.payroll.store')); ?>">
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
        <label class="form-label">Payroll Period</label>
        <input type="month" name="payroll_period" class="form-control" required>
    </div>
    <div class="mb-3">
        <label class="form-label">Basic Salary</label>
        <input type="number" name="basic_salary" class="form-control" required>
    </div>
    <div class="mb-3">
        <label class="form-label">Allowances</label>
        <input type="number" name="allowances" class="form-control">
    </div>
    <div class="mb-3">
        <label class="form-label">Bonuses</label>
        <input type="number" name="bonuses" class="form-control">
    </div>
    <div class="mb-3">
        <label class="form-label">Deductions</label>
        <input type="number" name="deductions" class="form-control">
    </div>
    <div class="mb-3">
        <label class="form-label">Net Salary</label>
        <input type="number" name="net_salary" class="form-control" required>
    </div>
    <div class="mb-3">
        <label class="form-label">Status</label>
        <select name="status" class="form-select" required>
            <option value="pending">Pending</option>
            <option value="paid">Paid</option>
        </select>
    </div>
    <button type="submit" class="btn btn-success">Save</button>
    <a href="<?php echo e(route('hr.payroll.index')); ?>" class="btn btn-secondary">Cancel</a>
</form>
<?php $__env->stopSection(); ?> 
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\dunth\Documents\duncoschool\Modules/HR/resources/views/payroll/create.blade.php ENDPATH**/ ?>