

<?php $__env->startSection('title', 'Library Categories'); ?>

<?php $__env->startSection('content'); ?>
<style>
    .library-container {
        background: linear-gradient(135deg, #f8fafc 0%, #e2e8f0 100%);
        min-height: 100vh;
        padding: 2rem;
    }
    
    .library-header {
        background: linear-gradient(135deg, #1e40af 0%, #3b82f6 100%);
        color: white;
        padding: 2rem;
        border-radius: 1rem;
        margin-bottom: 2rem;
        box-shadow: 0 10px 30px rgba(30, 64, 175, 0.2);
    }
    
    .library-title {
        font-size: 2.5rem;
        font-weight: 800;
        margin-bottom: 0.5rem;
    }
    
    .library-subtitle {
        font-size: 1.1rem;
        opacity: 0.9;
    }
    
    .categories-card {
        background: white;
        border-radius: 1rem;
        box-shadow: 0 4px 20px rgba(0,0,0,0.08);
        border: 1px solid #e2e8f0;
        overflow: hidden;
    }
    
    .categories-header {
        background: #f8fafc;
        padding: 1.5rem;
        border-bottom: 1px solid #e2e8f0;
        display: flex;
        justify-content: between;
        align-items: center;
    }
    
    .categories-title {
        font-size: 1.5rem;
        font-weight: 700;
        color: #1e293b;
    }
    
    .btn-add-category {
        background: linear-gradient(135deg, #10b981 0%, #059669 100%);
        color: white;
        border: none;
        padding: 0.75rem 1.5rem;
        border-radius: 0.5rem;
        font-weight: 600;
        text-decoration: none;
        transition: all 0.3s ease;
    }
    
    .btn-add-category:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 25px rgba(16, 185, 129, 0.3);
        color: white;
        text-decoration: none;
    }
    
    .categories-table {
        width: 100%;
        border-collapse: collapse;
    }
    
    .categories-table th {
        background: #f1f5f9;
        font-weight: 700;
        color: #475569;
        padding: 1rem;
        text-align: left;
        border-bottom: 2px solid #e2e8f0;
    }
    
    .categories-table td {
        padding: 1rem;
        border-bottom: 1px solid #f1f5f9;
        color: #475569;
    }
    
    .categories-table tbody tr:hover {
        background: #f8fafc;
        transition: background 0.2s;
    }
    
    .category-name {
        font-weight: 600;
        color: #1e293b;
    }
    
    .category-description {
        color: #64748b;
        font-size: 0.9rem;
    }
    
    .category-count {
        background: #e0e7ff;
        color: #3730a3;
        padding: 0.25rem 0.75rem;
        border-radius: 1rem;
        font-size: 0.875rem;
        font-weight: 600;
    }
    
    .category-date {
        color: #64748b;
        font-size: 0.9rem;
    }
    
    .action-buttons {
        display: flex;
        gap: 0.5rem;
    }
    
    .btn-view {
        background: #3b82f6;
        color: white;
        border: none;
        padding: 0.5rem 1rem;
        border-radius: 0.25rem;
        font-size: 0.875rem;
        text-decoration: none;
        transition: all 0.2s;
    }
    
    .btn-view:hover {
        background: #2563eb;
        color: white;
        text-decoration: none;
    }
    
    .btn-edit {
        background: #f59e0b;
        color: white;
        border: none;
        padding: 0.5rem 1rem;
        border-radius: 0.25rem;
        font-size: 0.875rem;
        text-decoration: none;
        transition: all 0.2s;
    }
    
    .btn-edit:hover {
        background: #d97706;
        color: white;
        text-decoration: none;
    }
    
    .btn-delete {
        background: #ef4444;
        color: white;
        border: none;
        padding: 0.5rem 1rem;
        border-radius: 0.25rem;
        font-size: 0.875rem;
        text-decoration: none;
        transition: all 0.2s;
    }
    
    .btn-delete:hover {
        background: #dc2626;
        color: white;
        text-decoration: none;
    }
</style>

<div class="library-container">
    <div class="library-header">
        <h1 class="library-title">Library Management System</h1>
        <p class="library-subtitle">Manage book categories and classifications</p>
    </div>
    
    <div class="categories-card">
        <div class="categories-header">
            <h2 class="categories-title">Book Categories</h2>
            <a href="<?php echo e(route('library.categories.create')); ?>" class="btn-add-category">
                <i class="fas fa-plus me-2"></i>Add Category
            </a>
        </div>
        
        <div class="table-responsive">
            <table class="categories-table">
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Description</th>
                        <th>Books</th>
                        <th>Created</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $__empty_1 = true; $__currentLoopData = $categories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $category): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <tr>
                        <td>
                            <div class="category-name"><?php echo e($category->name); ?></div>
                        </td>
                        <td>
                            <span class="category-description"><?php echo e($category->description); ?></span>
                        </td>
                        <td>
                            <span class="category-count"><?php echo e($category->book_count); ?> books</span>
                        </td>
                        <td>
                            <span class="category-date"><?php echo e(\Carbon\Carbon::parse($category->created_at)->format('M d, Y')); ?></span>
                        </td>
                        <td>
                            <div class="action-buttons">
                                <a href="<?php echo e(route('library.categories.show', $category->id)); ?>" class="btn-view">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <a href="<?php echo e(route('library.categories.edit', $category->id)); ?>" class="btn-edit">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <form action="<?php echo e(route('library.categories.destroy', $category->id)); ?>" method="POST" style="display: inline;">
                                    <?php echo csrf_field(); ?>
                                    <?php echo method_field('DELETE'); ?>
                                    <button type="submit" class="btn-delete" onclick="return confirm('Are you sure you want to delete this category?')">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <tr>
                        <td colspan="5" class="text-center py-4 text-muted">
                            <i class="fas fa-info-circle me-2"></i>No categories found
                        </td>
                    </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\dunth\Documents\duncoschool\Modules/Library/resources/views/categories/index.blade.php ENDPATH**/ ?>