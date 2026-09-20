@extends('layouts.app')

@section('title', 'Application Details')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-1"><i class="fas fa-file-alt me-2 text-primary"></i>Application #{{ $application->application_number ?? $application->reference }}</h1>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="{{ route('admin.cms.admissions.applications.index') }}">Applications</a></li>
                    <li class="breadcrumb-item active">{{ $application->application_number ?? $application->reference }}</li>
                </ol>
            </nav>
        </div>
        <a href="{{ route('admin.cms.admissions.applications.index') }}" class="btn btn-outline-secondary">
            <i class="fas fa-arrow-left me-1"></i> Back to List
        </a>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="row">
        <div class="col-lg-8">
            {{-- Tabs --}}
            <ul class="nav nav-tabs mb-4" id="applicationTabs" role="tablist">
                <li class="nav-item" role="presentation">
                    <button class="nav-link active" id="personal-tab" data-bs-toggle="tab" data-bs-target="#personal" type="button" role="tab">
                        <i class="fas fa-user me-1"></i> Personal
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="parent-tab" data-bs-toggle="tab" data-bs-target="#parent" type="button" role="tab">
                        <i class="fas fa-users me-1"></i> Parent/Guardian
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="academic-tab" data-bs-toggle="tab" data-bs-target="#academic" type="button" role="tab">
                        <i class="fas fa-graduation-cap me-1"></i> Academic
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="emergency-tab" data-bs-toggle="tab" data-bs-target="#emergency" type="button" role="tab">
                        <i class="fas fa-phone-alt me-1"></i> Emergency
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="documents-tab" data-bs-toggle="tab" data-bs-target="#documents" type="button" role="tab">
                        <i class="fas fa-file me-1"></i> Documents
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="notes-tab" data-bs-toggle="tab" data-bs-target="#notes" type="button" role="tab">
                        <i class="fas fa-sticky-note me-1"></i> Notes
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="logs-tab" data-bs-toggle="tab" data-bs-target="#logs" type="button" role="tab">
                        <i class="fas fa-history me-1"></i> Logs
                    </button>
                </li>
            </ul>

            <div class="tab-content" id="applicationTabContent">
                {{-- Personal Info --}}
                <div class="tab-pane fade show active" id="personal" role="tabpanel">
                    <div class="card shadow-sm">
                        <div class="card-body">
                            <div class="row g-4">
                                <div class="col-md-6">
                                    <h6 class="text-muted mb-1">First Name</h6>
                                    <p class="fw-semibold mb-0">{{ $application->first_name }}</p>
                                </div>
                                <div class="col-md-6">
                                    <h6 class="text-muted mb-1">Last Name</h6>
                                    <p class="fw-semibold mb-0">{{ $application->last_name }}</p>
                                </div>
                                <div class="col-md-6">
                                    <h6 class="text-muted mb-1">Date of Birth</h6>
                                    <p class="fw-semibold mb-0">{{ $application->date_of_birth ? \Carbon\Carbon::parse($application->date_of_birth)->format('M d, Y') : '-' }}</p>
                                </div>
                                <div class="col-md-6">
                                    <h6 class="text-muted mb-1">Gender</h6>
                                    <p class="fw-semibold mb-0">{{ ucfirst($application->gender ?? '-') }}</p>
                                </div>
                                <div class="col-md-6">
                                    <h6 class="text-muted mb-1">Email</h6>
                                    <p class="mb-0"><a href="mailto:{{ $application->email }}">{{ $application->email }}</a></p>
                                </div>
                                <div class="col-md-6">
                                    <h6 class="text-muted mb-1">Phone</h6>
                                    <p class="mb-0">{{ $application->phone ?? '-' }}</p>
                                </div>
                                <div class="col-12">
                                    <h6 class="text-muted mb-1">Address</h6>
                                    <p class="mb-0">{{ $application->address ?? '-' }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Parent/Guardian --}}
                <div class="tab-pane fade" id="parent" role="tabpanel">
                    <div class="card shadow-sm">
                        <div class="card-body">
                            <div class="row g-4">
                                <div class="col-md-6">
                                    <h6 class="text-muted mb-1">Parent/Guardian Name</h6>
                                    <p class="fw-semibold mb-0">{{ $application->parent_name ?? '-' }}</p>
                                </div>
                                <div class="col-md-6">
                                    <h6 class="text-muted mb-1">Relationship</h6>
                                    <p class="fw-semibold mb-0">{{ $application->parent_relationship ?? '-' }}</p>
                                </div>
                                <div class="col-md-6">
                                    <h6 class="text-muted mb-1">Parent Email</h6>
                                    <p class="mb-0">{{ $application->parent_email ?? '-' }}</p>
                                </div>
                                <div class="col-md-6">
                                    <h6 class="text-muted mb-1">Parent Phone</h6>
                                    <p class="mb-0">{{ $application->parent_phone ?? '-' }}</p>
                                </div>
                                <div class="col-md-6">
                                    <h6 class="text-muted mb-1">Occupation</h6>
                                    <p class="mb-0">{{ $application->parent_occupation ?? '-' }}</p>
                                </div>
                                <div class="col-md-6">
                                    <h6 class="text-muted mb-1">Annual Income</h6>
                                    <p class="mb-0">{{ $application->parent_income ?? '-' }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Academic --}}
                <div class="tab-pane fade" id="academic" role="tabpanel">
                    <div class="card shadow-sm">
                        <div class="card-body">
                            <div class="row g-4">
                                <div class="col-md-6">
                                    <h6 class="text-muted mb-1">Previous School</h6>
                                    <p class="fw-semibold mb-0">{{ $application->previous_school ?? '-' }}</p>
                                </div>
                                <div class="col-md-6">
                                    <h6 class="text-muted mb-1">Grade Applying For</h6>
                                    <p class="fw-semibold mb-0">{{ $application->grade_applying ?? '-' }}</p>
                                </div>
                                <div class="col-md-6">
                                    <h6 class="text-muted mb-1">Last Grade Average</h6>
                                    <p class="mb-0">{{ $application->last_grade_average ?? '-' }}</p>
                                </div>
                                <div class="col-md-6">
                                    <h6 class="text-muted mb-1">Special Needs</h6>
                                    <p class="mb-0">{{ $application->special_needs ? 'Yes' : 'No' }}</p>
                                </div>
                                @if($application->special_needs_description)
                                <div class="col-12">
                                    <h6 class="text-muted mb-1">Special Needs Description</h6>
                                    <p class="mb-0">{{ $application->special_needs_description }}</p>
                                </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Emergency --}}
                <div class="tab-pane fade" id="emergency" role="tabpanel">
                    <div class="card shadow-sm">
                        <div class="card-body">
                            <div class="row g-4">
                                <div class="col-md-6">
                                    <h6 class="text-muted mb-1">Emergency Contact Name</h6>
                                    <p class="fw-semibold mb-0">{{ $application->emergency_contact_name ?? '-' }}</p>
                                </div>
                                <div class="col-md-6">
                                    <h6 class="text-muted mb-1">Emergency Contact Phone</h6>
                                    <p class="mb-0">{{ $application->emergency_contact_phone ?? '-' }}</p>
                                </div>
                                <div class="col-md-6">
                                    <h6 class="text-muted mb-1">Relationship</h6>
                                    <p class="mb-0">{{ $application->emergency_relationship ?? '-' }}</p>
                                </div>
                                <div class="col-md-6">
                                    <h6 class="text-muted mb-1">Medical Conditions</h6>
                                    <p class="mb-0">{{ $application->medical_conditions ?? 'None' }}</p>
                                </div>
                                <div class="col-md-6">
                                    <h6 class="text-muted mb-1">Blood Group</h6>
                                    <p class="mb-0">{{ $application->blood_group ?? '-' }}</p>
                                </div>
                                <div class="col-md-6">
                                    <h6 class="text-muted mb-1">Allergies</h6>
                                    <p class="mb-0">{{ $application->allergies ?? 'None' }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Documents --}}
                <div class="tab-pane fade" id="documents" role="tabpanel">
                    <div class="card shadow-sm">
                        <div class="card-body">
                            @if($application->documents && count($application->documents) > 0)
                                <div class="list-group">
                                    @foreach($application->documents as $document)
                                    <div class="list-group-item d-flex justify-content-between align-items-center">
                                        <div>
                                            <i class="fas fa-file-alt me-2 text-primary"></i>
                                            {{ $document->name ?? $document->filename }}
                                        </div>
                                        <a href="{{ asset('storage/' . ($document->path ?? $document->file_path)) }}" class="btn btn-sm btn-outline-primary" target="_blank">
                                            <i class="fas fa-download me-1"></i> Download
                                        </a>
                                    </div>
                                    @endforeach
                                </div>
                            @else
                                <p class="text-muted mb-0 text-center py-4">
                                    <i class="fas fa-file fa-2x d-block mb-2"></i>
                                    No documents uploaded
                                </p>
                            @endif
                        </div>
                    </div>
                </div>

                {{-- Notes --}}
                <div class="tab-pane fade" id="notes" role="tabpanel">
                    <div class="card shadow-sm mb-3">
                        <div class="card-body">
                            @forelse($application->notes ?? [] as $note)
                                <div class="border-start border-primary border-3 ps-3 mb-3">
                                    <div class="d-flex justify-content-between">
                                        <strong>{{ $note->user->name ?? 'System' }}</strong>
                                        <small class="text-muted">{{ $note->created_at->format('M d, Y h:i A') }}</small>
                                    </div>
                                    <p class="mb-0 mt-1">{{ $note->content }}</p>
                                </div>
                            @empty
                                <p class="text-muted mb-0">No notes yet</p>
                            @endforelse
                        </div>
                    </div>

                    <div class="card shadow-sm">
                        <div class="card-header">
                            <h6 class="mb-0"><i class="fas fa-plus-circle me-2"></i>Add Note</h6>
                        </div>
                        <div class="card-body">
                            <form action="{{ route('admin.cms.admissions.applications.note', $application) }}" method="POST">
                                @csrf
                                <div class="mb-3">
                                    <textarea class="form-control" name="note" rows="3" placeholder="Add an internal note..." required></textarea>
                                </div>
                                <button type="submit" class="btn btn-primary btn-sm">
                                    <i class="fas fa-save me-1"></i> Save Note
                                </button>
                            </form>
                        </div>
                    </div>
                </div>

                {{-- Logs --}}
                <div class="tab-pane fade" id="logs" role="tabpanel">
                    <div class="card shadow-sm">
                        <div class="card-body">
                            @if(isset($application->logs) && count($application->logs) > 0)
                                <div class="timeline">
                                    @foreach($application->logs as $log)
                                    <div class="d-flex mb-3">
                                        <div class="me-3">
                                            <div class="bg-primary bg-opacity-10 rounded-circle d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">
                                                <i class="fas fa-circle fa-xs text-primary"></i>
                                            </div>
                                        </div>
                                        <div>
                                            <div class="fw-semibold">{{ $log->action ?? 'Status changed' }}</div>
                                            <small class="text-muted">{{ $log->created_at->format('M d, Y h:i A') }}</small>
                                            @if($log->description)
                                                <p class="mb-0 mt-1 text-muted">{{ $log->description }}</p>
                                            @endif
                                        </div>
                                    </div>
                                    @endforeach
                                </div>
                            @else
                                <p class="text-muted mb-0 text-center py-4">No activity logs found</p>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            {{-- Status Update --}}
            <div class="card shadow-sm mb-4">
                <div class="card-header">
                    <h5 class="mb-0"><i class="fas fa-cog me-2"></i>Status Actions</h5>
                </div>
                <div class="card-body">
                    @php
                        $statusColors = [
                            'new' => 'warning',
                            'under_review' => 'info',
                            'accepted' => 'success',
                            'rejected' => 'danger',
                            'waitlisted' => 'secondary',
                            'enrolled' => 'primary',
                        ];
                    @endphp
                    <div class="mb-3">
                        <span class="badge bg-{{ $statusColors[$application->status] ?? 'secondary' }} fs-6">
                            {{ ucfirst(str_replace('_', ' ', $application->status)) }}
                        </span>
                    </div>

                    <form action="{{ route('admin.cms.admissions.applications.update', $application) }}" method="POST">
                        @csrf
                        @method('PUT')
                        <div class="mb-3">
                            <label class="form-label">Update Status</label>
                            <select class="form-select" name="status">
                                <option value="new" {{ $application->status == 'new' ? 'selected' : '' }}>New</option>
                                <option value="under_review" {{ $application->status == 'under_review' ? 'selected' : '' }}>Under Review</option>
                                <option value="accepted" {{ $application->status == 'accepted' ? 'selected' : '' }}>Accepted</option>
                                <option value="rejected" {{ $application->status == 'rejected' ? 'selected' : '' }}>Rejected</option>
                                <option value="waitlisted" {{ $application->status == 'waitlisted' ? 'selected' : '' }}>Waitlisted</option>
                                <option value="enrolled" {{ $application->status == 'enrolled' ? 'selected' : '' }}>Enrolled</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Admin Notes</label>
                            <textarea class="form-control" name="admin_notes" rows="2" placeholder="Notes about this status change...">{{ $application->admin_notes ?? '' }}</textarea>
                        </div>
                        <button type="submit" class="btn btn-primary w-100">
                            <i class="fas fa-save me-1"></i> Update Status
                        </button>
                    </form>
                </div>
            </div>

            {{-- Application Info --}}
            <div class="card shadow-sm mb-4">
                <div class="card-header">
                    <h5 class="mb-0"><i class="fas fa-info-circle me-2"></i>Application Info</h5>
                </div>
                <div class="card-body">
                    <table class="table table-borderless table-sm mb-0">
                        <tr>
                            <td class="text-muted" style="width: 100px;">Reference</td>
                            <td><code>{{ $application->application_number ?? $application->reference }}</code></td>
                        </tr>
                        <tr>
                            <td class="text-muted">Cycle</td>
                            <td>{{ $application->cycle->name ?? '-' }}</td>
                        </tr>
                        <tr>
                            <td class="text-muted">Submitted</td>
                            <td>{{ $application->created_at->format('M d, Y') }}</td>
                        </tr>
                        <tr>
                            <td class="text-muted">Last Updated</td>
                            <td>{{ $application->updated_at->format('M d, Y') }}</td>
                        </tr>
                    </table>
                </div>
            </div>

            {{-- Quick Actions --}}
            <div class="card shadow-sm mb-4">
                <div class="card-header">
                    <h5 class="mb-0"><i class="fas fa-bolt me-2"></i>Quick Actions</h5>
                </div>
                <div class="card-body d-grid gap-2">
                    <a href="mailto:{{ $application->email }}?subject=Application {{ $application->application_number ?? $application->reference }}" class="btn btn-outline-primary">
                        <i class="fas fa-envelope me-1"></i> Email Applicant
                    </a>
                    @if($application->parent_email)
                    <a href="mailto:{{ $application->parent_email }}?subject=Application {{ $application->application_number ?? $application->reference }}" class="btn btn-outline-primary">
                        <i class="fas fa-envelope me-1"></i> Email Parent
                    </a>
                    @endif
                    <button class="btn btn-outline-success" onclick="window.print()">
                        <i class="fas fa-print me-1"></i> Print Application
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

@push('styles')
<style>
    @media print {
        .sidebar, .main-content { margin-left: 0 !important; width: 100% !important; }
        .btn, .nav-tabs, .card-header { display: none !important; }
    }
</style>
@endpush
@endsection
