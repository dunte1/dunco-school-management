<?php $__env->startSection('content'); ?>
<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="mb-0">Library Books</h2>
        <a href="<?php echo e(route('library.books.create')); ?>" class="btn btn-primary">
            <i class="fas fa-plus me-2"></i>Add Book
        </a>
    </div>

    <!-- Search and Filters -->
    <div class="card mb-4">
        <div class="card-body">
            <form method="GET" action="<?php echo e(route('library.books.index')); ?>" class="row g-3">
                <div class="col-md-3">
                    <input type="text" name="search" class="form-control" placeholder="Search books..." value="<?php echo e(request('search')); ?>">
                </div>
                <div class="col-md-2">
                    <select name="category" class="form-select">
                        <option value="">All Categories</option>
                        <?php $__currentLoopData = $categories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $category): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($category->id); ?>" <?php echo e(request('category') == $category->id ? 'selected' : ''); ?>>
                                <?php echo e($category->name); ?>

                            </option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                </div>
                <div class="col-md-2">
                    <select name="author" class="form-select">
                        <option value="">All Authors</option>
                        <?php $__currentLoopData = $authors; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $author): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($author->id); ?>" <?php echo e(request('author') == $author->id ? 'selected' : ''); ?>>
                                <?php echo e($author->name); ?>

                            </option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                </div>
                <div class="col-md-2">
                    <select name="status" class="form-select">
                        <option value="">All Status</option>
                        <option value="available" <?php echo e(request('status') == 'available' ? 'selected' : ''); ?>>Available</option>
                        <option value="borrowed" <?php echo e(request('status') == 'borrowed' ? 'selected' : ''); ?>>Borrowed</option>
                        <option value="reserved" <?php echo e(request('status') == 'reserved' ? 'selected' : ''); ?>>Reserved</option>
                        <option value="lost" <?php echo e(request('status') == 'lost' ? 'selected' : ''); ?>>Lost</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <button type="submit" class="btn btn-outline-primary me-2">
                        <i class="fas fa-search me-1"></i>Search
                    </button>
                    <a href="<?php echo e(route('library.books.index')); ?>" class="btn btn-outline-secondary">
                        <i class="fas fa-times me-1"></i>Clear
                    </a>
                </div>
            </form>
        </div>
    </div>

    <!-- Books Table -->
    <div class="card">
        <div class="card-header">
            <h5 class="mb-0">Books (<?php echo e($books->total()); ?>)</h5>
</div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead class="table-light">
        <tr>
            <th>Title</th>
            <th>Author</th>
            <th>Category</th>
            <th>Publisher</th>
            <th>ISBN</th>
            <th>Status</th>
                            <th width="150">Actions</th>
        </tr>
    </thead>
    <tbody>
                        <?php $__empty_1 = true; $__currentLoopData = $books; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $book): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <tr>
                            <td>
                                <strong><?php echo e($book->title); ?></strong>
                                <?php if($book->edition): ?>
                                    <br><small class="text-muted"><?php echo e($book->edition); ?></small>
                                <?php endif; ?>
                            </td>
            <td><?php echo e($book->author->name ?? '-'); ?></td>
                            <td>
                                <?php if($book->category): ?>
                                    <span class="badge bg-info"><?php echo e($book->category->name); ?></span>
                                <?php else: ?>
                                    -
                                <?php endif; ?>
                            </td>
            <td><?php echo e($book->publisher->name ?? '-'); ?></td>
                            <td><code><?php echo e($book->isbn); ?></code></td>
                            <td>
                                <span class="badge bg-<?php echo e($book->status == 'available' ? 'success' : ($book->status == 'borrowed' ? 'warning' : 'danger')); ?>">
                                    <?php echo e(ucfirst($book->status)); ?>

                                </span>
                            </td>
                            <td>
                                <div class="btn-group btn-group-sm" role="group">
                                    <a href="<?php echo e(route('library.books.show', $book)); ?>" class="btn btn-outline-info" title="View">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <a href="<?php echo e(route('library.books.edit', $book)); ?>" class="btn btn-outline-warning" title="Edit">
                                        <i class="fas fa-edit"></i>
                                    </a>
                <form action="<?php echo e(route('library.books.destroy', $book)); ?>" method="POST" class="d-inline">
                    <?php echo csrf_field(); ?>
                    <?php echo method_field('DELETE'); ?>
                                        <button type="submit" class="btn btn-outline-danger" title="Delete" 
                                                onclick="return confirm('Are you sure you want to delete this book?')">
                                            <i class="fas fa-trash"></i>
                                        </button>
                </form>
                                </div>
                            </td>
                        </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <tr>
                            <td colspan="7" class="text-center py-4">
                                <div class="text-muted">
                                    <i class="fas fa-book fa-2x mb-3"></i>
                                    <p>No books found.</p>
                                    <a href="<?php echo e(route('library.books.create')); ?>" class="btn btn-primary">
                                        <i class="fas fa-plus me-2"></i>Add First Book
                                    </a>
                                </div>
            </td>
        </tr>
                        <?php endif; ?>
    </tbody>
</table>
            </div>
        </div>
        <?php if($books->hasPages()): ?>
        <div class="card-footer">
            <?php echo e($books->links()); ?>

        </div>
        <?php endif; ?>
    </div>
</div>
<?php $__env->stopSection(); ?> 
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\dunth\Documents\duncoschool\Modules/Library/resources/views/books/index.blade.php ENDPATH**/ ?>