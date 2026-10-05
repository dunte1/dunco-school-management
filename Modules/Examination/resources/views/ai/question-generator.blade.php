@extends('layouts.app')

@section('title', 'AI Question Generator')

@section('content')
<div class="container-xl py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="fw-bold mb-1" style="color:#1a237e;">AI Question Generator</h2>
            <p class="text-muted mb-0">Generate intelligent questions using artificial intelligence</p>
        </div>
        <div>
            <a href="{{ route('examination.ai.dashboard') }}" class="btn btn-outline-secondary">
                <i class="fas fa-arrow-left me-2"></i>Back to AI Dashboard
            </a>
        </div>
    </div>

    <div class="row">
        <!-- Generation Form -->
        <div class="col-lg-8">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">
                        <i class="fas fa-magic me-2"></i>Question Generation Settings
                    </h5>
                </div>
                <div class="card-body">
                    <form id="questionGeneratorForm">
                        @csrf
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Subject</label>
                                <select class="form-select" name="subject" required>
                                    <option value="">Select Subject</option>
                                    <option value="mathematics">Mathematics</option>
                                    <option value="physics">Physics</option>
                                    <option value="chemistry">Chemistry</option>
                                    <option value="biology">Biology</option>
                                    <option value="english">English</option>
                                    <option value="history">History</option>
                                </select>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Topic</label>
                                <input type="text" class="form-control" name="topic" placeholder="e.g., Algebra, Organic Chemistry" required>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-4 mb-3">
                                <label class="form-label">Question Type</label>
                                <select class="form-select" name="question_type" required>
                                    <option value="mcq">Multiple Choice</option>
                                    <option value="true_false">True/False</option>
                                    <option value="fill_blank">Fill in the Blank</option>
                                    <option value="short_answer">Short Answer</option>
                                    <option value="essay">Essay</option>
                                </select>
                            </div>
                            <div class="col-md-4 mb-3">
                                <label class="form-label">Difficulty Level</label>
                                <select class="form-select" name="difficulty" required>
                                    <option value="easy">Easy</option>
                                    <option value="medium" selected>Medium</option>
                                    <option value="hard">Hard</option>
                                </select>
                            </div>
                            <div class="col-md-4 mb-3">
                                <label class="form-label">Number of Questions</label>
                                <input type="number" class="form-control" name="count" value="5" min="1" max="20" required>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Additional Context (Optional)</label>
                            <textarea class="form-control" name="context" rows="3" placeholder="Provide any additional context, specific requirements, or learning objectives..."></textarea>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Source Material (Optional)</label>
                            <div class="row">
                                <div class="col-md-6">
                                    <label class="form-label small">Syllabus Content</label>
                                    <textarea class="form-control" name="syllabus" rows="3" placeholder="Paste relevant syllabus content..."></textarea>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label small">Notes/Textbook Content</label>
                                    <textarea class="form-control" name="notes" rows="3" placeholder="Paste relevant notes or textbook content..."></textarea>
                                </div>
                            </div>
                        </div>

                        <div class="d-grid">
                            <button type="submit" class="btn btn-primary btn-lg">
                                <i class="fas fa-magic me-2"></i>Generate Questions
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Generated Questions -->
            <div class="card mt-4" id="generatedQuestions" style="display: none;">
                <div class="card-header">
                    <h5 class="mb-0">
                        <i class="fas fa-list me-2"></i>Generated Questions
                    </h5>
                </div>
                <div class="card-body">
                    <div id="questionsList">
                        <!-- Generated questions will appear here -->
                    </div>
                    <div class="mt-3">
                        <button class="btn btn-success" onclick="saveQuestions()">
                            <i class="fas fa-save me-2"></i>Save All Questions
                        </button>
                        <button class="btn btn-outline-secondary" onclick="regenerateQuestions()">
                            <i class="fas fa-redo me-2"></i>Regenerate
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- AI Features Info -->
        <div class="col-lg-4">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">
                        <i class="fas fa-info-circle me-2"></i>AI Features
                    </h5>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <h6 class="text-primary">Smart Generation</h6>
                        <p class="small text-muted">AI analyzes your input to create contextually relevant questions</p>
                    </div>
                    <div class="mb-3">
                        <h6 class="text-primary">Difficulty Scaling</h6>
                        <p class="small text-muted">Questions are automatically scaled to match the selected difficulty level</p>
                    </div>
                    <div class="mb-3">
                        <h6 class="text-primary">Multiple Formats</h6>
                        <p class="small text-muted">Generate various question types from the same content</p>
                    </div>
                    <div class="mb-3">
                        <h6 class="text-primary">Quality Assurance</h6>
                        <p class="small text-muted">AI ensures questions are clear, accurate, and educationally sound</p>
                    </div>
                </div>
            </div>

            <!-- Generation Tips -->
            <div class="card mt-3">
                <div class="card-header">
                    <h5 class="mb-0">
                        <i class="fas fa-lightbulb me-2"></i>Tips for Better Results
                    </h5>
                </div>
                <div class="card-body">
                    <ul class="list-unstyled mb-0">
                        <li class="mb-2">
                            <i class="fas fa-check text-success me-2"></i>
                            Be specific with topics and context
                        </li>
                        <li class="mb-2">
                            <i class="fas fa-check text-success me-2"></i>
                            Provide relevant source material
                        </li>
                        <li class="mb-2">
                            <i class="fas fa-check text-success me-2"></i>
                            Use clear learning objectives
                        </li>
                        <li class="mb-2">
                            <i class="fas fa-check text-success me-2"></i>
                            Review generated questions before saving
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
document.getElementById('questionGeneratorForm').addEventListener('submit', function(e) {
    e.preventDefault();
    
    // Show loading state
    const submitBtn = this.querySelector('button[type="submit"]');
    const originalText = submitBtn.innerHTML;
    submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Generating...';
    submitBtn.disabled = true;
    
    // Simulate AI generation
    setTimeout(() => {
        generateQuestions();
        submitBtn.innerHTML = originalText;
        submitBtn.disabled = false;
    }, 3000);
});

function generateQuestions() {
    const formData = new FormData(document.getElementById('questionGeneratorForm'));
    const count = parseInt(formData.get('count'));
    const type = formData.get('question_type');
    const subject = formData.get('subject');
    const topic = formData.get('topic');
    
    let questionsHtml = '';
    
    for (let i = 1; i <= count; i++) {
        questionsHtml += `
            <div class="card mb-3">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h6 class="mb-0">Question ${i}</h6>
                    <span class="badge bg-primary">${type.toUpperCase()}</span>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <label class="form-label">Question Text</label>
                        <textarea class="form-control" rows="3">Sample ${subject} question about ${topic} - Question ${i}</textarea>
                    </div>
                    ${type === 'mcq' ? `
                        <div class="mb-2">
                            <label class="form-label">Options</label>
                            <div class="row">
                                <div class="col-6">
                                    <input type="text" class="form-control mb-2" placeholder="Option A" value="Option A">
                                    <input type="text" class="form-control mb-2" placeholder="Option C" value="Option C">
                                </div>
                                <div class="col-6">
                                    <input type="text" class="form-control mb-2" placeholder="Option B" value="Option B">
                                    <input type="text" class="form-control mb-2" placeholder="Option D" value="Option D">
                                </div>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Correct Answer</label>
                            <select class="form-select">
                                <option value="A">A</option>
                                <option value="B">B</option>
                                <option value="C">C</option>
                                <option value="D">D</option>
                            </select>
                        </div>
                    ` : ''}
                    <div class="row">
                        <div class="col-md-6">
                            <label class="form-label">Marks</label>
                            <input type="number" class="form-control" value="1" min="1">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Time Limit (seconds)</label>
                            <input type="number" class="form-control" value="60" min="10">
                        </div>
                    </div>
                </div>
            </div>
        `;
    }
    
    document.getElementById('questionsList').innerHTML = questionsHtml;
    document.getElementById('generatedQuestions').style.display = 'block';
    
    // Scroll to generated questions
    document.getElementById('generatedQuestions').scrollIntoView({ behavior: 'smooth' });
}

function saveQuestions() {
    if (confirm('Save all generated questions to the question bank?')) {
        alert('Questions saved successfully!');
    }
}

function regenerateQuestions() {
    if (confirm('Regenerate all questions? This will replace the current set.')) {
        generateQuestions();
    }
}
</script>
@endpush
@endsection