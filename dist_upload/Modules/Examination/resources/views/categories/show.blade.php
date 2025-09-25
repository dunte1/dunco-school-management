@extends('examination::layouts.app')

@section('content')
<div class="container py-4">
    <div class="mb-4">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item">
                    <a href="{{ route('examination.categories.index') }}" class="text-decoration-none">
                        <i class="fas fa-folder me-1"></i>Categories
                    </a>
                </li>
                <li class="breadcrumb-item active" aria-current="page">Category Details</li>
            </ol>
        </nav>
        
        <div class="d-flex justify-content-between align-items-start">
            <div>
                <h2 class="fw-bold text-primary" style="font-size: 1.875rem; letter-spacing: -0.025em;">Mathematics</h2>
                <p class="text-muted mb-0">Core mathematical concepts and problem solving</p>
            </div>
            <div class="d-flex gap-2">
                <a href="#" class="btn btn-warning">
                    <i class="fas fa-edit me-1"></i>Edit
                </a>
                <a href="{{ route('examination.categories.index') }}" class="btn btn-outline-secondary">
                    <i class="fas fa-arrow-left me-1"></i>Back
                </a>
            </div>
        </div>
    </div>
    
    <div class="row">
        <div class="col-lg-8">
            <!-- Category Information -->
            <div class="glass-card p-4 mb-4">
                <h5 class="fw-bold mb-3">
                    <i class="fas fa-info-circle me-2"></i>Category Information
                </h5>
                
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-semibold text-muted">Category Code</label>
                        <div class="fw-bold">MATH</div>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-semibold text-muted">Subject</label>
                        <div class="fw-bold">Mathematics</div>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-semibold text-muted">Difficulty Level</label>
                        <div><span class="badge bg-success">Easy</span></div>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-semibold text-muted">Topic</label>
                        <div class="fw-bold">General Mathematics</div>
                    </div>
                    <div class="col-12 mb-3">
                        <label class="form-label fw-semibold text-muted">Description</label>
                        <div>This category covers fundamental mathematical concepts including arithmetic, algebra, geometry, and basic problem-solving techniques. It serves as the foundation for more advanced mathematical topics.</div>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-semibold text-muted">Created</label>
                        <div>July 6, 2024</div>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-semibold text-muted">Last Updated</label>
                        <div>July 6, 2024</div>
                    </div>
                </div>
            </div>
            
            <!-- Questions in this Category -->
            <div class="glass-card p-4">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h5 class="fw-bold mb-0">
                        <i class="fas fa-question-circle me-2"></i>Questions in this Category
                    </h5>
                    <span class="badge bg-primary fs-6">24 Questions</span>
                </div>
                
                <div class="table-responsive">
                    <table class="table table-premium align-middle mb-0">
                        <thead>
                            <tr>
                                <th scope="col">#</th>
                                <th scope="col">Question</th>
                                <th scope="col">Type</th>
                                <th scope="col">Difficulty</th>
                                <th scope="col">Created</th>
                                <th scope="col" class="text-center">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <th scope="row">1</th>
                                <td>What is 2 + 2?</td>
                                <td><span class="badge bg-info">Multiple Choice</span></td>
                                <td><span class="badge bg-success">Easy</span></td>
                                <td>2024-07-06</td>
                                <td class="text-center">
                                    <button class="btn btn-sm btn-info me-1" title="View">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                    <button class="btn btn-sm btn-warning me-1" title="Edit">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                </td>
                            </tr>
                            <tr>
                                <th scope="row">2</th>
                                <td>Solve for x: 3x + 5 = 20</td>
                                <td><span class="badge bg-warning text-dark">Short Answer</span></td>
                                <td><span class="badge bg-warning text-dark">Medium</span></td>
                                <td>2024-07-06</td>
                                <td class="text-center">
                                    <button class="btn btn-sm btn-info me-1" title="View">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                    <button class="btn btn-sm btn-warning me-1" title="Edit">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                </td>
                            </tr>
                            <tr>
                                <th scope="row">3</th>
                                <td>Calculate the area of a circle with radius 5 units</td>
                                <td><span class="badge bg-danger">Essay</span></td>
                                <td><span class="badge bg-danger">Hard</span></td>
                                <td>2024-07-05</td>
                                <td class="text-center">
                                    <button class="btn btn-sm btn-info me-1" title="View">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                    <button class="btn btn-sm btn-warning me-1" title="Edit">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                
                <div class="text-center mt-3">
                    <a href="#" class="btn btn-outline-primary">
                        <i class="fas fa-plus me-1"></i>Add Question to Category
                    </a>
                </div>
            </div>
        </div>
        
        <div class="col-lg-4">
            <!-- Statistics -->
            <div class="glass-card p-4 mb-4">
                <h5 class="fw-bold mb-3">
                    <i class="fas fa-chart-bar me-2"></i>Category Statistics
                </h5>
                
                <div class="row text-center">
                    <div class="col-6 mb-3">
                        <div class="bg-primary bg-opacity-10 rounded-circle d-inline-flex p-3 mb-2">
                            <i class="fas fa-question-circle fa-2x text-primary"></i>
                        </div>
                        <h4 class="fw-bold text-primary mb-1">24</h4>
                        <p class="text-muted small mb-0">Total Questions</p>
                    </div>
                    <div class="col-6 mb-3">
                        <div class="bg-success bg-opacity-10 rounded-circle d-inline-flex p-3 mb-2">
                            <i class="fas fa-check-circle fa-2x text-success"></i>
                        </div>
                        <h4 class="fw-bold text-success mb-1">18</h4>
                        <p class="text-muted small mb-0">Active Questions</p>
                    </div>
                    <div class="col-6 mb-3">
                        <div class="bg-warning bg-opacity-10 rounded-circle d-inline-flex p-3 mb-2">
                            <i class="fas fa-clock fa-2x text-warning"></i>
                        </div>
                        <h4 class="fw-bold text-warning mb-1">6</h4>
                        <p class="text-muted small mb-0">Draft Questions</p>
                    </div>
                    <div class="col-6 mb-3">
                        <div class="bg-info bg-opacity-10 rounded-circle d-inline-flex p-3 mb-2">
                            <i class="fas fa-file-alt fa-2x text-info"></i>
                        </div>
                        <h4 class="fw-bold text-info mb-1">3</h4>
                        <p class="text-muted small mb-0">Used in Exams</p>
                    </div>
                </div>
            </div>
            
            <!-- Quick Actions -->
            <div class="glass-card p-4 mb-4">
                <h5 class="fw-bold mb-3">
                    <i class="fas fa-bolt me-2"></i>Quick Actions
                </h5>
                
                <div class="d-grid gap-2">
                    <a href="#" class="btn btn-outline-primary">
                        <i class="fas fa-plus me-2"></i>Add Question
                    </a>
                    <a href="#" class="btn btn-outline-success">
                        <i class="fas fa-download me-2"></i>Export Questions
                    </a>
                    <a href="#" class="btn btn-outline-info">
                        <i class="fas fa-upload me-2"></i>Import Questions
                    </a>
                    <a href="#" class="btn btn-outline-warning">
                        <i class="fas fa-copy me-2"></i>Duplicate Category
                    </a>
                </div>
            </div>
            
            <!-- Related Categories -->
            <div class="glass-card p-4">
                <h5 class="fw-bold mb-3">
                    <i class="fas fa-sitemap me-2"></i>Related Categories
                </h5>
                
                <div class="list-group list-group-flush">
                    <a href="#" class="list-group-item list-group-item-action d-flex justify-content-between align-items-center">
                        <div>
                            <strong>Algebra</strong>
                            <br>
                            <small class="text-muted">12 questions</small>
                        </div>
                        <span class="badge bg-success">Easy</span>
                    </a>
                    <a href="#" class="list-group-item list-group-item-action d-flex justify-content-between align-items-center">
                        <div>
                            <strong>Geometry</strong>
                            <br>
                            <small class="text-muted">8 questions</small>
                        </div>
                        <span class="badge bg-warning text-dark">Medium</span>
                    </a>
                    <a href="#" class="list-group-item list-group-item-action d-flex justify-content-between align-items-center">
                        <div>
                            <strong>Calculus</strong>
                            <br>
                            <small class="text-muted">4 questions</small>
                        </div>
                        <span class="badge bg-danger">Hard</span>
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 