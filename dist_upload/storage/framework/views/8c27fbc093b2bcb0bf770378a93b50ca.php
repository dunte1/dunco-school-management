

<?php $__env->startSection('content'); ?>
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="page-title-box">
                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="<?php echo e(route('dashboard')); ?>">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="<?php echo e(route('transport.index')); ?>">Transport</a></li>
                        <li class="breadcrumb-item active">Drivers</li>
                    </ol>
                </div>
                <h4 class="page-title">Drivers Management</h4>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <h5 class="card-title">All Drivers</h5>
                        </div>
                        <div class="col-md-6 text-end">
                            <a href="<?php echo e(route('transport.drivers.create')); ?>" class="btn btn-primary">
                                <i class="fas fa-plus me-2"></i>Add Driver
                            </a>
                        </div>
                    </div>

                    <!-- Search and Filters -->
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <form method="GET" action="<?php echo e(route('transport.drivers.index')); ?>">
                                <div class="input-group">
                                    <input type="text" class="form-control" name="search" 
                                           placeholder="Search drivers..." value="<?php echo e(request('search')); ?>">
                                    <button class="btn btn-outline-secondary" type="submit">
                                        <i class="fas fa-search"></i>
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>

                    <div class="table-responsive">
                        <table class="table table-centered table-hover mb-0">
                            <thead>
                                <tr>
                                    <th>Name</th>
                                    <th>License Number</th>
                                    <th>Phone</th>
                                    <th>Status</th>
                                    <th>Experience</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $__empty_1 = true; $__currentLoopData = $drivers; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $driver): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                <tr>
                                    <td>
                                        <strong><?php echo e($driver->name); ?></strong>
                                        <br>
                                        <small class="text-muted"><?php echo e($driver->email ?? 'No email'); ?></small>
                                    </td>
                                    <td>
                                        <?php echo e($driver->license_number); ?>

                                        <br>
                                        <small class="text-muted">Expires: <?php echo e($driver->license_expiry->format('M d, Y')); ?></small>
                                    </td>
                                    <td><?php echo e($driver->phone); ?></td>
                                    <td>
                                        <span class="badge bg-<?php echo e($driver->status === 'active' ? 'success' : ($driver->status === 'inactive' ? 'secondary' : 'danger')); ?>">
                                            <?php echo e(ucfirst($driver->status)); ?>

                                        </span>
                                    </td>
                                    <td><?php echo e($driver->experience_years); ?> years</td>
                                    <td>
                                        <div class="btn-group" role="group">
                                            <a href="<?php echo e(route('transport.drivers.show', $driver)); ?>" 
                                               class="btn btn-sm btn-outline-info">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            <a href="<?php echo e(route('transport.drivers.edit', $driver)); ?>" 
                                               class="btn btn-sm btn-outline-primary">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <form method="POST" action="<?php echo e(route('transport.drivers.destroy', $driver)); ?>" 
                                                  style="display: inline;" onsubmit="return confirm('Are you sure?')">
                                                <?php echo csrf_field(); ?>
                                                <?php echo method_field('DELETE'); ?>
                                                <button type="submit" class="btn btn-sm btn-outline-danger">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                <tr>
                                    <td colspan="6" class="text-center">No drivers found.</td>
                                </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    <div class="row mt-3">
                        <div class="col-12">
                            <?php echo e($drivers->links()); ?>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?> 
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\dunth\dunco school management system\duncoschool\Modules\Transport\resources\views\drivers\index.blade.php ENDPATH**/ ?>