@extends('examination::layouts.app')

@section('title', 'Create Result')

@section('content')
<style>
    .result-form-card {
        background: linear-gradient(135deg, #ffffff 0%, #f8fafc 100%);
        border-radius: 20px;
        box-shadow: 0 8px 32px rgba(0, 0, 0, 0.06), 0 4px 16px rgba(0, 0, 0, 0.03);
        border: 1px solid rgba(255, 255, 255, 0.8);
        padding: 2rem;
        margin-bottom: 2rem;
        position: relative;
        overflow: hidden;
        backdrop-filter: blur(10px);
    }
    
    .form-header {
        text-align: center;
        margin-bottom: 2rem;
        padding-bottom: 1rem;
        border-bottom: 2px solid #e2e8f0;
    }
    
    .form-title {
        font-size: 2rem;
        font-weight: 700;
        background: linear-gradient(135deg, #1e293b 0%, #334155 100%);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
        margin-bottom: 0.5rem;
    }
    
    .form-subtitle {
        color: #64748b;
        font-size: 1.1rem;
        font-weight: 500;
    }
    
    .form-group {
        margin-bottom: 1.5rem;
    }
    
    .form-label {
        color: #374151;
        font-weight: 600;
        margin-bottom: 0.5rem;
        display: block;
    }
    
    .form-control {
        background: white;
        border: 2px solid #e2e8f0;
        border-radius: 12px;
        padding: 0.75rem 1rem;
        font-size: 1rem;
        transition: all 0.3s ease;
        width: 100%;
    }
    
    .form-control:focus {
        outline: none;
        border-color: #3b82f6;
        box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
    }
    
    .form-select {
        background: white;
        border: 2px solid #e2e8f0;
        border-radius: 12px;
        padding: 0.75rem 1rem;
        font-size: 1rem;
        transition: all 0.3s ease;
        width: 100%;
    }
    
    .form-select:focus {
        outline: none;
        border-color: #3b82f6;
        box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
    }
    
    .btn-submit {
        background: linear-gradient(135deg, #3b82f6 0%, #1d4ed8 100%);
        color: white;
        border: none;
        border-radius: 12px;
        padding: 1rem 2rem;
        font-weight: 600;
        font-size: 1.1rem;
        transition: all 0.3s ease;
        box-shadow: 0 4px 12px rgba(59, 130, 246, 0.3);
        width: 100%;
        margin-top: 1rem;
    }
    
    .btn-submit:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 24px rgba(59, 130, 246, 0.4);
    }
    
    .btn-cancel {
        background: #f1f5f9;
        color: #64748b;
        border: 2px solid #e2e8f0;
        border-radius: 12px;
        padding: 1rem 2rem;
        font-weight: 600;
        font-size: 1.1rem;
        transition: all 0.3s ease;
        text-decoration: none;
        display: inline-block;
        text-align: center;
        margin-top: 1rem;
    }
    
    .btn-cancel:hover {
        background: #e2e8f0;
        color: #374151;
        text-decoration: none;
    }
</style>

<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="result-form-card">
                <div class="form-header">
                    <h1 class="form-title">
                        <i class="fas fa-clipboard-check me-3"></i>
                        Create Result
                    </h1>
                    <p class="form-subtitle">Add examination results for students</p>
                </div>
                
                <form method="POST" action="{{ route('examination.results.store') }}">
                    @csrf
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="student" class="form-label">Student</label>
                                <select class="form-select" id="student" name="student" required>
                                    <option value="">Select Student</option>
                                    <option value="1">John Doe</option>
                                    <option value="2">Jane Smith</option>
                                    <option value="3">Mike Johnson</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="exam" class="form-label">Exam</label>
                                <select class="form-select" id="exam" name="exam" required>
                                    <option value="">Select Exam</option>
                                    <option value="1">Mathematics Final</option>
                                    <option value="2">Physics Midterm</option>
                                    <option value="3">English Literature</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="score" class="form-label">Score</label>
                                <input type="number" class="form-control" id="score" name="score" min="0" max="100" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="total_marks" class="form-label">Total Marks</label>
                                <input type="number" class="form-control" id="total_marks" name="total_marks" min="1" value="100" required>
                            </div>
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label for="grade" class="form-label">Grade</label>
                        <select class="form-select" id="grade" name="grade" required>
                            <option value="">Select Grade</option>
                            <option value="A">A (90-100)</option>
                            <option value="B">B (80-89)</option>
                            <option value="C">C (70-79)</option>
                            <option value="D">D (60-69)</option>
                            <option value="F">F (0-59)</option>
                        </select>
                    </div>
                    
                    <div class="form-group">
                        <label for="remarks" class="form-label">Remarks</label>
                        <textarea class="form-control" id="remarks" name="remarks" rows="3" placeholder="Enter any remarks about the result..."></textarea>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6">
                            <button type="submit" class="btn-submit">
                                <i class="fas fa-save me-2"></i>
                                Create Result
                            </button>
                        </div>
                        <div class="col-md-6">
                            <a href="{{ route('examination.results.index') }}" class="btn-cancel">
                                <i class="fas fa-times me-2"></i>
                                Cancel
                            </a>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection


