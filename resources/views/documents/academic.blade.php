@extends('layouts.app')

@section('title', 'Academic Documents')

@section('content')
<div class="container-fluid">
    <!-- Page Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-0 text-gray-800">
                <i class="fas fa-graduation-cap text-primary me-2"></i>
                Academic Documents
            </h1>
            <p class="text-muted">Generate result slips and exam cards for students</p>
        </div>
        <div class="d-flex gap-2">
            <button class="btn btn-outline-primary" onclick="bulkGenerateAcademic()">
                <i class="fas fa-download me-1"></i> Bulk Generate
            </button>
            <button class="btn btn-outline-info" onclick="previewAcademicTemplates()">
                <i class="fas fa-eye me-1"></i> Preview Templates
            </button>
        </div>
    </div>

    <!-- Result Slips Section -->
    <div class="card shadow mb-4">
        <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
            <h6 class="m-0 font-weight-bold text-white">
                <i class="fas fa-file-alt me-2"></i>
                Result Slips
            </h6>
            <span class="badge bg-light text-dark">{{ $results->count() }} Results Available</span>
        </div>
        <div class="card-body">
            @if($results->count() > 0)
                <div class="table-responsive">
                    <table class="table table-bordered" width="100%" cellspacing="0">
                        <thead>
                            <tr>
                                <th>Student</th>
                                <th>Exam</th>
                                <th>Class</th>
                                <th>Date</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($results->take(10) as $result)
                            <tr>
                                <td>
                                    <div class="d-flex align-items-center">
                                        @if($result->student->passport)
                                            <img src="{{ asset('storage/' . $result->student->passport) }}" 
                                                 class="rounded-circle me-2" 
                                                 width="32" height="32" 
                                                 alt="Student Photo">
                                        @else
                                            <div class="rounded-circle bg-secondary me-2 d-flex align-items-center justify-content-center" 
                                                 style="width: 32px; height: 32px;">
                                                <i class="fas fa-user text-white"></i>
                                            </div>
                                        @endif
                                        <div>
                                            <div class="font-weight-bold">{{ $result->student->name }}</div>
                                            <small class="text-muted">{{ $result->student->admission_number }}</small>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <div class="font-weight-bold">{{ $result->exam->name ?? 'N/A' }}</div>
                                    <small class="text-muted">{{ $result->exam->term ?? 'N/A' }}</small>
                                </td>
                                <td>{{ $result->student->class->name ?? 'N/A' }}</td>
                                <td>{{ $result->created_at->format('M d, Y') }}</td>
                                <td>
                                    @if($result->grade)
                                        <span class="badge bg-success">{{ $result->grade }}</span>
                                    @else
                                        <span class="badge bg-warning">Pending</span>
                                    @endif
                                </td>
                                <td>
                                    <div class="btn-group" role="group">
                                        <a href="{{ route('documents.results.slip', $result->id) }}" 
                                           class="btn btn-sm btn-primary" 
                                           title="Generate Result Slip">
                                            <i class="fas fa-file-pdf"></i>
                                        </a>
                                        <button class="btn btn-sm btn-outline-info" 
                                                onclick="previewResultSlip({{ $result->id }})" 
                                                title="Preview">
                                            <i class="fas fa-eye"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                @if($results->count() > 10)
                    <div class="text-center mt-3">
                        <a href="#" class="btn btn-outline-primary">View All {{ $results->count() }} Results</a>
                    </div>
                @endif
            @else
                <div class="text-center py-4">
                    <i class="fas fa-file-alt fa-3x text-muted mb-3"></i>
                    <h5 class="text-muted">No Results Available</h5>
                    <p class="text-muted">No exam results have been recorded yet.</p>
                </div>
            @endif
        </div>
    </div>

    <!-- Exam Cards Section -->
    <div class="card shadow mb-4">
        <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between" style="background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);">
            <h6 class="m-0 font-weight-bold text-white">
                <i class="fas fa-ticket-alt me-2"></i>
                Exam Cards
            </h6>
            <span class="badge bg-light text-dark">{{ $students->count() }} Students Available</span>
        </div>
        <div class="card-body">
            @if($students->count() > 0)
                <div class="table-responsive">
                    <table class="table table-bordered" width="100%" cellspacing="0">
                        <thead>
                            <tr>
                                <th>Student</th>
                                <th>Class</th>
                                <th>Admission No</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($students->take(10) as $student)
                            <tr>
                                <td>
                                    <div class="d-flex align-items-center">
                                        @if($student->passport)
                                            <img src="{{ asset('storage/' . $student->passport) }}" 
                                                 class="rounded-circle me-2" 
                                                 width="32" height="32" 
                                                 alt="Student Photo">
                                        @else
                                            <div class="rounded-circle bg-secondary me-2 d-flex align-items-center justify-content-center" 
                                                 style="width: 32px; height: 32px;">
                                                <i class="fas fa-user text-white"></i>
                                            </div>
                                        @endif
                                        <div>
                                            <div class="font-weight-bold">{{ $student->name }}</div>
                                            <small class="text-muted">{{ $student->gender ?? 'N/A' }}</small>
                                        </div>
                                    </div>
                                </td>
                                <td>{{ $student->class->name ?? 'N/A' }}</td>
                                <td>{{ $student->admission_number }}</td>
                                <td>
                                    <span class="badge bg-success">Active</span>
                                </td>
                                <td>
                                    <div class="btn-group" role="group">
                                        <button class="btn btn-sm btn-primary" 
                                                onclick="showExamSelectionModal({{ $student->id }})" 
                                                title="Generate Exam Card">
                                            <i class="fas fa-ticket-alt"></i>
                                        </button>
                                        <button class="btn btn-sm btn-outline-info" 
                                                onclick="previewExamCard({{ $student->id }})" 
                                                title="Preview">
                                            <i class="fas fa-eye"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                @if($students->count() > 10)
                    <div class="text-center mt-3">
                        <a href="#" class="btn btn-outline-primary">View All {{ $students->count() }} Students</a>
                    </div>
                @endif
            @else
                <div class="text-center py-4">
                    <i class="fas fa-user-graduate fa-3x text-muted mb-3"></i>
                    <h5 class="text-muted">No Students Available</h5>
                    <p class="text-muted">No students have been enrolled yet.</p>
                </div>
            @endif
        </div>
    </div>

    <!-- Quick Stats -->
    <div class="row">
        <div class="col-md-4">
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                Total Students
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $students->count() }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-user-graduate fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card border-left-success shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                Exam Results
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $results->count() }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-chart-line fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card border-left-info shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                                Documents Generated
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">0</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-file-pdf fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Exam Selection Modal -->
<div class="modal fade" id="examSelectionModal" tabindex="-1" aria-labelledby="examSelectionModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="examSelectionModalLabel">Select Exam for Exam Card</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p>Select an exam to generate the exam card for:</p>
                <div class="mb-3">
                    <label for="examSelect" class="form-label">Exam:</label>
                    <select class="form-select" id="examSelect">
                        <option value="">Choose an exam...</option>
                        @foreach($exams as $exam)
                            <option value="{{ $exam->id }}">{{ $exam->name }} ({{ $exam->term ?? 'N/A' }} - {{ $exam->academic_year ?? 'N/A' }})</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary" onclick="generateExamCard()">Generate Exam Card</button>
            </div>
        </div>
    </div>
</div>

<!-- JavaScript for Actions -->
<script>
function bulkGenerateAcademic() {
    // Redirect to bulk generation
    window.location.href = '{{ route("documents.bulk.generate") }}?type=academic';
}

function previewAcademicTemplates() {
    // Open template preview modal
    window.open('{{ route("documents.templates.preview", "result_slip") }}', '_blank');
}

function previewResultSlip(resultId) {
    // Implement result slip preview
    window.open(`{{ route('documents.templates.preview', 'result_slip') }}?result_id=${resultId}`, '_blank');
}

let selectedStudentId = null;

function showExamSelectionModal(studentId) {
    selectedStudentId = studentId;
    const modal = new bootstrap.Modal(document.getElementById('examSelectionModal'));
    modal.show();
}

function generateExamCard() {
    const examId = document.getElementById('examSelect').value;
    if (!examId) {
        alert('Please select an exam first.');
        return;
    }
    
    if (!selectedStudentId) {
        alert('No student selected.');
        return;
    }
    
    // Generate the exam card PDF
    window.location.href = `{{ route('documents.exam.card', ['student' => ':studentId', 'exam' => ':examId']) }}`
        .replace(':studentId', selectedStudentId)
        .replace(':examId', examId);
    
    // Close modal
    const modal = bootstrap.Modal.getInstance(document.getElementById('examSelectionModal'));
    if (modal) {
        modal.hide();
    }
}

function previewExamCard(studentId) {
    // Open exam card preview
    window.open(`{{ route('documents.templates.preview', 'exam_card') }}?student_id=${studentId}`, '_blank');
}
</script>

<style>
.border-left-primary {
    border-left: 0.25rem solid #4e73df !important;
}

.border-left-success {
    border-left: 0.25rem solid #1cc88a !important;
}

.border-left-info {
    border-left: 0.25rem solid #36b9cc !important;
}

.card-header {
    border-bottom: none;
}
</style>
@endsection
