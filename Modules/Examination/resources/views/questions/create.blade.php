@extends('examination::layouts.app')

@section('title', 'Create Question')

@section('content')
<style>
    .question-form-card {
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
    
    .option-group {
        background: #f8fafc;
        border-radius: 12px;
        padding: 1rem;
        margin-bottom: 1rem;
        border: 1px solid #e2e8f0;
    }
    
    .option-label {
        font-weight: 600;
        color: #374151;
        margin-bottom: 0.5rem;
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
            <div class="question-form-card">
                <div class="form-header">
                    <h1 class="form-title">
                        <i class="fas fa-question-circle me-3"></i>
                        Create New Question
                    </h1>
                    <p class="form-subtitle">Add a new question to the question bank</p>
                </div>
                
                <form method="POST" action="{{ route('examination.questions.store') }}">
                    @csrf
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="category" class="form-label">Category</label>
                                <select class="form-select" id="category" name="category" required>
                                    <option value="">Select Category</option>
                                    <option value="mathematics">Mathematics</option>
                                    <option value="physics">Physics</option>
                                    <option value="chemistry">Chemistry</option>
                                    <option value="biology">Biology</option>
                                    <option value="english">English</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="difficulty" class="form-label">Difficulty Level</label>
                                <select class="form-select" id="difficulty" name="difficulty" required>
                                    <option value="">Select Difficulty</option>
                                    <option value="easy">Easy</option>
                                    <option value="medium">Medium</option>
                                    <option value="hard">Hard</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label for="question_text" class="form-label">Question Text</label>
                        <textarea class="form-control" id="question_text" name="question_text" rows="4" placeholder="Enter your question here..." required></textarea>
                    </div>
                    
                    <div class="form-group">
                        <label for="question_type" class="form-label">Question Type</label>
                        <select class="form-select" id="question_type" name="question_type" required>
                            <option value="">Select Type</option>
                            <option value="multiple_choice">Multiple Choice</option>
                            <option value="true_false">True/False</option>
                            <option value="essay">Essay</option>
                            <option value="short_answer">Short Answer</option>
                        </select>
                    </div>
                    
                    <div id="options_container" style="display: none;">
                        <div class="option-group">
                            <label class="option-label">Option A</label>
                            <input type="text" class="form-control" name="options[]" placeholder="Enter option A">
                        </div>
                        <div class="option-group">
                            <label class="option-label">Option B</label>
                            <input type="text" class="form-control" name="options[]" placeholder="Enter option B">
                        </div>
                        <div class="option-group">
                            <label class="option-label">Option C</label>
                            <input type="text" class="form-control" name="options[]" placeholder="Enter option C">
                        </div>
                        <div class="option-group">
                            <label class="option-label">Option D</label>
                            <input type="text" class="form-control" name="options[]" placeholder="Enter option D">
                        </div>
                        
                        <div class="form-group">
                            <label for="correct_answer" class="form-label">Correct Answer</label>
                            <select class="form-select" id="correct_answer" name="correct_answer">
                                <option value="">Select Correct Answer</option>
                                <option value="A">Option A</option>
                                <option value="B">Option B</option>
                                <option value="C">Option C</option>
                                <option value="D">Option D</option>
                            </select>
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label for="explanation" class="form-label">Explanation (Optional)</label>
                        <textarea class="form-control" id="explanation" name="explanation" rows="3" placeholder="Provide explanation for the correct answer..."></textarea>
                    </div>
                    
                    <div class="form-group">
                        <label for="marks" class="form-label">Marks</label>
                        <input type="number" class="form-control" id="marks" name="marks" min="1" value="1" required>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6">
                            <button type="submit" class="btn-submit">
                                <i class="fas fa-save me-2"></i>
                                Create Question
                            </button>
                        </div>
                        <div class="col-md-6">
                            <a href="{{ route('examination.questions.index') }}" class="btn-cancel">
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

<script>
document.getElementById('question_type').addEventListener('change', function() {
    const optionsContainer = document.getElementById('options_container');
    if (this.value === 'multiple_choice') {
        optionsContainer.style.display = 'block';
    } else {
        optionsContainer.style.display = 'none';
    }
});
</script>
@endsection 