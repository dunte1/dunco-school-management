@extends('layouts.app')
@section('title', 'Leads')
@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-1"><i class="fas fa-funnel-dollar me-2 text-primary"></i>Leads</h1>
            <p class="text-muted mb-0">Track and manage leads from demo requests and enquiries</p>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show"><i class="fas fa-check-circle me-2"></i>{{ session('success') }}<button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>
    @endif

    <div class="row mb-3">
        <div class="col-md-3"><div class="card text-center shadow-sm"><div class="card-body"><h3 class="text-primary mb-0">{{ $leads->count() }}</h3><small class="text-muted">Total Leads</small></div></div></div>
        <div class="col-md-3"><div class="card text-center shadow-sm"><div class="card-body"><h3 class="text-warning mb-0">{{ $leads->where('status','new')->count() }}</h3><small class="text-muted">New</small></div></div></div>
        <div class="col-md-3"><div class="card text-center shadow-sm"><div class="card-body"><h3 class="text-info mb-0">{{ $leads->where('status','contacted')->count() }}</h3><small class="text-muted">Contacted</small></div></div></div>
        <div class="col-md-3"><div class="card text-center shadow-sm"><div class="card-body"><h3 class="text-success mb-0">{{ $leads->where('status','converted')->count() }}</h3><small class="text-muted">Converted</small></div></div></div>
    </div>

    <div class="card shadow-sm">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr><th>Name</th><th>Email</th><th>Organization</th><th>Source</th><th>Status</th><th>Date</th><th>Actions</th></tr>
                    </thead>
                    <tbody>
                        @forelse($leads as $lead)
                        <tr>
                            <td><strong>{{ $lead->name }}</strong></td>
                            <td>{{ $lead->email }}</td>
                            <td>{{ $lead->organization ?: '-' }}</td>
                            <td><span class="badge bg-light text-dark">{{ $lead->source }}</span></td>
                            <td>
                                <form action="{{ route('admin.cms.leads.update-status', $lead) }}" method="POST" class="d-inline">
                                    @csrf @method('PATCH')
                                    <select name="status" class="form-select form-select-sm form-select-inline" style="width:auto" onchange="this.form.submit()">
                                        @foreach(['new','contacted','qualified','converted','lost','closed'] as $s)
                                        <option value="{{ $s }}" {{ $lead->status === $s ? 'selected' : '' }}>{{ ucfirst($s) }}</option>
                                        @endforeach
                                    </select>
                                </form>
                            </td>
                            <td><small>{{ $lead->created_at->format('M d, Y') }}</small></td>
                            <td><a href="{{ route('admin.cms.leads.show', $lead) }}" class="btn btn-outline-primary btn-sm"><i class="fas fa-eye"></i></a></td>
                        </tr>
                        @empty
                        <tr><td colspan="7" class="text-center py-4 text-muted">No leads yet.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
