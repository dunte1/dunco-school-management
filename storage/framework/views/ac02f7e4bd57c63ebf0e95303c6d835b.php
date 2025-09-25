<?php $__env->startSection('content'); ?>
<h2>Contracts</h2>
<form method="GET" class="row g-2 mb-3">
    <div class="col-md-4">
        <select name="staff_id" class="form-select">
            <option value="">All Staff</option>
            <?php $__currentLoopData = $staff; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $s): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <option value="<?php echo e($s->id); ?>" <?php echo e(request('staff_id') == $s->id ? 'selected' : ''); ?>><?php echo e($s->first_name); ?> <?php echo e($s->last_name); ?></option>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </select>
    </div>
    <div class="col-md-4">
        <select name="type" class="form-select">
            <option value="">All Types</option>
            <option value="Permanent" <?php echo e(request('type') == 'Permanent' ? 'selected' : ''); ?>>Permanent</option>
            <option value="Contract" <?php echo e(request('type') == 'Contract' ? 'selected' : ''); ?>>Contract</option>
            <option value="Probation" <?php echo e(request('type') == 'Probation' ? 'selected' : ''); ?>>Probation</option>
        </select>
    </div>
    <div class="col-md-4">
        <button type="submit" class="btn btn-primary">Filter</button>
        <a href="<?php echo e(route('hr.contract.create')); ?>" class="btn btn-success">Add Contract</a>
    </div>
</form>
<table class="table table-bordered table-hover">
    <thead class="table-light">
        <tr>
            <th>Staff</th>
            <th>Type</th>
            <th>Start</th>
            <th>End</th>
            <th>Duration (months)</th>
            <th>Probation</th>
            <th>Promotion</th>
            <th>Transfer</th>
        </tr>
    </thead>
    <tbody>
        <?php $__currentLoopData = $contracts; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $contract): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <tr>
            <td><?php echo e($contract->staff->first_name); ?> <?php echo e($contract->staff->last_name); ?></td>
            <td><?php echo e($contract->type); ?></td>
            <td><?php echo e($contract->start_date); ?></td>
            <td><?php echo e($contract->end_date); ?></td>
            <td><?php echo e($contract->duration_months); ?></td>
            <td><?php echo e($contract->on_probation ? 'Yes' : 'No'); ?> <?php if($contract->on_probation && $contract->probation_end): ?> (ends <?php echo e($contract->probation_end); ?>) <?php endif; ?></td>
            <td><?php if($contract->promotion_from && $contract->promotion_to): ?> <?php echo e($contract->promotion_from); ?> → <?php echo e($contract->promotion_to); ?> (<?php echo e($contract->promotion_date); ?>) <?php endif; ?></td>
            <td><?php if($contract->transfer_from_department && $contract->transfer_to_department): ?> Dept <?php echo e($contract->transfer_from_department); ?> → <?php echo e($contract->transfer_to_department); ?> <?php endif; ?></td>
        </tr>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </tbody>
</table>
<?php echo e($contracts->links()); ?>

<?php $__env->stopSection(); ?> 
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\dunth\Documents\duncoschool\Modules/HR/resources/views/contract/index.blade.php ENDPATH**/ ?>