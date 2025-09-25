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
                <li class="breadcrumb-item">
                    <a href="#" class="text-decoration-none">Mathematics</a>
                </li>
                <li class="breadcrumb-item active" aria-current="page">Edit Category</li>
            </ol>
        </nav>
        
        <h2 class="fw-bold text-primary" style="font-size: 1.875rem; letter-spacing: -0.025em;">Edit Category</h2>
        <p class="text-muted mb-0">Update the category information and settings</p>
    </div>
    
    <div class="row">
        <div class="col-lg-8">
            <div class="glass-card p-4">
                <form>
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Category Name <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" value="Mathematics" required>
                            <div class="form-text">A descriptive name for the category</div>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Category Code <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" value="MATH" required>
                            <div class="form-text">A unique code to identify the category</div>
                        </div>
                    </div>
                    
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Subject</label>
                            <select class="form-select">
                                <option>Mathematics</option>
                                <option>Science</option>
                                <option>English</option>
                                <option>History</option>
                                <option>Geography</option>
                                <option>Computer Science</option>
                                <option>Art</option>
                                <option>Music</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Difficulty Level <span class="text-danger">*</span></label>
                            <select class="form-select" required>
                                <option value="easy" selected>Easy</option>
                                <option value="medium">Medium</option>
                                <option value="hard">Hard</option>
                            </select>
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Topic</label>
                        <input type="text" class="form-control" value="General Mathematics">
                        <div class="form-text">Specific topic within the subject</div>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Parent Category</label>
                        <select class="form-select">
                            <option selected>No parent category</option>
                            <option>Mathematics</option>
                            <option>Science</option>
                            <option>English</option>
                        </select>
                        <div class="form-text">Optional: Create a subcategory under an existing category</div>
                    </div>
                    
                    <div class="mb-4">
                        <label class="form-label fw-semibold">Description</label>
                        <textarea class="form-control" rows="4">This category covers fundamental mathematical concepts including arithmetic, algebra, geometry, and basic problem-solving techniques. It serves as the foundation for more advanced mathematical topics.</textarea>
                        <div class="form-text">Provide a comprehensive description of what this category covers</div>
                    </div>
                    
                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save me-1"></i>Update Category
                        </button>
                        <a href="{{ route('examination.categories.index') }}" class="btn btn-outline-secondary">
                            <i class="fas fa-times me-1"></i>Cancel
                        </a>
                    </div>
                </form>
            </div>
        </div>
        
        <div class="col-lg-4">
            <div class="glass-card p-4 mb-4">
                <h5 class="fw-bold mb-3">
                    <i class="fas fa-info-circle me-2"></i>Category Information
                </h5>
                
                <div class="mb-3">
                    <label class="form-label fw-semibold text-muted">Created</label>
                    <div>July 6, 2024</div>
                </div>
                
                <div class="mb-3">
                    <label class="form-label fw-semibold text-muted">Last Updated</label>
                    <div>July 6, 2024</div>
                </div>
                
                <div class="mb-3">
                    <label class="form-label fw-semibold text-muted">Total Questions</label>
                    <div><span class="badge bg-primary">24</span></div>
                </div>
                
                <div class="mb-3">
                    <label class="form-label fw-semibold text-muted">Status</label>
                    <div><span class="badge bg-success">Active</span></div>
                </div>
            </div>
            
            <div class="glass-card p-4">
                <h5 class="fw-bold mb-3">
                    <i class="fas fa-exclamation-triangle me-2"></i>Important Notes
                </h5>
                
                <div class="alert alert-warning small">
                    <i class="fas fa-lightbulb me-1"></i>
                    <strong>Note:</strong> Changing the category code may affect existing questions and exams that reference this category.
                </div>
                
                <div class="alert alert-info small">
                    <i class="fas fa-info-circle me-1"></i>
                    <strong>Tip:</strong> Consider creating a new category instead of editing if this category is already in use.
                </div>
                
                <div class="d-grid gap-2 mt-3">
                    <button class="btn btn-outline-danger">
                        <i class="fas fa-trash me-1"></i>Delete Category
                    </button>
                    <button class="btn btn-outline-warning">
                        <i class="fas fa-copy me-1"></i>Duplicate Category
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 