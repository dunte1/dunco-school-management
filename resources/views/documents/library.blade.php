@extends('layouts.app')

@section('title', 'Library Documents')

@section('content')
<div class="container-fluid">
    <!-- Page Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-0 text-gray-800">
                <i class="fas fa-book text-primary me-2"></i>
                Library Documents
            </h1>
            <p class="text-muted">Generate library cards and borrowing records</p>
        </div>
        <div class="d-flex gap-2">
            <button class="btn btn-outline-primary" onclick="bulkGenerateLibraryCards()">
                <i class="fas fa-download me-1"></i> Bulk Generate Cards
            </button>
            <button class="btn btn-outline-info" onclick="previewLibraryTemplates()">
                <i class="fas fa-eye me-1"></i> Preview Templates
            </button>
        </div>
    </div>

    <!-- Library Cards Section -->
    <div class="card shadow mb-4">
        <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between" style="background: linear-gradient(135deg, #43e97b 0%, #38f9d7 100%);">
            <h6 class="m-0 font-weight-bold text-white">
                <i class="fas fa-address-card me-2"></i>
                Library Cards
            </h6>
            <span class="badge bg-light text-dark">{{ $users->count() }} Users Available</span>
        </div>
        <div class="card-body">
            @if($users->count() > 0)
                <div class="table-responsive">
                    <table class="table table-bordered" width="100%" cellspacing="0">
                        <thead>
                            <tr>
                                <th>User</th>
                                <th>Type</th>
                                <th>ID Number</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($users->take(15) as $user)
                            <tr>
                                <td>
                                    <div class="d-flex align-items-center">
                                        @if($user->photo)
                                            <img src="{{ asset('storage/' . $user->photo) }}" 
                                                 class="rounded-circle me-2" 
                                                 width="32" height="32" 
                                                 alt="User Photo">
                                        @else
                                            <div class="rounded-circle bg-secondary me-2 d-flex align-items-center justify-content-center" 
                                                 style="width: 32px; height: 32px;">
                                                <i class="fas fa-user text-white"></i>
                                            </div>
                                        @endif
                                        <div>
                                            <div class="font-weight-bold">{{ $user->name }}</div>
                                            <small class="text-muted">{{ $user->email ?? 'N/A' }}</small>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    @if($user->hasRole('student'))
                                        <span class="badge bg-primary">Student</span>
                                    @elseif($user->hasRole('teacher'))
                                        <span class="badge bg-success">Teacher</span>
                                    @elseif($user->hasRole('staff'))
                                        <span class="badge bg-info">Staff</span>
                                    @else
                                        <span class="badge bg-secondary">User</span>
                                    @endif
                                </td>
                                <td>{{ $user->library_id ?? 'N/A' }}</td>
                                <td>
                                    @if($user->library_status === 'active')
                                        <span class="badge bg-success">Active</span>
                                    @else
                                        <span class="badge bg-warning">{{ ucfirst($user->library_status ?? 'Unknown') }}</span>
                                    @endif
                                </td>
                                <td>
                                    <div class="btn-group" role="group">
                                        <a href="{{ route('documents.library.card', $user->id) }}" 
                                           class="btn btn-sm btn-primary" 
                                           title="Generate Library Card">
                                            <i class="fas fa-address-card"></i>
                                        </a>
                                        <button class="btn btn-sm btn-outline-info" 
                                                onclick="previewLibraryCard({{ $user->id }})" 
                                                title="Preview">
                                            <i class="fas fa-eye"></i>
                                        </button>
                                        <button class="btn btn-sm btn-outline-success" 
                                                onclick="generateBorrowingRecord({{ $user->id }})" 
                                                title="Borrowing Record">
                                            <i class="fas fa-clipboard-list"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                @if($users->count() > 15)
                    <div class="text-center mt-3">
                        <a href="#" class="btn btn-outline-primary">View All {{ $users->count() }} Users</a>
                    </div>
                @endif
            @else
                <div class="text-center py-4">
                    <i class="fas fa-book fa-3x text-muted mb-3"></i>
                    <h5 class="text-muted">No Users Available</h5>
                    <p class="text-muted">No users have been registered for library access yet.</p>
                </div>
            @endif
        </div>
    </div>

    <!-- Borrowing Records Section -->
    <div class="card shadow mb-4">
        <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between" style="background: linear-gradient(135deg, #fa709a 0%, #fee140 100%);">
            <h6 class="m-0 font-weight-bold text-white">
                <i class="fas fa-clipboard-list me-2"></i>
                Borrowing Records
            </h6>
            <span class="badge bg-light text-dark">Generate Borrowing Documentation</span>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-6">
                    <div class="card border-primary">
                        <div class="card-body text-center">
                            <i class="fas fa-book-open fa-3x text-primary mb-3"></i>
                            <h5 class="card-title">Borrowing Slip</h5>
                            <p class="card-text">Generate borrowing slips for library books and materials.</p>
                            <button class="btn btn-primary" onclick="showBorrowingModal()">
                                <i class="fas fa-plus me-1"></i> Generate Slip
                            </button>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="card border-success">
                        <div class="card-body text-center">
                            <i class="fas fa-undo fa-3x text-success mb-3"></i>
                            <h5 class="card-title">Return Receipt</h5>
                            <p class="card-text">Create return receipts for returned library materials.</p>
                            <button class="btn btn-success" onclick="showReturnModal()">
                                <i class="fas fa-plus me-1"></i> Generate Receipt
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
                                Total Users
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $users->count() }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-users fa-2x text-gray-300"></i>
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
                                Active Members
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $users->where('library_status', 'active')->count() }}</div>
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
                                Cards Generated
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">0</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-address-card fa-2x text-gray-300"></i>
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
                                Records Generated
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">0</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-clipboard-list fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Borrowing Slip Modal -->
<div class="modal fade" id="borrowingModal" tabindex="-1" aria-labelledby="borrowingModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="borrowingModalLabel">Generate Borrowing Slip</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="borrowingForm">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="borrower_name" class="form-label">Borrower Name</label>
                                <input type="text" class="form-control" id="borrower_name" name="borrower_name" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="library_id" class="form-label">Library ID</label>
                                <input type="text" class="form-control" id="library_id" name="library_id" required>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="book_title" class="form-label">Book Title</label>
                                <input type="text" class="form-control" id="book_title" name="book_title" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="book_id" class="form-label">Book ID</label>
                                <input type="text" class="form-control" id="book_id" name="book_id" required>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="borrow_date" class="form-label">Borrow Date</label>
                                <input type="date" class="form-control" id="borrow_date" name="borrow_date" value="{{ date('Y-m-d') }}" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="return_date" class="form-label">Return Date</label>
                                <input type="date" class="form-control" id="return_date" name="return_date" required>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary" onclick="generateBorrowingSlip()">
                    <i class="fas fa-download me-1"></i> Generate Slip
                </button>
            </div>
        </div>
    </div>
</div>

<!-- JavaScript for Actions -->
<script>
function bulkGenerateLibraryCards() {
    // Navigate to bulk generation with library cards selected
    const modal = document.getElementById('bulkGenerateModal');
    if (modal) {
        document.getElementById('bulkDocumentType').value = 'library_cards';
        document.getElementById('bulkSelection').value = 'all_users';
        var bootstrapModal = new bootstrap.Modal(modal);
        bootstrapModal.show();
    } else {
        // Fallback: redirect to dashboard with bulk generation
        window.location.href = '{{ route("documents.index") }}';
    }
}

function previewLibraryTemplates() {
    // Open template preview in new window
    window.open('{{ route("documents.templates.preview", "library_card") }}', '_blank');
}

function previewLibraryCard(userId) {
    // Implement library card preview
    window.open(`{{ route('documents.templates.preview', 'library_card') }}?user_id=${userId}`, '_blank');
}

function generateBorrowingRecord(userId) {
    // Show borrowing modal
    showBorrowingModal();
}

function showBorrowingModal() {
    // Show borrowing slip modal
    var modal = new bootstrap.Modal(document.getElementById('borrowingModal'));
    modal.show();
}

function showReturnModal() {
    // Show return receipt modal
    var modal = new bootstrap.Modal(document.getElementById('returnModal'));
    modal.show();
}

function generateBorrowingSlip() {
    // Generate borrowing slip from form data
    var form = document.getElementById('borrowingForm');
    var formData = new FormData(form);
    
    // For now, show a success message
    alert('Borrowing slip generation feature will be implemented soon!');
    
    // Close the modal
    var modal = bootstrap.Modal.getInstance(document.getElementById('borrowingModal'));
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
