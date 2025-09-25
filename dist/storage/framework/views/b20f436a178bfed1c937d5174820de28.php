

<?php $__env->startSection('content'); ?>
<div class="container-fluid">
    <!-- Page Header -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">
            <i class="fas fa-chalkboard-teacher text-primary"></i>
            Academic Classes
        </h1>
        <a href="<?php echo e(route('academic.classes.create')); ?>" class="btn btn-primary">
            <i class="fas fa-plus"></i> Create New Class
        </a>
    </div>

    <!-- Search and Filter Card -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Search & Filter</h6>
        </div>
        <div class="card-body">
            <form method="GET" action="<?php echo e(route('academic.classes.index')); ?>" class="row g-3">
                <div class="col-md-3">
                    <label for="search" class="form-label">Search</label>
                    <input type="text" class="form-control" id="search" name="search" 
                           value="<?php echo e(request('search')); ?>" placeholder="Class name, code, or description">
                </div>
                <div class="col-md-2">
                    <label for="academic_year" class="form-label">Academic Year</label>
                    <select class="form-select" id="academic_year" name="academic_year">
                        <option value="">All Years</option>
                        <?php $__currentLoopData = $academicYears; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $year): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($year); ?>" <?php echo e(request('academic_year') == $year ? 'selected' : ''); ?>>
                                <?php echo e($year); ?>

                            </option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                </div>
                <div class="col-md-2">
                    <label for="teacher_id" class="form-label">Teacher</label>
                    <select class="form-select" id="teacher_id" name="teacher_id">
                        <option value="">All Teachers</option>
                        <?php $__currentLoopData = $teachers; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $teacher): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($teacher->id); ?>" <?php echo e(request('teacher_id') == $teacher->id ? 'selected' : ''); ?>>
                                <?php echo e($teacher->name); ?>

                            </option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                </div>
                <div class="col-md-2">
                    <label for="status" class="form-label">Status</label>
                    <select class="form-select" id="status" name="status">
                        <option value="">All Status</option>
                        <option value="active" <?php echo e(request('status') == 'active' ? 'selected' : ''); ?>>Active</option>
                        <option value="inactive" <?php echo e(request('status') == 'inactive' ? 'selected' : ''); ?>>Inactive</option>
                    </select>
                </div>
                <div class="col-md-3 d-flex align-items-end">
                    <button type="submit" class="btn btn-primary me-2">
                        <i class="fas fa-search"></i> Search
                    </button>
                    <a href="<?php echo e(route('academic.classes.index')); ?>" class="btn btn-secondary">
                        <i class="fas fa-times"></i> Clear
                    </a>
                </div>
            </form>
        </div>
    </div>

    <!-- Classes Table -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Classes List</h6>
        </div>
        <div class="card-body">
            <?php if($classes->count() > 0): ?>
                <div class="table-responsive">
                    <table class="table table-bordered" width="100%" cellspacing="0">
                        <thead>
                            <tr>
                                <th>Class Name</th>
                                <th>Code</th>
                                <th>Teacher</th>
                                <th>Students</th>
                                <th>Academic Year</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $__currentLoopData = $classes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $class): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <tr>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <div class="avatar-sm bg-primary rounded-circle d-flex align-items-center justify-content-center me-2">
                                            <i class="fas fa-chalkboard-teacher text-white"></i>
                                        </div>
                                        <div>
                                            <strong><?php echo e($class->name); ?></strong>
                                            <?php if($class->description): ?>
                                                <br><small class="text-muted"><?php echo e(Str::limit($class->description, 50)); ?></small>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                </td>
                                <td><code><?php echo e($class->code); ?></code></td>
                                <td>
                                    <?php if($class->teacher): ?>
                                        <div class="d-flex align-items-center">
                                            <div class="avatar-sm bg-info rounded-circle d-flex align-items-center justify-content-center me-2">
                                                <i class="fas fa-user text-white"></i>
                                            </div>
                                            <?php echo e($class->teacher->name); ?>

                                        </div>
                                    <?php else: ?>
                                        <span class="text-muted">Not Assigned</span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <span class="me-2"><?php echo e($class->students_count ?? 0); ?>/<?php echo e($class->capacity); ?></span>
                                        <div class="progress flex-grow-1" style="height: 6px;">
                                            <?php
                                                $percentage = $class->capacity > 0 ? (($class->students_count ?? 0) / $class->capacity) * 100 : 0;
                                            ?>
                                            <div class="progress-bar <?php echo e($percentage >= 90 ? 'bg-danger' : ($percentage >= 75 ? 'bg-warning' : 'bg-success')); ?>" 
                                                 style="width: <?php echo e($percentage); ?>%"></div>
                                        </div>
                                    </div>
                                </td>
                                <td><?php echo e($class->academic_year); ?></td>
                                <td>
                                    <?php if($class->is_active): ?>
                                        <span class="badge badge-success">Active</span>
                                    <?php else: ?>
                                        <span class="badge badge-secondary">Inactive</span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <div class="btn-group" role="group">
                                        <a href="<?php echo e(route('academic.classes.show', $class->id)); ?>" 
                                           class="btn btn-info btn-sm" title="View">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <a href="<?php echo e(route('academic.classes.edit', $class->id)); ?>" 
                                           class="btn btn-warning btn-sm" title="Edit">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <form method="POST" action="<?php echo e(route('academic.classes.toggle-status', $class->id)); ?>" 
                                              class="d-inline" onsubmit="return confirm('Are you sure you want to change the status?')">
                                            <?php echo csrf_field(); ?>
                                            <?php echo method_field('PATCH'); ?>
                                            <button type="submit" class="btn btn-<?php echo e($class->is_active ? 'secondary' : 'success'); ?> btn-sm" 
                                                    title="<?php echo e($class->is_active ? 'Deactivate' : 'Activate'); ?>">
                                                <i class="fas fa-<?php echo e($class->is_active ? 'pause' : 'play'); ?>"></i>
                                            </button>
                                        </form>
                                        <form method="POST" action="<?php echo e(route('academic.classes.destroy', $class->id)); ?>" 
                                              class="d-inline" onsubmit="return confirm('Are you sure you want to delete this class?')">
                                            <?php echo csrf_field(); ?>
                                            <?php echo method_field('DELETE'); ?>
                                            <button type="submit" class="btn btn-danger btn-sm" title="Delete">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                <div class="d-flex justify-content-center">
                    <?php echo e($classes->appends(request()->query())->links()); ?>

                </div>
            <?php else: ?>
                <div class="text-center py-4">
                    <i class="fas fa-chalkboard-teacher fa-3x text-gray-300 mb-3"></i>
                    <h5 class="text-gray-500">No classes found</h5>
                    <p class="text-gray-400">Create your first class to get started.</p>
                    <a href="<?php echo e(route('academic.classes.create')); ?>" class="btn btn-primary">
                        <i class="fas fa-plus"></i> Create First Class
                    </a>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<?php $__env->startPush('scripts'); ?>
<script>
    // Auto-submit form when filters change
    document.querySelectorAll('#academic_year, #teacher_id, #status').forEach(select => {
        select.addEventListener('change', function() {
            this.closest('form').submit();
        });
    });
</script>
<?php $__env->stopPush(); ?>
<?php $__env->stopSection(); ?> 
<?php echo $__env->make('academic::components.layouts.master', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\dunth\dunco school management system\duncoschool\dist\Modules\Academic\resources\views\classes\index.blade.php ENDPATH**/ ?>