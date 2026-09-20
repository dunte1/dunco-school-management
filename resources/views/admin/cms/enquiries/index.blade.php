@extends('layouts.app')

@section('title', 'Enquiries Management')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-1"><i class="fas fa-envelope me-2 text-primary"></i>Enquiries</h1>
            <p class="text-muted mb-0">Manage customer enquiries and support tickets</p>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    {{-- Status Counts --}}
    <div class="row g-3 mb-4">
        <div class="col-xl-2 col-md-4 col-6">
            <div class="card shadow-sm border-0 bg-primary bg-opacity-10">
                <div class="card-body text-center py-3">
                    <div class="fw-bold fs-4 text-primary">{{ $statusCounts['total'] ?? 0 }}</div>
                    <small class="text-muted">Total</small>
                </div>
            </div>
        </div>
        <div class="col-xl-2 col-md-4 col-6">
            <div class="card shadow-sm border-0 bg-warning bg-opacity-10">
                <div class="card-body text-center py-3">
                    <div class="fw-bold fs-4 text-warning">{{ $statusCounts['new'] ?? 0 }}</div>
                    <small class="text-muted">New</small>
                </div>
            </div>
        </div>
        <div class="col-xl-2 col-md-4 col-6">
            <div class="card shadow-sm border-0 bg-info bg-opacity-10">
                <div class="card-body text-center py-3">
                    <div class="fw-bold fs-4 text-info">{{ $statusCounts['in_progress'] ?? 0 }}</div>
                    <small class="text-muted">In Progress</small>
                </div>
            </div>
        </div>
        <div class="col-xl-2 col-md-4 col-6">
            <div class="card shadow-sm border-0 bg-secondary bg-opacity-10">
                <div class="card-body text-center py-3">
                    <div class="fw-bold fs-4 text-secondary">{{ $statusCounts['replied'] ?? 0 }}</div>
                    <small class="text-muted">Replied</small>
                </div>
            </div>
        </div>
        <div class="col-xl-2 col-md-4 col-6">
            <div class="card shadow-sm border-0 bg-success bg-opacity-10">
                <div class="card-body text-center py-3">
                    <div class="fw-bold fs-4 text-success">{{ $statusCounts['resolved'] ?? 0 }}</div>
                    <small class="text-muted">Resolved</small>
                </div>
            </div>
        </div>
        <div class="col-xl-2 col-md-4 col-6">
            <div class="card shadow-sm border-0 bg-dark bg-opacity-10">
                <div class="card-body text-center py-3">
                    <div class="fw-bold fs-4 text-dark">{{ $statusCounts['closed'] ?? 0 }}</div>
                    <small class="text-muted">Closed</small>
                </div>
            </div>
        </div>
    </div>

    {{-- Filters --}}
    <div class="card shadow-sm mb-4">
        <div class="card-body">
            <form method="GET" class="row g-3 align-items-end">
                <div class="col-md-3">
                    <label class="form-label">Status</label>
                    <select name="status" class="form-select">
                        <option value="">All Statuses</option>
                        <option value="new" {{ request('status') == 'new' ? 'selected' : '' }}>New</option>
                        <option value="in_progress" {{ request('status') == 'in_progress' ? 'selected' : '' }}>In Progress</option>
                        <option value="replied" {{ request('status') == 'replied' ? 'selected' : '' }}>Replied</option>
                        <option value="resolved" {{ request('status') == 'resolved' ? 'selected' : '' }}>Resolved</option>
                        <option value="closed" {{ request('status') == 'closed' ? 'selected' : '' }}>Closed</option>
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
                    <a href="{{ route('admin.cms.enquiries.index') }}" class="btn btn-outline-secondary w-100">
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
                            <th>Reference</th>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Subject</th>
                            <th>Status</th>
                            <th>Date</th>
                            <th class="text-end">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($enquiries as $enquiry)
                        <tr>
                            <td><code>{{ $enquiry->reference }}</code></td>
                            <td class="fw-semibold">{{ $enquiry->name }}</td>
                            <td>{{ $enquiry->email }}</td>
                            <td>{{ Str::limit($enquiry->subject, 40) }}</td>
                            <td>
                                @php
                                    $statusColors = [
                                        'new' => 'warning',
                                        'in_progress' => 'info',
                                        'replied' => 'secondary',
                                        'resolved' => 'success',
                                        'closed' => 'dark',
                                    ];
                                @endphp
                                <span class="badge bg-{{ $statusColors[$enquiry->status] ?? 'secondary' }}">
                                    {{ ucfirst(str_replace('_', ' ', $enquiry->status)) }}
                                </span>
                            </td>
                            <td>{{ $enquiry->created_at->format('M d, Y') }}</td>
                            <td class="text-end">
                                <a href="{{ route('admin.cms.enquiries.show', $enquiry) }}" class="btn btn-sm btn-outline-primary" title="View">
                                    <i class="fas fa-eye"></i>
                                </a>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="text-center py-5">
                                <i class="fas fa-envelope fa-3x text-muted mb-3"></i>
                                <p class="text-muted mb-0">No enquiries found</p>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        @if(method_exists($enquiries, 'links'))
        <div class="card-footer">
            {{ $enquiries->links() }}
        </div>
        @endif
    </div>
</div>
@endsection
