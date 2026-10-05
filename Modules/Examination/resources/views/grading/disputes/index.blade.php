@extends('layouts.app')

@section('title', 'Grade Disputes')

@section('content')
<div class="container-xl py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="fw-bold mb-1" style="color:#1a237e;">Grade Disputes</h2>
            <p class="text-muted mb-0">Manage grade disputes and appeals</p>
        </div>
        <div>
            <a href="{{ route('examination.grading.dashboard') }}" class="btn btn-outline-secondary">
                <i class="fas fa-arrow-left me-2"></i>Back to Dashboard
            </a>
        </div>
    </div>

    <!-- Dispute Statistics -->
    <div class="row mb-4">
        <div class="col-md-3 mb-3">
            <div class="card bg-danger text-white">
                <div class="card-body text-center">
                    <h3 class="mb-1">{{ $stats['pending_disputes'] ?? 0 }}</h3>
                    <p class="mb-0">Pending Disputes</p>
                </div>
            </div>
        </div>
        <div class="col-md-3 mb-3">
            <div class="card bg-warning text-dark">
                <div class="card-body text-center">
                    <h3 class="mb-1">{{ $stats['under_review'] ?? 0 }}</h3>
                    <p class="mb-0">Under Review</p>
                </div>
            </div>
        </div>
        <div class="col-md-3 mb-3">
            <div class="card bg-success text-white">
                <div class="card-body text-center">
                    <h3 class="mb-1">{{ $stats['resolved'] ?? 0 }}</h3>
                    <p class="mb-0">Resolved</p>
                </div>
            </div>
        </div>
        <div class="col-md-3 mb-3">
            <div class="card bg-info text-white">
                <div class="card-body text-center">
                    <h3 class="mb-1">{{ $stats['total_disputes'] ?? 0 }}</h3>
                    <p class="mb-0">Total Disputes</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Filters -->
    <div class="card mb-4">
        <div class="card-body">
            <form class="row g-3">
                <div class="col-md-3">
                    <label class="form-label">Status</label>
                    <select class="form-select" name="status">
                        <option value="">All Status</option>
                        <option value="pending">Pending</option>
                        <option value="under_review">Under Review</option>
                        <option value="resolved">Resolved</option>
                        <option value="rejected">Rejected</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label">Priority</label>
                    <select class="form-select" name="priority">
                        <option value="">All Priorities</option>
                        <option value="high">High</option>
                        <option value="medium">Medium</option>
                        <option value="low">Low</option>
                    </select>
                </div>
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

    <!-- Disputes List -->
    <div class="card">
        <div class="card-header">
            <h5 class="mb-0">
                <i class="fas fa-exclamation-triangle me-2"></i>Grade Disputes
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
                            <th>Current Grade</th>
                            <th>Dispute Reason</th>
                            <th>Status</th>
                            <th>Submitted</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>
                                <div class="d-flex align-items-center">
                                    <div class="avatar-sm bg-danger text-white rounded-circle me-2 d-flex align-items-center justify-content-center">
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
                                    <strong>Q3:</strong> Solve the equation...
                                </div>
                            </td>
                            <td>
                                <span class="text-danger fw-bold">6/10</span>
                            </td>
                            <td>
                                <span class="badge bg-warning">Grading Error</span>
                            </td>
                            <td><span class="badge bg-danger">Pending</span></td>
                            <td>2 hours ago</td>
                            <td>
                                <button class="btn btn-sm btn-primary" onclick="reviewDispute(1)">
                                    <i class="fas fa-eye"></i> Review
                                </button>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <div class="d-flex align-items-center">
                                    <div class="avatar-sm bg-warning text-dark rounded-circle me-2 d-flex align-items-center justify-content-center">
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
                                    <strong>Q7:</strong> Explain the concept...
                                </div>
                            </td>
                            <td>
                                <span class="text-warning fw-bold">4/8</span>
                            </td>
                            <td>
                                <span class="badge bg-info">Partial Credit</span>
                            </td>
                            <td><span class="badge bg-warning">Under Review</span></td>
                            <td>4 hours ago</td>
                            <td>
                                <button class="btn btn-sm btn-warning" onclick="continueReview(2)">
                                    <i class="fas fa-edit"></i> Continue
                                </button>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <div class="d-flex align-items-center">
                                    <div class="avatar-sm bg-success text-white rounded-circle me-2 d-flex align-items-center justify-content-center">
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
                                    <strong>Q2:</strong> Calculate the molarity...
                                </div>
                            </td>
                            <td>
                                <span class="text-success fw-bold">8/10</span>
                            </td>
                            <td>
                                <span class="badge bg-success">Resolved</span>
                            </td>
                            <td><span class="badge bg-success">Resolved</span></td>
                            <td>1 day ago</td>
                            <td>
                                <button class="btn btn-sm btn-outline-primary" onclick="viewResolution(3)">
                                    <i class="fas fa-check"></i> View
                                </button>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Review Dispute Modal -->
<div class="modal fade" id="reviewModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Review Grade Dispute</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-6">
                        <h6>Original Question</h6>
                        <div class="border p-3 bg-light">
                            <strong>Q3:</strong> Solve the quadratic equation x² + 5x + 6 = 0
                        </div>
                    </div>
                    <div class="col-md-6">
                        <h6>Student's Answer</h6>
                        <div class="border p-3 bg-light">
                            <p>Using the quadratic formula: x = (-5 ± √(25-24))/2</p>
                            <p>x = (-5 ± 1)/2</p>
                            <p>Therefore: x = -2 or x = -3</p>
                        </div>
                    </div>
                </div>
                
                <div class="row mt-3">
                    <div class="col-md-6">
                        <h6>Original Grade</h6>
                        <div class="border p-3 bg-light">
                            <strong>6/10</strong>
                            <p class="mb-0">Feedback: Correct method but missing some steps</p>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <h6>Student's Dispute</h6>
                        <div class="border p-3 bg-light">
                            <p>"I believe I should get full marks as I used the correct method and got the right answer. The quadratic formula is a valid approach."</p>
                        </div>
                    </div>
                </div>

                <div class="mt-3">
                    <h6>Review Decision</h6>
                    <div class="row">
                        <div class="col-md-6">
                            <label class="form-label">New Grade</label>
                            <input type="number" class="form-control" value="8" max="10">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Decision</label>
                            <select class="form-select">
                                <option value="upgrade">Upgrade Grade</option>
                                <option value="maintain">Maintain Grade</option>
                                <option value="downgrade">Downgrade Grade</option>
                            </select>
                        </div>
                    </div>
                    <div class="mt-3">
                        <label class="form-label">Review Comments</label>
                        <textarea class="form-control" rows="3" placeholder="Explain your decision...">Upon review, the student used a valid method and obtained the correct answer. The quadratic formula is indeed acceptable. Upgrading to 8/10.</textarea>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-success" onclick="resolveDispute()">Resolve Dispute</button>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
function reviewDispute(disputeId) {
    const modal = new bootstrap.Modal(document.getElementById('reviewModal'));
    modal.show();
}

function continueReview(disputeId) {
    alert('Continue review for dispute ' + disputeId);
}

function viewResolution(disputeId) {
    alert('View resolution for dispute ' + disputeId);
}

function resolveDispute() {
    const modal = bootstrap.Modal.getInstance(document.getElementById('reviewModal'));
    modal.hide();
    alert('Dispute resolved successfully!');
}
</script>
@endpush
@endsection
