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
                <li class="breadcrumb-item active" aria-current="page">Create Category</li>
            </ol>
        </nav>
        
        <h2 class="fw-bold text-primary" style="font-size: 1.875rem; letter-spacing: -0.025em;">Create New Category</h2>
        <p class="text-muted mb-0">Add a new question category to organize your examination questions</p>
    </div>
    
    <div class="row">
        <div class="col-lg-8">
            <div class="glass-card p-4">
                <form>
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Category Name <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" placeholder="Enter category name" required>
                            <div class="form-text">A descriptive name for the category</div>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Category Code <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" placeholder="e.g., MATH, PHYS" required>
                            <div class="form-text">A unique code to identify the category</div>
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
                                <option>Computer Science</option>
                                <option>Art</option>
                                <option>Music</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Difficulty Level <span class="text-danger">*</span></label>
                            <select class="form-select" required>
                                <option selected>Select difficulty</option>
                                <option value="easy">Easy</option>
                                <option value="medium">Medium</option>
                                <option value="hard">Hard</option>
                            </select>
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Topic</label>
                        <input type="text" class="form-control" placeholder="e.g., Algebra, Mechanics, Poetry">
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
                        <textarea class="form-control" rows="4" placeholder="Enter detailed description of the category"></textarea>
                        <div class="form-text">Provide a comprehensive description of what this category covers</div>
                    </div>
                    
                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save me-1"></i>Create Category
                        </button>
                        <a href="{{ route('examination.categories.index') }}" class="btn btn-outline-secondary">
                            <i class="fas fa-times me-1"></i>Cancel
                        </a>
                    </div>
                </form>
            </div>
        </div>
        
        <div class="col-lg-4">
            <div class="glass-card p-4">
                <h5 class="fw-bold mb-3">
                    <i class="fas fa-info-circle me-2"></i>Category Guidelines
                </h5>
                
                <div class="mb-3">
                    <h6 class="fw-semibold text-primary">Naming Convention</h6>
                    <ul class="list-unstyled small">
                        <li>• Use clear, descriptive names</li>
                        <li>• Keep names under 255 characters</li>
                        <li>• Avoid special characters</li>
                    </ul>
                </div>
                
                <div class="mb-3">
                    <h6 class="fw-semibold text-primary">Code Requirements</h6>
                    <ul class="list-unstyled small">
                        <li>• Use 2-10 characters</li>
                        <li>• Alphanumeric only</li>
                        <li>• Must be unique</li>
                        <li>• Example: MATH, PHYS, ENG</li>
                    </ul>
                </div>
                
                <div class="mb-3">
                    <h6 class="fw-semibold text-primary">Difficulty Levels</h6>
                    <ul class="list-unstyled small">
                        <li><span class="badge bg-success me-1">Easy</span> Basic concepts</li>
                        <li><span class="badge bg-warning text-dark me-1">Medium</span> Intermediate topics</li>
                        <li><span class="badge bg-danger me-1">Hard</span> Advanced concepts</li>
                    </ul>
                </div>
                
                <div class="alert alert-info small">
                    <i class="fas fa-lightbulb me-1"></i>
                    <strong>Tip:</strong> Well-organized categories help students and teachers find relevant questions quickly.
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 