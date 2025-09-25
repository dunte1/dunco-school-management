<?php $__env->startSection('content'); ?>
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="mb-0">Staff List</h2>
        <a href="<?php echo e(route('hr.staff.create')); ?>" class="btn btn-primary"><i class="bi bi-person-plus"></i> Add Staff</a>
    </div>
    <div class="card mb-4">
        <div class="card-body">
            <form method="GET" action="" class="row g-2 align-items-end">
                <div class="col-md-4">
                    <input type="text" name="search" class="form-control" placeholder="Search by name or email" value="<?php echo e(request('search')); ?>">
                </div>
                <div class="col-md-3">
                    <select name="role_id" class="form-select">
        <option value="">All Roles</option>
        <?php $__currentLoopData = $roles; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $role): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <option value="<?php echo e($role->id); ?>" <?php echo e(request('role_id') == $role->id ? 'selected' : ''); ?>><?php echo e($role->name); ?></option>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </select>
                </div>
                <div class="col-md-3">
                    <select name="department_id" class="form-select">
        <option value="">All Departments</option>
        <?php $__currentLoopData = $departments; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $department): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <option value="<?php echo e($department->id); ?>" <?php echo e(request('department_id') == $department->id ? 'selected' : ''); ?>><?php echo e($department->name); ?></option>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </select>
                </div>
                <div class="col-md-2">
                    <button type="submit" class="btn btn-outline-primary w-100"><i class="bi bi-funnel"></i> Filter</button>
                </div>
</form>
        </div>
    </div>
    <div class="card">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover table-striped align-middle mb-0">
                    <thead class="table-light">
        <tr>
            <th>Photo</th>
            <th>Name</th>
            <th>Email</th>
            <th>Role</th>
            <th>Department</th>
            <th>Status</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
                        <?php $__empty_1 = true; $__currentLoopData = $staff; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $member): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
        <tr>
                            <td><?php if($member->photo): ?><img src="<?php echo e(asset($member->photo)); ?>" width="40" class="rounded-circle"><?php endif; ?></td>
            <td><?php echo e($member->first_name); ?> <?php echo e($member->last_name); ?></td>
            <td><?php echo e($member->email); ?></td>
            <td><?php echo e($member->role->name ?? ''); ?></td>
            <td><?php echo e($member->department->name ?? ''); ?></td>
                            <td><span class="badge bg-<?php echo e($member->status == 'active' ? 'success' : 'secondary'); ?>"><?php echo e(ucfirst($member->status)); ?></span></td>
            <td>
                                <a href="<?php echo e(route('hr.staff.show', $member->id)); ?>" class="btn btn-sm btn-info" title="View"><i class="bi bi-eye"></i></a>
                                <a href="<?php echo e(route('hr.staff.edit', $member->id)); ?>" class="btn btn-sm btn-warning" title="Edit"><i class="bi bi-pencil"></i></a>
                <form action="<?php echo e(route('hr.staff.destroy', $member->id)); ?>" method="POST" style="display:inline;">
                    <?php echo csrf_field(); ?>
                    <?php echo method_field('DELETE'); ?>
                                    <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Delete this staff?')" title="Delete"><i class="bi bi-trash"></i></button>
                </form>
            </td>
        </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <tr>
                            <td colspan="7" class="text-center text-muted">No staff found.</td>
                        </tr>
                        <?php endif; ?>
    </tbody>
</table>
            </div>
        </div>
        <div class="card-footer">
<?php echo e($staff->links()); ?>

        </div>
    </div>
</div>
<?php $__env->stopSection(); ?> 
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\dunth\Documents\duncoschool\Modules/HR/resources/views/staff/index.blade.php ENDPATH**/ ?>