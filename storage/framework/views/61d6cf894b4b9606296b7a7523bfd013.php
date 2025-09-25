<?php $__env->startSection('content'); ?>
<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="fw-bold text-primary" style="font-size: 1.875rem; letter-spacing: -0.025em;">Question Bank</h2>
        <button class="btn btn-primary shadow-sm d-flex align-items-center" data-bs-toggle="modal" data-bs-target="#addQuestionModal">
            <i class="fas fa-plus me-2"></i> Add Question
        </button>
    </div>
    
    <div class="glass-card p-4 mb-4">
        <form class="row g-3 mb-4">
            <div class="col-md-4">
                <input type="text" class="form-control" placeholder="Search questions...">
            </div>
            <div class="col-md-3">
                <select class="form-select">
                    <option selected>All Categories</option>
                    <option>Mathematics</option>
                    <option>Science</option>
                    <option>English</option>
                    <option>History</option>
                </select>
            </div>
            <div class="col-md-3">
                <select class="form-select">
                    <option selected>All Statuses</option>
                    <option>Active</option>
                    <option>Inactive</option>
                </select>
            </div>
            <div class="col-md-2 d-flex align-items-end">
                <button class="btn btn-outline-primary w-100" type="submit">
                    <i class="fas fa-search me-1"></i> Filter
                </button>
            </div>
        </form>
        
        <div class="table-responsive">
            <table class="table table-premium align-middle mb-0">
                <thead>
                    <tr>
                        <th scope="col" style="width: 60px;">#</th>
                        <th scope="col"><i class="fas fa-question-circle me-1"></i> Question</th>
                        <th scope="col"><i class="fas fa-list me-1"></i> Category</th>
                        <th scope="col"><i class="fas fa-user me-1"></i> Created By</th>
                        <th scope="col"><i class="fas fa-calendar-alt me-1"></i> Created At</th>
                        <th scope="col" class="text-center" style="width: 150px;"><i class="fas fa-cogs me-1"></i> Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <th scope="row">1</th>
                        <td>What is the capital of France?</td>
                        <td><span class="badge bg-primary">Geography</span></td>
                        <td>Admin</td>
                        <td>2024-07-06</td>
                        <td class="text-center">
                            <button class="btn btn-sm btn-info me-1" title="View">
                                <i class="fas fa-eye"></i>
                            </button>
                            <button class="btn btn-sm btn-warning me-1" title="Edit">
                                <i class="fas fa-edit"></i>
                            </button>
                            <button class="btn btn-sm btn-danger" title="Delete">
                                <i class="fas fa-trash"></i>
                            </button>
                        </td>
                    </tr>
                    <tr>
                        <th scope="row">2</th>
                        <td>What is 2 + 2?</td>
                        <td><span class="badge bg-success">Mathematics</span></td>
                        <td>Teacher</td>
                        <td>2024-07-05</td>
                        <td class="text-center">
                            <button class="btn btn-sm btn-info me-1" title="View">
                                <i class="fas fa-eye"></i>
                            </button>
                            <button class="btn btn-sm btn-warning me-1" title="Edit">
                                <i class="fas fa-edit"></i>
                            </button>
                            <button class="btn btn-sm btn-danger" title="Delete">
                                <i class="fas fa-trash"></i>
                            </button>
                        </td>
                    </tr>
                    <tr>
                        <th scope="row">3</th>
                        <td>What is the chemical symbol for water?</td>
                        <td><span class="badge bg-warning text-dark">Science</span></td>
                        <td>Admin</td>
                        <td>2024-07-04</td>
                        <td class="text-center">
                            <button class="btn btn-sm btn-info me-1" title="View">
                                <i class="fas fa-eye"></i>
                            </button>
                            <button class="btn btn-sm btn-warning me-1" title="Edit">
                                <i class="fas fa-edit"></i>
                            </button>
                            <button class="btn btn-sm btn-danger" title="Delete">
                                <i class="fas fa-trash"></i>
                            </button>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
    
    <!-- Floating Action Button -->
    <button class="btn btn-primary rounded-circle shadow-lg position-fixed" style="bottom: 2rem; right: 2rem; width: 60px; height: 60px; z-index: 1050;" data-bs-toggle="modal" data-bs-target="#addQuestionModal">
        <i class="fas fa-plus"></i>
    </button>
    
    <!-- Add Question Modal -->
    <div class="modal fade" id="addQuestionModal" tabindex="-1" aria-labelledby="addQuestionModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title fw-bold" id="addQuestionModalLabel">
                        <i class="fas fa-plus-circle me-2"></i>Add New Question
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form>
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Question</label>
                            <textarea class="form-control" rows="3" placeholder="Enter question text"></textarea>
                        </div>
                        
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Category</label>
                                <select class="form-select">
                                    <option selected>Select category</option>
                                    <option>Mathematics</option>
                                    <option>Science</option>
                                    <option>English</option>
                                    <option>History</option>
                                    <option>Geography</option>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Status</label>
                                <select class="form-select">
                                    <option>Active</option>
                                    <option>Inactive</option>
                                </select>
                            </div>
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Options</label>
                            <div class="row g-2">
                                <div class="col-md-6">
                                    <input type="text" class="form-control" placeholder="Option 1">
                                </div>
                                <div class="col-md-6">
                                    <input type="text" class="form-control" placeholder="Option 2">
                                </div>
                                <div class="col-md-6">
                                    <input type="text" class="form-control" placeholder="Option 3">
                                </div>
                                <div class="col-md-6">
                                    <input type="text" class="form-control" placeholder="Option 4">
                                </div>
                            </div>
                        </div>
                        
                        <div class="mb-4">
                            <label class="form-label fw-semibold">Correct Answer</label>
                            <input type="text" class="form-control" placeholder="Enter correct answer">
                        </div>
                        
                        <div class="d-flex justify-content-end gap-2">
                            <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save me-1"></i>Save Question
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?> 
<?php echo $__env->make('examination::layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\dunth\Documents\duncoschool\Modules/Examination/resources/views/questions/index.blade.php ENDPATH**/ ?>