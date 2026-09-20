@extends('layouts.app')

@section('title', 'Admission Applications')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-1"><i class="fas fa-file-alt me-2 text-primary"></i>Admission Applications</h1>
            <p class="text-muted mb-0">Manage student admission applications</p>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    {{-- Dashboard Stats --}}
    <div class="row g-3 mb-4">
        <div class="col-xl-2 col-md-4 col-6">
            <div class="card shadow-sm border-0 bg-primary bg-opacity-10">
                <div class="card-body text-center py-3">
                    <div class="fw-bold fs-4 text-primary">{{ $stats['total'] ?? 0 }}</div>
                    <small class="text-muted">Total</small>
                </div>
            </div>
        </div>
        <div class="col-xl-2 col-md-4 col-6">
            <div class="card shadow-sm border-0 bg-warning bg-opacity-10">
                <div class="card-body text-center py-3">
                    <div class="fw-bold fs-4 text-warning">{{ $stats['new'] ?? 0 }}</div>
                    <small class="text-muted">New</small>
                </div>
            </div>
        </div>
        <div class="col-xl-2 col-md-4 col-6">
            <div class="card shadow-sm border-0 bg-info bg-opacity-10">
                <div class="card-body text-center py-3">
                    <div class="fw-bold fs-4 text-info">{{ $stats['under_review'] ?? 0 }}</div>
                    <small class="text-muted">Under Review</small>
                </div>
            </div>
        </div>
        <div class="col-xl-2 col-md-4 col-6">
            <div class="card shadow-sm border-0 bg-success bg-opacity-10">
                <div class="card-body text-center py-3">
                    <div class="fw-bold fs-4 text-success">{{ $stats['accepted'] ?? 0 }}</div>
                    <small class="text-muted">Accepted</small>
                </div>
            </div>
        </div>
        <div class="col-xl-2 col-md-4 col-6">
            <div class="card shadow-sm border-0 bg-danger bg-opacity-10">
                <div class="card-body text-center py-3">
                    <div class="fw-bold fs-4 text-danger">{{ $stats['rejected'] ?? 0 }}</div>
                    <small class="text-muted">Rejected</small>
                </div>
            </div>
        </div>
        <div class="col-xl-2 col-md-4 col-6">
            <div class="card shadow-sm border-0 bg-secondary bg-opacity-10">
                <div class="card-body text-center py-3">
                    <div class="fw-bold fs-4 text-secondary">{{ $stats['waitlisted'] ?? 0 }}</div>
                    <small class="text-muted">Waitlisted</small>
                </div>
            </div>
        </div>
    </div>

    {{-- Filters --}}
    <div class="card shadow-sm mb-4">
        <div class="card-body">
            <form method="GET" class="row g-3 align-items-end">
                <div class="col-md-2">
                    <label class="form-label">Status</label>
                    <select name="status" class="form-select">
                        <option value="">All Statuses</option>
                        <option value="new" {{ request('status') == 'new' ? 'selected' : '' }}>New</option>
                        <option value="under_review" {{ request('status') == 'under_review' ? 'selected' : '' }}>Under Review</option>
                        <option value="accepted" {{ request('status') == 'accepted' ? 'selected' : '' }}>Accepted</option>
                        <option value="rejected" {{ request('status') == 'rejected' ? 'selected' : '' }}>Rejected</option>
                        <option value="waitlisted" {{ request('status') == 'waitlisted' ? 'selected' : '' }}>Waitlisted</option>
                        <option value="enrolled" {{ request('status') == 'enrolled' ? 'selected' : '' }}>Enrolled</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <label class="form-label">Cycle</label>
                    <select name="cycle_id" class="form-select">
                        <option value="">All Cycles</option>
                        @isset($cycles)
                            @foreach($cycles as $cycle)
                                <option value="{{ $cycle->id }}" {{ request('cycle_id') == $cycle->id ? 'selected' : '' }}>{{ $cycle->name }}</option>
                            @endforeach
                        @endisset
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label">Search</label>
                    <input type="text" name="search" class="form-control" placeholder="Name, email, reference..." value="{{ request('search') }}">
                </div>
                <div class="col-md-2">
                    <button type="submit" class="btn btn-primary w-100">
                        <i class="fas fa-search me-1"></i> Filter
                    </button>
                </div>
                <div class="col-md-2">
                    <a href="{{ route('admin.cms.admissions.applications.index') }}" class="btn btn-outline-secondary w-100">
                        <i class="fas fa-times me-1"></i> Clear
                    </a>
                </div>
            </form>
        </div>
    </div>

    <div class="card shadow-sm">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>Application #</th>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Status</th>
                            <th>Cycle</th>
                            <th>Date</th>
                            <th class="text-end">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($applications as $application)
                        <tr>
                            <td><code>{{ $application->application_number ?? $application->reference }}</code></td>
                            <td class="fw-semibold">{{ $application->first_name }} {{ $application->last_name }}</td>
                            <td>{{ $application->email }}</td>
                            <td>
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
                                <span class="badge bg-{{ $statusColors[$application->status] ?? 'secondary' }}">
                                    {{ ucfirst(str_replace('_', ' ', $application->status)) }}
                                </span>
                            </td>
                            <td><span class="badge bg-light text-dark">{{ $application->cycle->name ?? '-' }}</span></td>
                            <td>{{ $application->created_at->format('M d, Y') }}</td>
                            <td class="text-end">
                                <a href="{{ route('admin.cms.admissions.applications.show', $application) }}" class="btn btn-sm btn-outline-primary" title="View">
                                    <i class="fas fa-eye"></i>
                                </a>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="text-center py-5">
                                <i class="fas fa-file-alt fa-3x text-muted mb-3"></i>
                                <p class="text-muted mb-0">No applications found</p>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        @if(method_exists($applications, 'links'))
        <div class="card-footer">
            {{ $applications->links() }}
        </div>
        @endif
    </div>
</div>
@endsection
