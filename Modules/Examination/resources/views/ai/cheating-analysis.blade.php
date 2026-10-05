@extends('layouts.app')

@section('title', 'AI Cheating Analysis')

@section('content')
<div class="container-xl py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="fw-bold mb-1" style="color:#1a237e;">AI Cheating Analysis</h2>
            <p class="text-muted mb-0">Advanced AI-powered detection of suspicious behavior during exams</p>
        </div>
        <div>
            <button class="btn btn-warning" onclick="runBatchAnalysis()">
                <i class="fas fa-play me-2"></i>Run Batch Analysis
            </button>
            <a href="{{ route('examination.ai.dashboard') }}" class="btn btn-outline-secondary">
                <i class="fas fa-arrow-left me-2"></i>Back to AI Dashboard
            </a>
        </div>
    </div>

    <!-- Analysis Statistics -->
    <div class="row mb-4">
        <div class="col-md-3 mb-3">
            <div class="card bg-danger text-white">
                <div class="card-body text-center">
                    <h3 class="mb-1">12</h3>
                    <p class="mb-0">High Risk Cases</p>
                </div>
            </div>
        </div>
        <div class="col-md-3 mb-3">
            <div class="card bg-warning text-dark">
                <div class="card-body text-center">
                    <h3 class="mb-1">28</h3>
                    <p class="mb-0">Medium Risk Cases</p>
                </div>
            </div>
        </div>
        <div class="col-md-3 mb-3">
            <div class="card bg-success text-white">
                <div class="card-body text-center">
                    <h3 class="mb-1">156</h3>
                    <p class="mb-0">Clean Attempts</p>
                </div>
            </div>
        </div>
        <div class="col-md-3 mb-3">
            <div class="card bg-info text-white">
                <div class="card-body text-center">
                    <h3 class="mb-1">96.2%</h3>
                    <p class="mb-0">Accuracy Rate</p>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Analysis Controls -->
        <div class="col-lg-4">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">
                        <i class="fas fa-cogs me-2"></i>Analysis Controls
                    </h5>
                </div>
                <div class="card-body">
                    <form id="analysisForm">
                        <div class="mb-3">
                            <label class="form-label">Select Exam</label>
                            <select class="form-select" name="exam_id">
                                <option value="">All Exams</option>
                                <option value="1">Mathematics Midterm</option>
                                <option value="2">Physics Final</option>
                                <option value="3">Chemistry Quiz</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Date Range</label>
                            <div class="row">
                                <div class="col-6">
                                    <input type="date" class="form-control" name="start_date">
                                </div>
                                <div class="col-6">
                                    <input type="date" class="form-control" name="end_date">
                                </div>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Risk Level</label>
                            <select class="form-select" name="risk_level">
                                <option value="">All Levels</option>
                                <option value="high">High Risk</option>
                                <option value="medium">Medium Risk</option>
                                <option value="low">Low Risk</option>
                            </select>
                        </div>
                        <div class="d-grid">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-search me-2"></i>Analyze
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- AI Detection Methods -->
            <div class="card mt-3">
                <div class="card-header">
                    <h5 class="mb-0">
                        <i class="fas fa-robot me-2"></i>Detection Methods
                    </h5>
                </div>
                <div class="card-body">
                    <div class="form-check mb-2">
                        <input class="form-check-input" type="checkbox" id="tabSwitch" checked>
                        <label class="form-check-label" for="tabSwitch">
                            Tab Switching Detection
                        </label>
                    </div>
                    <div class="form-check mb-2">
                        <input class="form-check-input" type="checkbox" id="faceDetection" checked>
                        <label class="form-check-label" for="faceDetection">
                            Face Detection Analysis
                        </label>
                    </div>
                    <div class="form-check mb-2">
                        <input class="form-check-input" type="checkbox" id="eyeTracking" checked>
                        <label class="form-check-label" for="eyeTracking">
                            Eye Movement Tracking
                        </label>
                    </div>
                    <div class="form-check mb-2">
                        <input class="form-check-input" type="checkbox" id="keystrokeAnalysis">
                        <label class="form-check-label" for="keystrokeAnalysis">
                            Keystroke Pattern Analysis
                        </label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" id="behavioralAnalysis" checked>
                        <label class="form-check-label" for="behavioralAnalysis">
                            Behavioral Pattern Analysis
                        </label>
                    </div>
                </div>
            </div>
        </div>

        <!-- Analysis Results -->
        <div class="col-lg-8">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">
                        <i class="fas fa-list me-2"></i>Analysis Results
                    </h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Student</th>
                                    <th>Exam</th>
                                    <th>Risk Level</th>
                                    <th>Violations</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr class="table-danger">
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
                                    <td><span class="badge bg-danger">High Risk</span></td>
                                    <td>
                                        <span class="text-danger fw-bold">8</span>
                                        <small class="text-muted d-block">Tab switches, face not detected</small>
                                    </td>
                                    <td>
                                        <button class="btn btn-sm btn-outline-primary" onclick="viewDetails(1)">
                                            <i class="fas fa-eye"></i>
                                        </button>
                                        <button class="btn btn-sm btn-outline-warning" onclick="flagForReview(1)">
                                            <i class="fas fa-flag"></i>
                                        </button>
                                    </td>
                                </tr>
                                <tr class="table-warning">
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
                                    <td><span class="badge bg-warning">Medium Risk</span></td>
                                    <td>
                                        <span class="text-warning fw-bold">3</span>
                                        <small class="text-muted d-block">Multiple windows detected</small>
                                    </td>
                                    <td>
                                        <button class="btn btn-sm btn-outline-primary" onclick="viewDetails(2)">
                                            <i class="fas fa-eye"></i>
                                        </button>
                                        <button class="btn btn-sm btn-outline-warning" onclick="flagForReview(2)">
                                            <i class="fas fa-flag"></i>
                                        </button>
                                    </td>
                                </tr>
                                <tr class="table-success">
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
                                    <td><span class="badge bg-success">Low Risk</span></td>
                                    <td>
                                        <span class="text-success fw-bold">0</span>
                                        <small class="text-muted d-block">Clean attempt</small>
                                    </td>
                                    <td>
                                        <button class="btn btn-sm btn-outline-primary" onclick="viewDetails(3)">
                                            <i class="fas fa-eye"></i>
                                        </button>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
function runBatchAnalysis() {
    if (confirm('This will analyze all recent exam attempts. Continue?')) {
        // Show loading state
        const btn = event.target;
        const originalText = btn.innerHTML;
        btn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Analyzing...';
        btn.disabled = true;
        
        // Simulate analysis
        setTimeout(() => {
            btn.innerHTML = originalText;
            btn.disabled = false;
            alert('Batch analysis completed! Found 5 new suspicious activities.');
        }, 3000);
    }
}

function viewDetails(attemptId) {
    alert('Detailed analysis for attempt ' + attemptId + ' would be shown here.');
}

function flagForReview(attemptId) {
    if (confirm('Flag this attempt for manual review?')) {
        alert('Attempt ' + attemptId + ' has been flagged for review.');
    }
}

// Form submission
document.getElementById('analysisForm').addEventListener('submit', function(e) {
    e.preventDefault();
    alert('Analysis filters applied!');
});
</script>
@endpush
@endsection
