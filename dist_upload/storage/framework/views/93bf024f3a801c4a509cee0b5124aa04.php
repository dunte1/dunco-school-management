

<?php $__env->startSection('content'); ?>
<div class="container py-4">
    <h2>Audit Logs</h2>
    <form method="GET" class="row g-2 mb-3 align-items-end">
        <div class="col-md-3">
            <label class="form-label">Action</label>
            <select name="action" class="form-select">
                <option value="">All</option>
                <?php $__currentLoopData = $actions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $action): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <option value="<?php echo e($action); ?>" <?php if(request('action')==$action): ?> selected <?php endif; ?>><?php echo e($action); ?></option>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </select>
        </div>
        <div class="col-md-3">
            <label class="form-label">User</label>
            <select name="user_id" class="form-select">
                <option value="">All</option>
                <?php $__currentLoopData = $users; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $user): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <option value="<?php echo e($user->id); ?>" <?php if(request('user_id')==$user->id): ?> selected <?php endif; ?>><?php echo e($user->name); ?></option>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </select>
        </div>
        <div class="col-md-3">
            <label class="form-label">Date</label>
            <input type="date" name="date" class="form-control" value="<?php echo e(request('date')); ?>">
        </div>
        <div class="col-md-3">
            <button type="submit" class="btn btn-primary">Filter</button>
        </div>
    </form>
    <div class="table-responsive">
        <table class="table table-bordered table-striped">
            <thead>
                <tr>
                    <th>Timestamp</th>
                    <th>User</th>
                    <th>Action</th>
                    <th>Description</th>
                    <th>Details</th>
                </tr>
            </thead>
            <tbody>
                <?php $__empty_1 = true; $__currentLoopData = $logs; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $log): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                <tr>
                    <td><?php echo e($log->created_at); ?></td>
                    <td><?php echo e($log->user->name ?? 'System'); ?></td>
                    <td><?php echo e($log->action); ?></td>
                    <td><?php echo e($log->description); ?></td>
                    <td>
                        <button class="btn btn-sm btn-info" data-bs-toggle="modal" data-bs-target="#logModal<?php echo e($log->id); ?>">View</button>
                        <!-- Modal -->
                        <div class="modal fade" id="logModal<?php echo e($log->id); ?>" tabindex="-1" aria-labelledby="logModalLabel<?php echo e($log->id); ?>" aria-hidden="true">
                          <div class="modal-dialog modal-lg">
                            <div class="modal-content">
                              <div class="modal-header">
                                <h5 class="modal-title" id="logModalLabel<?php echo e($log->id); ?>">Audit Log Details</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                              </div>
                              <div class="modal-body">
                                <strong>Old Values:</strong>
                                <pre><?php echo e(json_encode($log->old_values, JSON_PRETTY_PRINT)); ?></pre>
                                <strong>New Values:</strong>
                                <pre><?php echo e(json_encode($log->new_values, JSON_PRETTY_PRINT)); ?></pre>
                              </div>
                            </div>
                          </div>
                        </div>
                    </td>
                </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                <tr><td colspan="5" class="text-center">No audit logs found.</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
    <div class="mt-3"><?php echo e($logs->links()); ?></div>
</div>
<?php $__env->stopSection(); ?> 
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\dunth\dunco school management system\duncoschool\resources\views\audit_logs\index.blade.php ENDPATH**/ ?>