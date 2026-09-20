@extends('layouts.app')

@section('title', 'Admission Cycles')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-1"><i class="fas fa-calendar-alt me-2 text-primary"></i>Admission Cycles</h1>
            <p class="text-muted mb-0">Manage admission cycles and application periods</p>
        </div>
        <a href="{{ route('admin.cms.cycles.create') }}" class="btn btn-primary">
            <i class="fas fa-plus me-1"></i> Add Cycle
        </a>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="card shadow-sm">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>Name</th>
                            <th>Academic Year</th>
                            <th>Opening Date</th>
                            <th>Closing Date</th>
                            <th>Status</th>
                            <th>Applications</th>
                            <th class="text-end">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($cycles as $cycle)
                        <tr>
                            <td class="fw-semibold">{{ $cycle->name }}</td>
                            <td><span class="badge bg-info">{{ $cycle->academic_year }}</span></td>
                            <td>{{ $cycle->opening_date ? \Carbon\Carbon::parse($cycle->opening_date)->format('M d, Y') : '-' }}</td>
                            <td>{{ $cycle->closing_date ? \Carbon\Carbon::parse($cycle->closing_date)->format('M d, Y') : '-' }}</td>
                            <td>
                                @php
                                    $statusColors = [
                                        'draft' => 'secondary',
                                        'open' => 'success',
                                        'closing_soon' => 'warning',
                                        'closed' => 'danger',
                                        'completed' => 'info',
                                    ];
                                @endphp
                                <span class="badge bg-{{ $statusColors[$cycle->status] ?? 'secondary' }}">
                                    {{ ucfirst(str_replace('_', ' ', $cycle->status)) }}
                                </span>
                            </td>
                            <td>
                                <span class="badge bg-primary rounded-pill">{{ $cycle->applications_count ?? $cycle->applications->count() ?? 0 }}</span>
                            </td>
                            <td class="text-end">
                                <div class="btn-group btn-group-sm">
                                    <a href="{{ route('admin.cms.cycles.edit', $cycle) }}" class="btn btn-outline-primary" title="Edit">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <form action="{{ route('admin.cms.cycles.destroy', $cycle) }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure you want to delete this cycle?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-outline-danger" title="Delete">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="text-center py-5">
                                <i class="fas fa-calendar-alt fa-3x text-muted mb-3"></i>
                                <p class="text-muted mb-0">No admission cycles found</p>
                                <a href="{{ route('admin.cms.cycles.create') }}" class="btn btn-primary btn-sm mt-3">
                                    <i class="fas fa-plus me-1"></i> Create First Cycle
                                </a>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        @if(method_exists($cycles, 'links'))
        <div class="card-footer">
            {{ $cycles->links() }}
        </div>
        @endif
    </div>
</div>
@endsection
