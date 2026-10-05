@extends('layouts.app')

@section('title', 'Staff Documents')

@section('content')
<div class="container-fluid">
    <!-- Page Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-0 text-gray-800">
                <i class="fas fa-user-tie text-primary me-2"></i>
                Staff Documents
            </h1>
            <p class="text-muted">Generate staff ID cards and employment documents</p>
        </div>
        <div class="d-flex gap-2">
            <button class="btn btn-outline-primary" onclick="bulkGenerateStaffCards()">
                <i class="fas fa-download me-1"></i> Bulk Generate ID Cards
            </button>
            <button class="btn btn-outline-info" onclick="previewStaffTemplates()">
                <i class="fas fa-eye me-1"></i> Preview Templates
            </button>
        </div>
    </div>

    <!-- Staff ID Cards Section -->
    <div class="card shadow mb-4">
        <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between" style="background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);">
            <h6 class="m-0 font-weight-bold text-white">
                <i class="fas fa-id-badge me-2"></i>
                Staff ID Cards
            </h6>
            <span class="badge bg-light text-dark">{{ $staff->count() }} Staff Available</span>
        </div>
        <div class="card-body">
            @if($staff->count() > 0)
                <div class="table-responsive">
                    <table class="table table-bordered" width="100%" cellspacing="0">
                        <thead>
                            <tr>
                                <th>Staff Member</th>
                                <th>Department</th>
                                <th>Position</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($staff->take(15) as $staffMember)
                            <tr>
                                <td>
                                    <div class="d-flex align-items-center">
                                        @if($staffMember->photo)
                                            <img src="{{ asset('storage/' . $staffMember->photo) }}" 
                                                 class="rounded-circle me-2" 
                                                 width="32" height="32" 
                                                 alt="Staff Photo">
                                        @else
                                            <div class="rounded-circle bg-secondary me-2 d-flex align-items-center justify-content-center" 
                                                 style="width: 32px; height: 32px;">
                                                <i class="fas fa-user-tie text-white"></i>
                                            </div>
                                        @endif
                                        <div>
                                            <div class="font-weight-bold">{{ $staffMember->name }}</div>
                                            <small class="text-muted">{{ $staffMember->employee_id ?? 'N/A' }}</small>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <div class="font-weight-bold">{{ $staffMember->department ?? 'N/A' }}</div>
                                    <small class="text-muted">{{ $staffMember->designation ?? 'N/A' }}</small>
                                </td>
                                <td>{{ $staffMember->position ?? 'N/A' }}</td>
                                <td>
                                    @if($staffMember->status === 'active')
                                        <span class="badge bg-success">Active</span>
                                    @else
                                        <span class="badge bg-warning">{{ ucfirst($staffMember->status ?? 'Unknown') }}</span>
                                    @endif
                                </td>
                                <td>
                                    <div class="btn-group" role="group">
                                        <a href="{{ route('documents.staff.id-card', $staffMember->id) }}" 
                                           class="btn btn-sm btn-primary" 
                                           title="Generate ID Card">
                                            <i class="fas fa-id-badge"></i>
                                        </a>
                                        <button class="btn btn-sm btn-outline-info" 
                                                onclick="previewStaffIdCard({{ $staffMember->id }})" 
                                                title="Preview">
                                            <i class="fas fa-eye"></i>
                                        </button>
                                        <button class="btn btn-sm btn-outline-success" 
                                                onclick="generateEmploymentLetter({{ $staffMember->id }})" 
                                                title="Employment Letter">
                                            <i class="fas fa-file-contract"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                @if($staff->count() > 15)
                    <div class="text-center mt-3">
                        <a href="#" class="btn btn-outline-primary">View All {{ $staff->count() }} Staff</a>
                    </div>
                @endif
            @else
                <div class="text-center py-4">
                    <i class="fas fa-user-tie fa-3x text-muted mb-3"></i>
                    <h5 class="text-muted">No Staff Available</h5>
                    <p class="text-muted">No staff members have been registered yet.</p>
                </div>
            @endif
        </div>
    </div>

    <!-- Employment Documents Section -->
    <div class="card shadow mb-4">
        <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between" style="background: linear-gradient(135deg, #43e97b 0%, #38f9d7 100%);">
            <h6 class="m-0 font-weight-bold text-white">
                <i class="fas fa-file-contract me-2"></i>
                Employment Documents
            </h6>
            <span class="badge bg-light text-dark">Generate Employment Letters</span>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-6">
                    <div class="card border-primary">
                        <div class="card-body text-center">
                            <i class="fas fa-file-signature fa-3x text-primary mb-3"></i>
                            <h5 class="card-title">Employment Letter</h5>
                            <p class="card-text">Generate official employment letters for staff members with terms and conditions.</p>
                            <button class="btn btn-primary" onclick="showEmploymentModal()">
                                <i class="fas fa-plus me-1"></i> Generate Letter
                            </button>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="card border-success">
                        <div class="card-body text-center">
                            <i class="fas fa-handshake fa-3x text-success mb-3"></i>
                            <h5 class="card-title">Contract Agreement</h5>
                            <p class="card-text">Create employment contract agreements with detailed terms and conditions.</p>
                            <button class="btn btn-success" onclick="showContractModal()">
                                <i class="fas fa-plus me-1"></i> Generate Contract
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
                                Total Staff
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $staff->count() }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-user-tie fa-2x text-gray-300"></i>
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
                                Active Staff
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $staff->where('status', 'active')->count() }}</div>
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
                            <i class="fas fa-id-badge fa-2x text-gray-300"></i>
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
                                Letters Generated
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">0</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-file-contract fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Employment Letter Modal -->
<div class="modal fade" id="employmentModal" tabindex="-1" aria-labelledby="employmentModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="employmentModalLabel">Generate Employment Letter</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="employmentForm">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="staff_name" class="form-label">Staff Name</label>
                                <input type="text" class="form-control" id="staff_name" name="staff_name" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="position" class="form-label">Position</label>
                                <input type="text" class="form-control" id="position" name="position" required>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="department" class="form-label">Department</label>
                                <input type="text" class="form-control" id="department" name="department" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="start_date" class="form-label">Start Date</label>
                                <input type="date" class="form-control" id="start_date" name="start_date" required>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="salary" class="form-label">Salary</label>
                                <input type="text" class="form-control" id="salary" name="salary" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="employment_type" class="form-label">Employment Type</label>
                                <select class="form-select" id="employment_type" name="employment_type" required>
                                    <option value="">Select Type</option>
                                    <option value="Full-time">Full-time</option>
                                    <option value="Part-time">Part-time</option>
                                    <option value="Contract">Contract</option>
                                    <option value="Temporary">Temporary</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="letter_date" class="form-label">Letter Date</label>
                        <input type="date" class="form-control" id="letter_date" name="letter_date" value="{{ date('Y-m-d') }}" required>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary" onclick="generateEmploymentLetterFromForm()">
                    <i class="fas fa-download me-1"></i> Generate Letter
                </button>
            </div>
        </div>
    </div>
</div>

<!-- JavaScript for Actions -->
<script>
function bulkGenerateStaffCards() {
    // Navigate to bulk generation with staff ID cards selected
    const modal = document.getElementById('bulkGenerateModal');
    if (modal) {
        document.getElementById('bulkDocumentType').value = 'staff_id_cards';
        document.getElementById('bulkSelection').value = 'all_staff';
        var bootstrapModal = new bootstrap.Modal(modal);
        bootstrapModal.show();
    } else {
        // Fallback: redirect to dashboard with bulk generation
        window.location.href = '{{ route("documents.index") }}';
    }
}

function previewStaffTemplates() {
    // Open template preview in new window
    window.open('{{ route("documents.templates.preview", "staff_id_card") }}', '_blank');
}

function previewStaffIdCard(staffId) {
    // Implement staff ID card preview
    window.open(`{{ route('documents.templates.preview', 'staff_id_card') }}?staff_id=${staffId}`, '_blank');
}

function generateEmploymentLetter(staffId) {
    // Show employment letter modal
    showEmploymentModal();
}

function showEmploymentModal() {
    // Show employment letter modal
    var modal = new bootstrap.Modal(document.getElementById('employmentModal'));
    modal.show();
}

function showContractModal() {
    // Show contract modal
    var modal = new bootstrap.Modal(document.getElementById('contractModal'));
    modal.show();
}

function generateEmploymentLetterFromForm() {
    // Generate employment letter from form data
    var form = document.getElementById('employmentForm');
    var formData = new FormData(form);
    
    // For now, show a success message
    alert('Employment letter generation feature will be implemented soon!');
    
    // Close the modal
    var modal = bootstrap.Modal.getInstance(document.getElementById('employmentModal'));
    if (modal) {
        modal.hide();
    }
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
