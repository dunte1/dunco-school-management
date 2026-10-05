@extends('layouts.app')

@section('title', 'Documents Dashboard')

@section('content')
<div class="container-fluid">
    <!-- Page Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-0 text-gray-800">
                <i class="fas fa-file-alt text-primary me-2"></i>
                Documents Dashboard
                @if(isset($isSystemAdmin) && $isSystemAdmin)
                    <span class="badge badge-info ml-2">System Admin View</span>
                @endif
            </h1>
            <p class="text-muted">
                @if(isset($isSystemAdmin) && $isSystemAdmin)
                    Generate and manage documents for all schools with professional branding
                @else
                    Generate and manage all school documents with professional branding
                @endif
            </p>
        </div>
        <div class="d-flex gap-2">
            <button class="btn btn-outline-primary" onclick="window.print()">
                <i class="fas fa-print me-1"></i> Print Dashboard
            </button>
            <a href="{{ route('documents.branding') }}" class="btn btn-outline-info">
                <i class="fas fa-palette me-1"></i> Branding Settings
            </a>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="row mb-4">
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                Total Students
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ number_format($stats['students']) }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-user-graduate fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
    </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-success shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                Total Staff
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ number_format($stats['staff']) }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-user-tie fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-info shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                                Exam Results
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ number_format($stats['results']) }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-chart-line fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-warning shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                                Payments
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ number_format($stats['payments']) }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-money-bill-wave fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Tabbed Document Interface -->
    <div class="card shadow">
        <div class="card-header">
            <ul class="nav nav-tabs card-header-tabs" id="documentsTab" role="tablist">
                <li class="nav-item" role="presentation">
                    <button class="nav-link active" id="academic-tab" data-bs-toggle="tab" data-bs-target="#academic" type="button" role="tab" aria-controls="academic" aria-selected="true">
                        <i class="fas fa-graduation-cap me-2"></i>Academic
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="students-tab" data-bs-toggle="tab" data-bs-target="#students" type="button" role="tab" aria-controls="students" aria-selected="false">
                        <i class="fas fa-user-graduate me-2"></i>Students
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="staff-tab" data-bs-toggle="tab" data-bs-target="#staff" type="button" role="tab" aria-controls="staff" aria-selected="false">
                        <i class="fas fa-user-tie me-2"></i>Staff
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="library-tab" data-bs-toggle="tab" data-bs-target="#library" type="button" role="tab" aria-controls="library" aria-selected="false">
                        <i class="fas fa-book me-2"></i>Library
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="finance-tab" data-bs-toggle="tab" data-bs-target="#finance" type="button" role="tab" aria-controls="finance" aria-selected="false">
                        <i class="fas fa-money-bill-wave me-2"></i>Finance
                    </button>
                </li>
            </ul>
        </div>
        <div class="card-body">
            <div class="tab-content" id="documentsTabContent">
                <!-- Academic Tab -->
                <div class="tab-pane fade show active" id="academic" role="tabpanel" aria-labelledby="academic-tab">
                    <div class="row">
                        <div class="col-md-6 mb-4">
                            <div class="card border-primary">
                                <div class="card-header bg-primary text-white">
                                    <h6 class="mb-0"><i class="fas fa-file-alt me-2"></i>Result Slips</h6>
                                </div>
                                <div class="card-body">
                                    <p class="card-text">Generate exam result slips with grades and performance analysis.</p>
                                    <div class="d-flex gap-2">
                                        <button class="btn btn-primary btn-sm" onclick="generateResultSlip()">
                                            <i class="fas fa-plus me-1"></i>Generate
                                        </button>
                                        <button class="btn btn-outline-primary btn-sm" onclick="previewTemplate('result_slip')">
                                            <i class="fas fa-eye me-1"></i>Preview
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6 mb-4">
                            <div class="card border-success">
                                <div class="card-header bg-success text-white">
                                    <h6 class="mb-0"><i class="fas fa-ticket-alt me-2"></i>Exam Cards</h6>
                                </div>
                                <div class="card-body">
                                    <p class="card-text">Create examination cards with timetables and instructions.</p>
                                    <div class="d-flex gap-2">
                                        <button class="btn btn-success btn-sm" onclick="generateExamCard()">
                                            <i class="fas fa-plus me-1"></i>Generate
                                        </button>
                                        <button class="btn btn-outline-success btn-sm" onclick="previewTemplate('exam_card')">
                                            <i class="fas fa-eye me-1"></i>Preview
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
    </div>
    
                <!-- Students Tab -->
                <div class="tab-pane fade" id="students" role="tabpanel" aria-labelledby="students-tab">
                    <div class="row">
                        <div class="col-md-6 mb-4">
                            <div class="card border-info">
                                <div class="card-header bg-info text-white">
                                    <h6 class="mb-0"><i class="fas fa-id-card me-2"></i>Student ID Cards</h6>
                                </div>
                                <div class="card-body">
                                    <p class="card-text">Professional student identification cards with QR codes.</p>
                                    <div class="d-flex gap-2">
                                        <button class="btn btn-info btn-sm" onclick="generateStudentIdCard()">
                                            <i class="fas fa-plus me-1"></i>Generate
                                        </button>
                                        <button class="btn btn-outline-info btn-sm" onclick="previewTemplate('student_id_card')">
                                            <i class="fas fa-eye me-1"></i>Preview
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6 mb-4">
                            <div class="card border-warning">
                                <div class="card-header bg-warning text-white">
                                    <h6 class="mb-0"><i class="fas fa-certificate me-2"></i>Certificates</h6>
                                </div>
                                <div class="card-body">
                                    <p class="card-text">Academic completion and achievement certificates.</p>
                                    <div class="d-flex gap-2">
                                        <button class="btn btn-warning btn-sm" onclick="generateCertificate()">
                                            <i class="fas fa-plus me-1"></i>Generate
                                        </button>
                                        <button class="btn btn-outline-warning btn-sm" onclick="previewTemplate('certificate')">
                                            <i class="fas fa-eye me-1"></i>Preview
                                        </button>
                                    </div>
        </div>
        </div>
        </div>
        </div>
    </div>
    
                <!-- Staff Tab -->
                <div class="tab-pane fade" id="staff" role="tabpanel" aria-labelledby="staff-tab">
                    <div class="row">
                        <div class="col-md-6 mb-4">
                            <div class="card border-secondary">
                                <div class="card-header bg-secondary text-white">
                                    <h6 class="mb-0"><i class="fas fa-id-badge me-2"></i>Staff ID Cards</h6>
                                </div>
                                <div class="card-body">
                                    <p class="card-text">Professional staff identification cards with department info.</p>
                                    <div class="d-flex gap-2">
                                        <button class="btn btn-secondary btn-sm" onclick="generateStaffIdCard()">
                                            <i class="fas fa-plus me-1"></i>Generate
                                        </button>
                                        <button class="btn btn-outline-secondary btn-sm" onclick="previewTemplate('staff_id_card')">
                                            <i class="fas fa-eye me-1"></i>Preview
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6 mb-4">
                            <div class="card border-dark">
                                <div class="card-header bg-dark text-white">
                                    <h6 class="mb-0"><i class="fas fa-file-contract me-2"></i>Employment Documents</h6>
                                </div>
                                <div class="card-body">
                                    <p class="card-text">Employment contracts and staff documentation.</p>
                                    <div class="d-flex gap-2">
                                        <button class="btn btn-dark btn-sm" onclick="generateEmploymentDoc()">
                                            <i class="fas fa-plus me-1"></i>Generate
                                        </button>
                                        <button class="btn btn-outline-dark btn-sm" onclick="previewTemplate('employment_doc')">
                                            <i class="fas fa-eye me-1"></i>Preview
                                        </button>
                                    </div>
            </div>
            </div>
            </div>
        </div>
    </div>
    
                <!-- Library Tab -->
                <div class="tab-pane fade" id="library" role="tabpanel" aria-labelledby="library-tab">
                    <div class="row">
                        <div class="col-md-6 mb-4">
                            <div class="card border-purple">
                                <div class="card-header bg-purple text-white">
                                    <h6 class="mb-0"><i class="fas fa-book me-2"></i>Library Cards</h6>
                                </div>
                                <div class="card-body">
                                    <p class="card-text">Library membership cards with barcode integration.</p>
                                    <div class="d-flex gap-2">
                                        <button class="btn btn-purple btn-sm" onclick="generateLibraryCard()">
                                            <i class="fas fa-plus me-1"></i>Generate
                                        </button>
                                        <button class="btn btn-outline-purple btn-sm" onclick="previewTemplate('library_card')">
                                            <i class="fas fa-eye me-1"></i>Preview
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6 mb-4">
                            <div class="card border-indigo">
                                <div class="card-header bg-indigo text-white">
                                    <h6 class="mb-0"><i class="fas fa-list me-2"></i>Library Reports</h6>
                                </div>
                                <div class="card-body">
                                    <p class="card-text">Library usage reports and borrowing statistics.</p>
                                    <div class="d-flex gap-2">
                                        <button class="btn btn-indigo btn-sm" onclick="generateLibraryReport()">
                                            <i class="fas fa-plus me-1"></i>Generate
                                        </button>
                                        <button class="btn btn-outline-indigo btn-sm" onclick="previewTemplate('library_report')">
                                            <i class="fas fa-eye me-1"></i>Preview
                                        </button>
                                    </div>
                                </div>
                            </div>
            </div>
        </div>
    </div>
    
                <!-- Finance Tab -->
                <div class="tab-pane fade" id="finance" role="tabpanel" aria-labelledby="finance-tab">
                    <div class="row">
                        <div class="col-md-6 mb-4">
                            <div class="card border-success">
                                <div class="card-header bg-success text-white">
                                    <h6 class="mb-0"><i class="fas fa-receipt me-2"></i>Fee Receipts</h6>
                                </div>
                                <div class="card-body">
                                    <p class="card-text">Payment receipts with transaction details and verification.</p>
                                    <div class="d-flex gap-2">
                                        <button class="btn btn-success btn-sm" onclick="generateFeeReceipt()">
                                            <i class="fas fa-plus me-1"></i>Generate
                                        </button>
                                        <button class="btn btn-outline-success btn-sm" onclick="previewTemplate('fee_receipt')">
                                            <i class="fas fa-eye me-1"></i>Preview
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6 mb-4">
                            <div class="card border-warning">
                                <div class="card-header bg-warning text-white">
                                    <h6 class="mb-0"><i class="fas fa-file-invoice-dollar me-2"></i>Fee Structure</h6>
                                </div>
                                <div class="card-body">
                                    <p class="card-text">Fee structure documents for different classes and programs.</p>
                                    <div class="d-flex gap-2">
                                        <button class="btn btn-warning btn-sm" onclick="generateFeeStructure()">
                                            <i class="fas fa-plus me-1"></i>Generate
                                        </button>
                                        <button class="btn btn-outline-warning btn-sm" onclick="previewTemplate('fee_structure')">
                                            <i class="fas fa-eye me-1"></i>Preview
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Bulk Generation Section -->
    <div class="card shadow mt-4">
        <div class="card-header">
            <h6 class="mb-0"><i class="fas fa-layer-group me-2"></i>Bulk Document Generation</h6>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="bulkDocumentType">Document Type</label>
                        <select class="form-control" id="bulkDocumentType">
                            <option value="">Select Document Type</option>
                            <option value="student_id_card">Student ID Cards</option>
                            <option value="staff_id_card">Staff ID Cards</option>
                            <option value="result_slip">Result Slips</option>
                            <option value="exam_card">Exam Cards</option>
                            <option value="library_card">Library Cards</option>
                            <option value="fee_receipt">Fee Receipts</option>
                        </select>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="bulkSelection">Selection</label>
                        <select class="form-control" id="bulkSelection">
                            <option value="">Select Items</option>
                            <option value="all">All Items</option>
                            <option value="selected">Selected Items</option>
                            <option value="filtered">Filtered Items</option>
                        </select>
                    </div>
                </div>
            </div>
            <div class="mt-3">
                <button class="btn btn-primary" onclick="bulkGenerate()">
                    <i class="fas fa-download me-1"></i>Generate Bulk Documents
                </button>
                <small class="text-muted">Multiple documents at once</small>
            </div>
            </div>
        </div>
    </div>

<style>
.border-purple { border-color: #6f42c1 !important; }
.bg-purple { background-color: #6f42c1 !important; }
.btn-purple { background-color: #6f42c1; border-color: #6f42c1; color: white; }
.btn-purple:hover { background-color: #5a32a3; border-color: #5a32a3; color: white; }
.btn-outline-purple { color: #6f42c1; border-color: #6f42c1; }
.btn-outline-purple:hover { background-color: #6f42c1; border-color: #6f42c1; color: white; }

.border-indigo { border-color: #6610f2 !important; }
.bg-indigo { background-color: #6610f2 !important; }
.btn-indigo { background-color: #6610f2; border-color: #6610f2; color: white; }
.btn-indigo:hover { background-color: #520dc2; border-color: #520dc2; color: white; }
.btn-outline-indigo { color: #6610f2; border-color: #6610f2; }
.btn-outline-indigo:hover { background-color: #6610f2; border-color: #6610f2; color: white; }

.nav-tabs .nav-link {
    border: none;
    border-bottom: 2px solid transparent;
    color: #6c757d;
    font-weight: 500;
}

.nav-tabs .nav-link.active {
    border-bottom: 2px solid #007bff;
    color: #007bff;
    background: none;
}

.nav-tabs .nav-link:hover {
    border-color: transparent;
    color: #007bff;
}
</style>

<script>
// Document generation functions
function generateResultSlip() {
    // Navigate to academic documents section for result slips
    window.location.href = '{{ route("documents.academic") }}';
        }

        function generateExamCard() {
    // Navigate to academic documents section for exam cards
    window.location.href = '{{ route("documents.academic") }}';
        }

        function generateStudentIdCard() {
    // Navigate to students documents section
    window.location.href = '{{ route("documents.students") }}';
}

function generateCertificate() {
    // Show certificate generation form
    alert('Certificate generation form coming soon! Please use the Academic section for now.');
        }

        function generateStaffIdCard() {
    // Navigate to staff documents section
    window.location.href = '{{ route("documents.staff") }}';
}

function generateEmploymentDoc() {
    // Navigate to staff documents section
    window.location.href = '{{ route("documents.staff") }}';
        }

        function generateLibraryCard() {
    // Navigate to library documents section
    window.location.href = '{{ route("documents.library") }}';
}

function generateLibraryReport() {
    // Navigate to library documents section
    window.location.href = '{{ route("documents.library") }}';
}

function generateFeeReceipt() {
    // Navigate to finance documents section
    window.location.href = '{{ route("documents.finance") }}';
}

function generateFeeStructure() {
    // Navigate to finance documents section
    window.location.href = '{{ route("documents.finance") }}';
}

function previewTemplate(templateType) {
    // Open template preview in new window
    const url = '{{ route("documents.templates.preview", ":templateType") }}'.replace(':templateType', templateType);
            window.open(url, '_blank');
        }

function bulkGenerate() {
    const documentType = document.getElementById('bulkDocumentType').value;
    const selection = document.getElementById('bulkSelection').value;
    
    if (!documentType || !selection) {
        alert('Please select both document type and selection criteria.');
        return;
    }
    
    // Send bulk generation request
    fetch('{{ route("documents.bulk.generate") }}', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        body: JSON.stringify({
            document_type: documentType,
            selection: selection
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            if (data.redirect) {
                alert(data.message);
                window.location.href = data.redirect;
            } else {
                alert('Bulk generation started! Check your downloads.');
            }
        } else {
            alert('Error: ' + data.message);
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Error starting bulk generation. Please try again.');
    });
}

// Auto-refresh statistics every 5 minutes
setInterval(function() {
    // You can implement AJAX call to refresh statistics here
}, 300000);
    </script>
@endsection
