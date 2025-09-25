@extends('examination::layouts.app')

@section('content')
<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="fw-bold text-primary" style="font-size: 1.875rem; letter-spacing: -0.025em;">Question Categories</h2>
        <button class="btn btn-primary shadow-sm d-flex align-items-center" data-bs-toggle="modal" data-bs-target="#addCategoryModal">
            <i class="fas fa-plus me-2"></i> Add Category
        </button>
    </div>
    
    <div class="glass-card p-4 mb-4">
        <form class="row g-3 mb-4">
            <div class="col-md-4">
                <input type="text" class="form-control" placeholder="Search categories...">
            </div>
            <div class="col-md-3">
                <select class="form-select">
                    <option selected>All Subjects</option>
                    <option>Mathematics</option>
                    <option>Science</option>
                    <option>English</option>
                    <option>History</option>
                </select>
            </div>
            <div class="col-md-3">
                <select class="form-select">
                    <option selected>All Difficulties</option>
                    <option>Easy</option>
                    <option>Medium</option>
                    <option>Hard</option>
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
                        <th scope="col"><i class="fas fa-folder me-1"></i> Category Name</th>
                        <th scope="col"><i class="fas fa-code me-1"></i> Code</th>
                        <th scope="col"><i class="fas fa-book me-1"></i> Subject</th>
                        <th scope="col"><i class="fas fa-signal me-1"></i> Difficulty</th>
                        <th scope="col"><i class="fas fa-question-circle me-1"></i> Questions</th>
                        <th scope="col"><i class="fas fa-calendar-alt me-1"></i> Created</th>
                        <th scope="col" class="text-center" style="width: 150px;"><i class="fas fa-cogs me-1"></i> Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <th scope="row">1</th>
                        <td>
                            <div>
                                <strong>Mathematics</strong>
                                <br>
                                <small class="text-muted">Core mathematical concepts and problem solving</small>
                            </div>
                        </td>
                        <td><span class="badge bg-secondary">MATH</span></td>
                        <td>Mathematics</td>
                        <td><span class="badge bg-success">Easy</span></td>
                        <td><span class="badge bg-primary">24</span></td>
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
                        <td>
                            <div>
                                <strong>Physics</strong>
                                <br>
                                <small class="text-muted">Fundamental physics principles and applications</small>
                            </div>
                        </td>
                        <td><span class="badge bg-secondary">PHYS</span></td>
                        <td>Science</td>
                        <td><span class="badge bg-warning text-dark">Medium</span></td>
                        <td><span class="badge bg-primary">18</span></td>
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
                        <td>
                            <div>
                                <strong>English Literature</strong>
                                <br>
                                <small class="text-muted">Literary analysis and comprehension</small>
                            </div>
                        </td>
                        <td><span class="badge bg-secondary">ENG</span></td>
                        <td>English</td>
                        <td><span class="badge bg-danger">Hard</span></td>
                        <td><span class="badge bg-primary">32</span></td>
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
    <button class="btn btn-primary rounded-circle shadow-lg position-fixed" style="bottom: 2rem; right: 2rem; width: 60px; height: 60px; z-index: 1050;" data-bs-toggle="modal" data-bs-target="#addCategoryModal">
        <i class="fas fa-plus"></i>
    </button>
    
    <!-- Add Category Modal -->
    <div class="modal fade" id="addCategoryModal" tabindex="-1" aria-labelledby="addCategoryModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title fw-bold" id="addCategoryModalLabel">
                        <i class="fas fa-plus-circle me-2"></i>Add New Category
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form>
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Category Name</label>
                                <input type="text" class="form-control" placeholder="Enter category name">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Category Code</label>
                                <input type="text" class="form-control" placeholder="e.g., MATH, PHYS">
                            </div>
                        </div>
                        
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Subject</label>
                                <select class="form-select">
                                    <option selected>Select subject</option>
                                    <option>Mathematics</option>
                                    <option>Science</option>
                                    <option>English</option>
                                    <option>History</option>
                                    <option>Geography</option>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Difficulty Level</label>
                                <select class="form-select">
                                    <option>Easy</option>
                                    <option>Medium</option>
                                    <option>Hard</option>
                                </select>
                            </div>
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Topic</label>
                            <input type="text" class="form-control" placeholder="e.g., Algebra, Mechanics, Poetry">
                        </div>
                        
                        <div class="mb-4">
                            <label class="form-label fw-semibold">Description</label>
                            <textarea class="form-control" rows="3" placeholder="Enter category description"></textarea>
                        </div>
                        
                        <div class="d-flex justify-content-end gap-2">
                            <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save me-1"></i>Save Category
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 