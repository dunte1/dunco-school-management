

<?php $__env->startSection('content'); ?>
<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="mb-0">Library Dashboard</h2>
        <div>
            <a href="<?php echo e(route('library.books.create')); ?>" class="btn btn-primary me-2">
                <i class="fas fa-plus me-2"></i>Add Book
            </a>
            <a href="<?php echo e(route('library.books.index')); ?>" class="btn btn-outline-primary">
                <i class="fas fa-book me-2"></i>View All Books
            </a>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card bg-primary text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h4 class="mb-0"><?php echo e($totalBooks); ?></h4>
                            <p class="mb-0">Total Books</p>
                        </div>
                        <div class="align-self-center">
                            <i class="fas fa-book fa-2x"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-success text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h4 class="mb-0"><?php echo e($activeMembers); ?></h4>
                            <p class="mb-0">Active Members</p>
                        </div>
                        <div class="align-self-center">
                            <i class="fas fa-users fa-2x"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-warning text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h4 class="mb-0"><?php echo e($booksBorrowed); ?></h4>
                            <p class="mb-0">Books Borrowed</p>
                        </div>
                        <div class="align-self-center">
                            <i class="fas fa-exchange-alt fa-2x"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-danger text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h4 class="mb-0"><?php echo e($overdueBooks); ?></h4>
                            <p class="mb-0">Overdue Books</p>
                        </div>
                        <div class="align-self-center">
                            <i class="fas fa-exclamation-triangle fa-2x"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Recent Books -->
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Recent Books</h5>
                </div>
                <div class="card-body">
                    <?php if($recentBooks->count() > 0): ?>
                        <div class="list-group list-group-flush">
                            <?php $__currentLoopData = $recentBooks; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $book): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <div class="list-group-item d-flex justify-content-between align-items-center">
                                <div>
                                    <h6 class="mb-1"><?php echo e($book->title); ?></h6>
                                    <small class="text-muted">
                                        <?php echo e($book->author->name ?? 'Unknown Author'); ?> • 
                                        <?php echo e($book->category->name ?? 'Uncategorized'); ?>

                                    </small>
                                </div>
                                <span class="badge bg-<?php echo e($book->status == 'available' ? 'success' : 'warning'); ?>">
                                    <?php echo e(ucfirst($book->status)); ?>

                                </span>
                            </div>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </div>
                    <?php else: ?>
                        <p class="text-muted text-center py-3">No books added yet.</p>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <!-- Books by Status -->
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Books by Status</h5>
                </div>
                <div class="card-body">
                    <?php if($booksByStatus->count() > 0): ?>
                        <div class="list-group list-group-flush">
                            <?php $__currentLoopData = $booksByStatus; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $status): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <div class="list-group-item d-flex justify-content-between align-items-center">
                                <span class="text-capitalize"><?php echo e($status->status); ?></span>
                                <span class="badge bg-primary"><?php echo e($status->count); ?></span>
                            </div>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </div>
                    <?php else: ?>
                        <p class="text-muted text-center py-3">No books found.</p>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="row mt-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Quick Actions</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-3 mb-3">
                            <a href="<?php echo e(route('library.books.create')); ?>" class="btn btn-outline-primary w-100">
                                <i class="fas fa-plus me-2"></i>Add Book
                            </a>
                        </div>
                        <div class="col-md-3 mb-3">
                            <a href="<?php echo e(route('library.authors.index')); ?>" class="btn btn-outline-info w-100">
                                <i class="fas fa-user-edit me-2"></i>Manage Authors
                            </a>
                        </div>
                        <div class="col-md-3 mb-3">
                            <a href="<?php echo e(route('library.categories.index')); ?>" class="btn btn-outline-success w-100">
                                <i class="fas fa-tags me-2"></i>Manage Categories
                            </a>
                        </div>
                        <div class="col-md-3 mb-3">
                            <a href="<?php echo e(route('library.reports.borrowed')); ?>" class="btn btn-outline-warning w-100">
                                <i class="fas fa-chart-bar me-2"></i>View Reports
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?> 
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\dunth\Documents\duncoschool\Modules/Library/resources/views/dashboard.blade.php ENDPATH**/ ?>