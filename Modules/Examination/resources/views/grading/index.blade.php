@extends('layouts.app')

@section('title', 'Grade Questions')

@section('content')
<div class="container-xl py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="fw-bold mb-1" style="color:#1a237e;">Grade Questions</h2>
            <p class="text-muted mb-0">Review and grade student answers</p>
        </div>
        <div>
            <a href="{{ route('examination.grading.dashboard') }}" class="btn btn-outline-secondary">
                <i class="fas fa-arrow-left me-2"></i>Back to Dashboard
            </a>
        </div>
    </div>

    <!-- Filters -->
    <div class="card mb-4">
        <div class="card-body">
            <form class="row g-3">
                <div class="col-md-3">
                    <label class="form-label">Exam</label>
                    <select class="form-select" name="exam_id">
                        <option value="">All Exams</option>
                        <option value="1">Mathematics Midterm</option>
                        <option value="2">Physics Final</option>
                        <option value="3">Chemistry Quiz</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label">Status</label>
                    <select class="form-select" name="status">
                        <option value="">All Status</option>
                        <option value="pending">Pending</option>
                        <option value="graded">Graded</option>
                        <option value="disputed">Disputed</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label">Question Type</label>
                    <select class="form-select" name="question_type">
                        <option value="">All Types</option>
                        <option value="essay">Essay</option>
                        <option value="short_answer">Short Answer</option>
                        <option value="coding">Coding</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label">&nbsp;</label>
                    <div class="d-grid">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-search me-2"></i>Filter
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Grading List -->
    <div class="card">
        <div class="card-header">
            <h5 class="mb-0">
                <i class="fas fa-list me-2"></i>Questions to Grade
            </h5>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>Student</th>
                            <th>Exam</th>
                            <th>Question</th>
                            <th>Type</th>
                            <th>Status</th>
                            <th>Submitted</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>
                                <div class="d-flex align-items-center">
                                    <div class="avatar-sm bg-primary text-white rounded-circle me-2 d-flex align-items-center justify-content-center">
                                        JD
                                    </div>
                                    <div>
                                        <div class="fw-bold">John Doe</div>
                                        <small class="text-muted">ID: 12345</small>
                                    </div>
                                </div>
                            </td>
                            <td>Mathematics Midterm</td>
                            <td>
                                <div class="question-preview">
                                    <strong>Q1:</strong> Solve the quadratic equation x² + 5x + 6 = 0
                                </div>
                            </td>
                            <td><span class="badge bg-info">Essay</span></td>
                            <td><span class="badge bg-warning">Pending</span></td>
                            <td>2 hours ago</td>
                            <td>
                                <button class="btn btn-sm btn-primary" onclick="gradeQuestion(1)">
                                    <i class="fas fa-edit"></i> Grade
                                </button>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <div class="d-flex align-items-center">
                                    <div class="avatar-sm bg-success text-white rounded-circle me-2 d-flex align-items-center justify-content-center">
                                        JS
                                    </div>
                                    <div>
                                        <div class="fw-bold">Jane Smith</div>
                                        <small class="text-muted">ID: 12346</small>
                                    </div>
                                </div>
                            </td>
                            <td>Physics Final</td>
                            <td>
                                <div class="question-preview">
                                    <strong>Q3:</strong> Explain the concept of momentum conservation
                                </div>
                            </td>
                            <td><span class="badge bg-info">Short Answer</span></td>
                            <td><span class="badge bg-success">Graded</span></td>
                            <td>4 hours ago</td>
                            <td>
                                <button class="btn btn-sm btn-outline-primary" onclick="viewGrade(2)">
                                    <i class="fas fa-eye"></i> View
                                </button>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <div class="d-flex align-items-center">
                                    <div class="avatar-sm bg-warning text-dark rounded-circle me-2 d-flex align-items-center justify-content-center">
                                        MJ
                                    </div>
                                    <div>
                                        <div class="fw-bold">Mike Johnson</div>
                                        <small class="text-muted">ID: 12347</small>
                                    </div>
                                </div>
                            </td>
                            <td>Chemistry Quiz</td>
                            <td>
                                <div class="question-preview">
                                    <strong>Q2:</strong> Write a Python function to calculate factorial
                                </div>
                            </td>
                            <td><span class="badge bg-info">Coding</span></td>
                            <td><span class="badge bg-danger">Disputed</span></td>
                            <td>1 day ago</td>
                            <td>
                                <button class="btn btn-sm btn-warning" onclick="resolveDispute(3)">
                                    <i class="fas fa-exclamation-triangle"></i> Resolve
                                </button>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Grade Question Modal -->
<div class="modal fade" id="gradeModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Grade Question</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="mb-3">
                    <label class="form-label">Question</label>
                    <div class="border p-3 bg-light">
                        <strong>Q1:</strong> Solve the quadratic equation x² + 5x + 6 = 0
                    </div>
                </div>
                <div class="mb-3">
                    <label class="form-label">Student Answer</label>
                    <div class="border p-3 bg-light">
                        <p>To solve x² + 5x + 6 = 0, I can use the quadratic formula or factor the equation.</p>
                        <p>Factoring: (x + 2)(x + 3) = 0</p>
                        <p>Therefore, x = -2 or x = -3</p>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <label class="form-label">Marks Obtained</label>
                        <input type="number" class="form-control" value="8" max="10">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Total Marks</label>
                        <input type="number" class="form-control" value="10" readonly>
                    </div>
                </div>
                <div class="mb-3">
                    <label class="form-label">Feedback</label>
                    <textarea class="form-control" rows="3" placeholder="Provide feedback to the student...">Good work! You correctly factored the equation and found both solutions. Consider showing more steps for clarity.</textarea>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary" onclick="saveGrade()">Save Grade</button>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
function gradeQuestion(questionId) {
    // Show grade modal
    const modal = new bootstrap.Modal(document.getElementById('gradeModal'));
    modal.show();
}

function viewGrade(questionId) {
    alert('View grade details for question ' + questionId);
}

function resolveDispute(questionId) {
    if (confirm('Resolve dispute for question ' + questionId + '?')) {
        alert('Dispute resolved successfully!');
    }
}

function saveGrade() {
    // Close modal and show success message
    const modal = bootstrap.Modal.getInstance(document.getElementById('gradeModal'));
    modal.hide();
    alert('Grade saved successfully!');
}
</script>
@endpush
@endsection
