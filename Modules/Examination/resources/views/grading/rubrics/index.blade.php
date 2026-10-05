@extends('layouts.app')

@section('title', 'Grading Rubrics')

@section('content')
<div class="container-xl py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="fw-bold mb-1" style="color:#1a237e;">Grading Rubrics</h2>
            <p class="text-muted mb-0">Manage grading rubrics for consistent evaluation</p>
        </div>
        <div>
            <a href="{{ route('examination.grading.rubrics.create') }}" class="btn btn-primary">
                <i class="fas fa-plus me-2"></i>Create Rubric
            </a>
            <a href="{{ route('examination.grading.dashboard') }}" class="btn btn-outline-secondary">
                <i class="fas fa-arrow-left me-2"></i>Back to Dashboard
            </a>
        </div>
    </div>

    <!-- Rubrics List -->
    <div class="card">
        <div class="card-header">
            <h5 class="mb-0">
                <i class="fas fa-list-check me-2"></i>Grading Rubrics
            </h5>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Subject</th>
                            <th>Question Type</th>
                            <th>Criteria</th>
                            <th>Total Points</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>
                                <div>
                                    <div class="fw-bold">Mathematics Essay Rubric</div>
                                    <small class="text-muted">For mathematical problem-solving questions</small>
                                </div>
                            </td>
                            <td>Mathematics</td>
                            <td><span class="badge bg-info">Essay</span></td>
                            <td>4</td>
                            <td>20</td>
                            <td><span class="badge bg-success">Active</span></td>
                            <td>
                                <button class="btn btn-sm btn-outline-primary" onclick="viewRubric(1)">
                                    <i class="fas fa-eye"></i>
                                </button>
                                <button class="btn btn-sm btn-outline-warning" onclick="editRubric(1)">
                                    <i class="fas fa-edit"></i>
                                </button>
                                <button class="btn btn-sm btn-outline-danger" onclick="deleteRubric(1)">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <div>
                                    <div class="fw-bold">Physics Lab Report Rubric</div>
                                    <small class="text-muted">For laboratory experiment reports</small>
                                </div>
                            </td>
                            <td>Physics</td>
                            <td><span class="badge bg-info">Report</span></td>
                            <td>5</td>
                            <td>25</td>
                            <td><span class="badge bg-success">Active</span></td>
                            <td>
                                <button class="btn btn-sm btn-outline-primary" onclick="viewRubric(2)">
                                    <i class="fas fa-eye"></i>
                                </button>
                                <button class="btn btn-sm btn-outline-warning" onclick="editRubric(2)">
                                    <i class="fas fa-edit"></i>
                                </button>
                                <button class="btn btn-sm btn-outline-danger" onclick="deleteRubric(2)">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <div>
                                    <div class="fw-bold">Chemistry Short Answer Rubric</div>
                                    <small class="text-muted">For short answer questions</small>
                                </div>
                            </td>
                            <td>Chemistry</td>
                            <td><span class="badge bg-info">Short Answer</span></td>
                            <td>3</td>
                            <td>15</td>
                            <td><span class="badge bg-warning">Draft</span></td>
                            <td>
                                <button class="btn btn-sm btn-outline-primary" onclick="viewRubric(3)">
                                    <i class="fas fa-eye"></i>
                                </button>
                                <button class="btn btn-sm btn-outline-warning" onclick="editRubric(3)">
                                    <i class="fas fa-edit"></i>
                                </button>
                                <button class="btn btn-sm btn-outline-danger" onclick="deleteRubric(3)">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- View Rubric Modal -->
<div class="modal fade" id="viewRubricModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Rubric Details</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <h6>Mathematics Essay Rubric</h6>
                <p class="text-muted">For mathematical problem-solving questions</p>
                
                <div class="table-responsive">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>Criteria</th>
                                <th>Excellent (5)</th>
                                <th>Good (4)</th>
                                <th>Satisfactory (3)</th>
                                <th>Needs Improvement (2)</th>
                                <th>Poor (1)</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td><strong>Problem Understanding</strong></td>
                                <td>Clearly identifies the problem and all requirements</td>
                                <td>Identifies most requirements</td>
                                <td>Basic understanding shown</td>
                                <td>Limited understanding</td>
                                <td>No clear understanding</td>
                            </tr>
                            <tr>
                                <td><strong>Solution Method</strong></td>
                                <td>Uses appropriate method with clear steps</td>
                                <td>Method mostly correct</td>
                                <td>Reasonable approach</td>
                                <td>Method unclear</td>
                                <td>No clear method</td>
                            </tr>
                            <tr>
                                <td><strong>Mathematical Accuracy</strong></td>
                                <td>All calculations correct</td>
                                <td>Minor errors only</td>
                                <td>Some calculation errors</td>
                                <td>Multiple errors</td>
                                <td>Mostly incorrect</td>
                            </tr>
                            <tr>
                                <td><strong>Explanation</strong></td>
                                <td>Clear, detailed explanation</td>
                                <td>Good explanation</td>
                                <td>Adequate explanation</td>
                                <td>Limited explanation</td>
                                <td>No explanation</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" onclick="editRubric(1)">Edit Rubric</button>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
function viewRubric(rubricId) {
    const modal = new bootstrap.Modal(document.getElementById('viewRubricModal'));
    modal.show();
}

function editRubric(rubricId) {
    alert('Edit rubric ' + rubricId + ' - Feature coming soon!');
}

function deleteRubric(rubricId) {
    if (confirm('Are you sure you want to delete this rubric?')) {
        alert('Rubric deleted successfully!');
    }
}
</script>
@endpush
@endsection
