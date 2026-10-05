@extends('layouts.app')

@section('title', 'Student Documents')

@section('content')
<div class="container-fluid">
    <!-- Page Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-0 text-gray-800">
                <i class="fas fa-user-graduate text-primary me-2"></i>
                Student Documents
            </h1>
            <p class="text-muted">Generate student ID cards and certificates</p>
        </div>
        <div class="d-flex gap-2">
            <button class="btn btn-outline-primary" onclick="bulkGenerateStudentCards()">
                <i class="fas fa-download me-1"></i> Bulk Generate ID Cards
            </button>
            <button class="btn btn-outline-info" onclick="previewStudentTemplates()">
                <i class="fas fa-eye me-1"></i> Preview Templates
            </button>
        </div>
    </div>

    <!-- Student ID Cards Section -->
    <div class="card shadow mb-4">
        <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between" style="background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);">
            <h6 class="m-0 font-weight-bold text-white">
                <i class="fas fa-id-card me-2"></i>
                Student ID Cards
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
                            @foreach($students->take(15) as $student)
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
                                            <small class="text-muted">{{ $student->gender ?? 'N/A' }} • {{ $student->date_of_birth ? $student->date_of_birth->format('M d, Y') : 'N/A' }}</small>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <div class="font-weight-bold">{{ $student->class->name ?? 'N/A' }}</div>
                                    <small class="text-muted">{{ $student->stream ?? 'N/A' }}</small>
                                </td>
                                <td>{{ $student->admission_number }}</td>
                                <td>
                                    @if($student->status === 'active')
                                        <span class="badge bg-success">Active</span>
                                    @else
                                        <span class="badge bg-warning">{{ ucfirst($student->status ?? 'Unknown') }}</span>
                                    @endif
                                </td>
                                <td>
                                    <div class="btn-group" role="group">
                                        <a href="{{ route('documents.student.id-card', $student->id) }}" 
                                           class="btn btn-sm btn-primary" 
                                           title="Generate ID Card">
                                            <i class="fas fa-id-card"></i>
                                        </a>
                                        <button class="btn btn-sm btn-outline-info" 
                                                onclick="previewStudentIdCard({{ $student->id }})" 
                                                title="Preview">
                                            <i class="fas fa-eye"></i>
                                        </button>
                                        <button class="btn btn-sm btn-outline-success" 
                                                onclick="generateCertificate({{ $student->id }})" 
                                                title="Generate Certificate">
                                            <i class="fas fa-certificate"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                @if($students->count() > 15)
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

    <!-- Certificates Section -->
    <div class="card shadow mb-4">
        <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between" style="background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);">
            <h6 class="m-0 font-weight-bold text-white">
                <i class="fas fa-certificate me-2"></i>
                Certificates
            </h6>
            <span class="badge bg-light text-dark">Generate Academic Certificates</span>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-6">
                    <div class="card border-primary">
                        <div class="card-body text-center">
                            <i class="fas fa-graduation-cap fa-3x text-primary mb-3"></i>
                            <h5 class="card-title">Completion Certificate</h5>
                            <p class="card-text">Generate academic completion certificates for students who have finished their studies.</p>
                            <button class="btn btn-primary" onclick="showCertificateModal()">
                                <i class="fas fa-plus me-1"></i> Generate Certificate
                            </button>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="card border-success">
                        <div class="card-body text-center">
                            <i class="fas fa-award fa-3x text-success mb-3"></i>
                            <h5 class="card-title">Achievement Certificate</h5>
                            <p class="card-text">Create achievement certificates for outstanding performance and special accomplishments.</p>
                            <button class="btn btn-success" onclick="showAchievementModal()">
                                <i class="fas fa-plus me-1"></i> Generate Certificate
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Stats -->
    <div class="row">
        <div class="col-md-3">
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
        <div class="col-md-3">
            <div class="card border-left-success shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                Active Students
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $students->where('status', 'active')->count() }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-check-circle fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-left-info shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                                ID Cards Generated
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">0</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-id-card fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-left-warning shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                                Certificates Generated
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">0</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-certificate fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Certificate Generation Modal -->
<div class="modal fade" id="certificateModal" tabindex="-1" aria-labelledby="certificateModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="certificateModalLabel">Generate Certificate</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="certificateForm">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="student_name" class="form-label">Student Name</label>
                                <input type="text" class="form-control" id="student_name" name="student_name" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="course_name" class="form-label">Course Name</label>
                                <input type="text" class="form-control" id="course_name" name="course_name" required>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="program_name" class="form-label">Program Name</label>
                                <input type="text" class="form-control" id="program_name" name="program_name" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="duration" class="form-label">Duration</label>
                                <input type="text" class="form-control" id="duration" name="duration" required>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="grade" class="form-label">Grade</label>
                                <select class="form-select" id="grade" name="grade" required>
                                    <option value="">Select Grade</option>
                                    <option value="Distinction">Distinction</option>
                                    <option value="First Class">First Class</option>
                                    <option value="Second Class Upper">Second Class Upper</option>
                                    <option value="Second Class Lower">Second Class Lower</option>
                                    <option value="Pass">Pass</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="completion_date" class="form-label">Completion Date</label>
                                <input type="date" class="form-control" id="completion_date" name="completion_date" required>
                            </div>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="certificate_id" class="form-label">Certificate ID</label>
                        <input type="text" class="form-control" id="certificate_id" name="certificate_id" value="CERT-{{ strtoupper(uniqid()) }}" required>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary" onclick="generateCertificateFromForm()">
                    <i class="fas fa-download me-1"></i> Generate Certificate
                </button>
            </div>
        </div>
    </div>
</div>

<!-- JavaScript for Actions -->
<script>
function bulkGenerateStudentCards() {
    // Navigate to bulk generation with student ID cards selected
    const modal = document.getElementById('bulkGenerateModal');
    if (modal) {
        document.getElementById('bulkDocumentType').value = 'student_id_cards';
        document.getElementById('bulkSelection').value = 'all_students';
        var bootstrapModal = new bootstrap.Modal(modal);
        bootstrapModal.show();
    } else {
        // Fallback: redirect to dashboard with bulk generation
        window.location.href = '{{ route("documents.index") }}';
    }
}

function previewStudentTemplates() {
    // Open template preview in new window
    window.open('{{ route("documents.templates.preview", "student_id_card") }}', '_blank');
}

function previewStudentIdCard(studentId) {
    // Implement student ID card preview
    window.open(`{{ route('documents.templates.preview', 'student_id_card') }}?student_id=${studentId}`, '_blank');
}

function generateCertificate(studentId) {
    // Show certificate generation modal
    showCertificateModal();
}

function showCertificateModal() {
    // Show certificate generation modal
    var modal = new bootstrap.Modal(document.getElementById('certificateModal'));
    modal.show();
}

function showAchievementModal() {
    // Show achievement certificate modal
    var modal = new bootstrap.Modal(document.getElementById('achievementModal'));
    modal.show();
}

function generateCertificateFromForm() {
    // Generate certificate from form data
    var form = document.getElementById('certificateForm');
    var formData = new FormData(form);
    
    fetch('{{ route("documents.certificates.generate") }}', {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            'Content-Type': 'application/json',
        },
        body: JSON.stringify(Object.fromEntries(formData))
    })
    .then(response => response.blob())
    .then(blob => {
        var url = window.URL.createObjectURL(blob);
        var a = document.createElement('a');
        a.href = url;
        a.download = 'certificate.pdf';
        document.body.appendChild(a);
        a.click();
        window.URL.revokeObjectURL(url);
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Error generating certificate');
    });
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

.border-left-warning {
    border-left: 0.25rem solid #f6c23e !important;
}

.card-header {
    border-bottom: none;
}
</style>
@endsection
