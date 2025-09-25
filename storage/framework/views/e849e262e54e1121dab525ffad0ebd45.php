

<?php $__env->startSection('content'); ?>
<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="mb-0">Authors</h2>
        <a href="<?php echo e(route('library.authors.create')); ?>" class="btn btn-primary">
            <i class="fas fa-plus me-2"></i>Add Author
        </a>
    </div>

    <div class="card">
        <div class="card-header">
            <h5 class="mb-0">Authors (<?php echo e($authors->count()); ?>)</h5>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Website</th>
                            <th>Books Count</th>
                            <th width="150">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $__empty_1 = true; $__currentLoopData = $authors; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $author): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <tr>
                            <td>
                                <strong><?php echo e($author->name); ?></strong>
                                <?php if($author->biography): ?>
                                    <br><small class="text-muted"><?php echo e(Str::limit($author->biography, 50)); ?></small>
                                <?php endif; ?>
                            </td>
                            <td><?php echo e($author->email ?? '-'); ?></td>
                            <td>
                                <?php if($author->website): ?>
                                    <a href="<?php echo e($author->website); ?>" target="_blank"><?php echo e($author->website); ?></a>
                                <?php else: ?>
                                    -
                                <?php endif; ?>
                            </td>
                            <td>
                                <span class="badge bg-info"><?php echo e($author->books->count()); ?></span>
                            </td>
                            <td>
                                <div class="btn-group btn-group-sm" role="group">
                                    <a href="<?php echo e(route('library.authors.show', $author)); ?>" class="btn btn-outline-info" title="View">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <a href="<?php echo e(route('library.authors.edit', $author)); ?>" class="btn btn-outline-warning" title="Edit">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <form action="<?php echo e(route('library.authors.destroy', $author)); ?>" method="POST" class="d-inline">
                                        <?php echo csrf_field(); ?>
                                        <?php echo method_field('DELETE'); ?>
                                        <button type="submit" class="btn btn-outline-danger" title="Delete" 
                                                onclick="return confirm('Are you sure you want to delete this author?')">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <tr>
                            <td colspan="5" class="text-center py-4">
                                <div class="text-muted">
                                    <i class="fas fa-user-edit fa-2x mb-3"></i>
                                    <p>No authors found.</p>
                                    <a href="<?php echo e(route('library.authors.create')); ?>" class="btn btn-primary">
                                        <i class="fas fa-plus me-2"></i>Add First Author
                                    </a>
                                </div>
                            </td>
                        </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?> 
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\dunth\Documents\duncoschool\Modules/Library/resources/views/authors/index.blade.php ENDPATH**/ ?>