

<?php $__env->startSection('content'); ?>
<div class="container">
    <h1>Hostel Fees</h1>
    <a href="<?php echo e(route('hostel.fees.create')); ?>" class="btn btn-primary mb-3">Add Fee</a>
    <?php if(session('success')): ?>
        <div class="alert alert-success"><?php echo e(session('success')); ?></div>
    <?php endif; ?>
    <form method="GET" class="row g-3 mb-3">
        <div class="col-md-3">
            <select name="hostel_id" class="form-control" onchange="this.form.submit()">
                <option value="">All Hostels</option>
                <?php $__currentLoopData = $hostels; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $hostel): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <option value="<?php echo e($hostel->id); ?>" <?php echo e(request('hostel_id') == $hostel->id ? 'selected' : ''); ?>><?php echo e($hostel->name); ?></option>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </select>
        </div>
        <div class="col-md-3">
            <select name="student_id" class="form-control" onchange="this.form.submit()">
                <option value="">All Students</option>
                <?php $__currentLoopData = $students; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $student): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <option value="<?php echo e($student->id); ?>" <?php echo e(request('student_id') == $student->id ? 'selected' : ''); ?>><?php echo e($student->name); ?></option>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </select>
        </div>
        <div class="col-md-3">
            <select name="status" class="form-control" onchange="this.form.submit()">
                <option value="">All Statuses</option>
                <option value="paid" <?php echo e(request('status') == 'paid' ? 'selected' : ''); ?>>Paid</option>
                <option value="unpaid" <?php echo e(request('status') == 'unpaid' ? 'selected' : ''); ?>>Unpaid</option>
                <option value="overdue" <?php echo e(request('status') == 'overdue' ? 'selected' : ''); ?>>Overdue</option>
            </select>
        </div>
        <div class="col-md-3">
            <button type="submit" class="btn btn-secondary w-100">Filter</button>
        </div>
    </form>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Student</th>
                <th>Bed</th>
                <th>Room</th>
                <th>Hostel</th>
                <th>Amount</th>
                <th>Status</th>
                <th>Due Date</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php $__currentLoopData = $fees; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $fee): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <tr>
                    <td><?php echo e($fee->student->name ?? 'N/A'); ?></td>
                    <td><?php echo e($fee->bed->bed_number ?? 'N/A'); ?></td>
                    <td><?php echo e($fee->room->name ?? 'N/A'); ?></td>
                    <td><?php echo e($fee->hostel->name ?? 'N/A'); ?></td>
                    <td><?php echo e($fee->amount); ?></td>
                    <td><?php echo e(ucfirst($fee->status)); ?></td>
                    <td><?php echo e($fee->due_date); ?></td>
                    <td>
                        <a href="<?php echo e(route('hostel.fees.show', $fee)); ?>" class="btn btn-info btn-sm">View</a>
                        <a href="<?php echo e(route('hostel.fees.edit', $fee)); ?>" class="btn btn-warning btn-sm">Edit</a>
                        <form action="<?php echo e(route('hostel.fees.destroy', $fee)); ?>" method="POST" style="display:inline-block;">
                            <?php echo csrf_field(); ?>
                            <?php echo method_field('DELETE'); ?>
                            <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure?')">Delete</button>
                        </form>
                    </td>
                </tr>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </tbody>
    </table>
    <?php echo e($fees->links()); ?>

</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\dunth\Documents\duncoschool\Modules/Hostel/resources/views/fees/index.blade.php ENDPATH**/ ?>