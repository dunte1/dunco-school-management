<?php $__env->startSection('content'); ?>
    <h2>Payroll</h2>
    <form method="GET" class="row g-2 mb-3">
        <div class="col-md-3">
            <input type="month" name="payroll_period" class="form-control" value="<?php echo e(request('payroll_period')); ?>">
        </div>
        <div class="col-md-3">
            <select name="staff_id" class="form-select">
                <option value="">All Staff</option>
                <?php $__currentLoopData = $staff; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $s): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <option value="<?php echo e($s->id); ?>" <?php echo e(request('staff_id') == $s->id ? 'selected' : ''); ?>><?php echo e($s->first_name); ?> <?php echo e($s->last_name); ?></option>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </select>
        </div>
        <div class="col-md-3">
            <select name="status" class="form-select">
                <option value="">All Status</option>
                <option value="paid" <?php echo e(request('status') == 'paid' ? 'selected' : ''); ?>>Paid</option>
                <option value="pending" <?php echo e(request('status') == 'pending' ? 'selected' : ''); ?>>Pending</option>
            </select>
        </div>
        <div class="col-md-3">
            <button type="submit" class="btn btn-primary">Filter</button>
            <a href="<?php echo e(route('hr.payroll.create')); ?>" class="btn btn-success">Add Payroll</a>
        </div>
    </form>
    <table class="table table-bordered table-hover">
        <thead class="table-light">
            <tr>
                <th>Staff</th>
                <th>Period</th>
                <th>Basic Salary</th>
                <th>Allowances</th>
                <th>Bonuses</th>
                <th>Deductions</th>
                <th>Net Salary</th>
                <th>Status</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php $__currentLoopData = $payrolls; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $payroll): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <tr>
                <td><?php echo e($payroll->staff->first_name); ?> <?php echo e($payroll->staff->last_name); ?></td>
                <td><?php echo e($payroll->payroll_period); ?></td>
                <td><?php echo e(number_format($payroll->basic_salary, 2)); ?></td>
                <td><?php echo e(number_format($payroll->allowances, 2)); ?></td>
                <td><?php echo e(number_format($payroll->bonuses, 2)); ?></td>
                <td><?php echo e(number_format($payroll->deductions, 2)); ?></td>
                <td><strong><?php echo e(number_format($payroll->net_salary, 2)); ?></strong></td>
                <td><span class="badge bg-<?php echo e($payroll->status == 'paid' ? 'success' : 'warning'); ?>"><?php echo e(ucfirst($payroll->status)); ?></span></td>
                <td>
                    <?php if($payroll->status == 'pending'): ?>
                        <form action="<?php echo e(route('hr.payroll.markPaid', $payroll->id)); ?>" method="POST" style="display:inline;">
                            <?php echo csrf_field(); ?>
                            <button type="submit" class="btn btn-sm btn-success">Mark Paid</button>
                        </form>
                    <?php endif; ?>
                </td>
            </tr>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </tbody>
    </table>
    <?php echo e($payrolls->links()); ?>

<?php $__env->stopSection(); ?> 
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\dunth\Documents\duncoschool\Modules/HR/resources/views/payroll/index.blade.php ENDPATH**/ ?>