<?php $__env->startSection('content'); ?>
<h2>Leave Applications</h2>
<form method="GET" class="row g-2 mb-3">
    <div class="col-md-3">
        <select name="staff_id" class="form-select">
            <option value="">All Staff</option>
            <?php $__currentLoopData = $staff; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $s): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <option value="<?php echo e($s->id); ?>" <?php echo e(request('staff_id') == $s->id ? 'selected' : ''); ?>><?php echo e($s->first_name); ?> <?php echo e($s->last_name); ?></option>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </select>
    </div>
    <div class="col-md-3">
        <select name="type" class="form-select">
            <option value="">All Types</option>
            <?php $__currentLoopData = $types; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $type): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <option value="<?php echo e($type->name); ?>" <?php echo e(request('type') == $type->name ? 'selected' : ''); ?>><?php echo e($type->name); ?></option>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </select>
    </div>
    <div class="col-md-3">
        <select name="status" class="form-select">
            <option value="">All Status</option>
            <option value="pending" <?php echo e(request('status') == 'pending' ? 'selected' : ''); ?>>Pending</option>
            <option value="approved" <?php echo e(request('status') == 'approved' ? 'selected' : ''); ?>>Approved</option>
            <option value="rejected" <?php echo e(request('status') == 'rejected' ? 'selected' : ''); ?>>Rejected</option>
        </select>
    </div>
    <div class="col-md-3">
        <button type="submit" class="btn btn-primary">Filter</button>
        <a href="<?php echo e(route('hr.leave.create')); ?>" class="btn btn-success">Apply for Leave</a>
    </div>
</form>
<table class="table table-bordered table-hover">
    <thead class="table-light">
        <tr>
            <th>Staff</th>
            <th>Type</th>
            <th>Start</th>
            <th>End</th>
            <th>Days</th>
            <th>Status</th>
            <th>Reason</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
        <?php $__currentLoopData = $leaves; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $leave): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <tr>
            <td><?php echo e($leave->staff->first_name); ?> <?php echo e($leave->staff->last_name); ?></td>
            <td><?php echo e($leave->type); ?></td>
            <td><?php echo e($leave->start_date); ?></td>
            <td><?php echo e($leave->end_date); ?></td>
            <td><?php echo e($leave->days); ?></td>
            <td><span class="badge bg-<?php echo e($leave->status == 'approved' ? 'success' : ($leave->status == 'rejected' ? 'danger' : 'warning')); ?>"><?php echo e(ucfirst($leave->status)); ?></span></td>
            <td><?php echo e($leave->reason); ?></td>
            <td>
                <?php if($leave->status == 'pending'): ?>
                    <form action="<?php echo e(route('hr.leave.approve', $leave->id)); ?>" method="POST" style="display:inline;">
                        <?php echo csrf_field(); ?>
                        <button type="submit" class="btn btn-sm btn-success">Approve</button>
                    </form>
                    <form action="<?php echo e(route('hr.leave.reject', $leave->id)); ?>" method="POST" style="display:inline;">
                        <?php echo csrf_field(); ?>
                        <button type="submit" class="btn btn-sm btn-danger">Reject</button>
                    </form>
                <?php endif; ?>
            </td>
        </tr>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </tbody>
</table>
<?php echo e($leaves->links()); ?>

<?php $__env->stopSection(); ?> 
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\dunth\Documents\duncoschool\Modules/HR/resources/views/leave/index.blade.php ENDPATH**/ ?>