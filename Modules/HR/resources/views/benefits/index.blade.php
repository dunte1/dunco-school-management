@extends('layouts.app')

@section('title', 'Benefits Management')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h4 class="mb-0">Benefits Management</h4>
                    <a href="{{ route('hr.benefits.create') }}" class="btn btn-primary">
                        <i class="fas fa-plus me-2"></i>New Benefit
                    </a>
                </div>
                <div class="card-body">
                    <!-- Search and Filter Form -->
                    <form method="GET" action="{{ route('hr.benefits.index') }}" class="mb-4">
                        <div class="row">
                            <div class="col-md-4">
                                <label for="search" class="form-label">Search</label>
                                <input type="text" name="search" id="search" class="form-control" 
                                       value="{{ request('search') }}" placeholder="Search by name or description...">
                            </div>
                            <div class="col-md-3">
                                <label for="type" class="form-label">Type</label>
                                <select name="type" id="type" class="form-select">
                                    <option value="">All Types</option>
                                    <option value="monetary" {{ request('type') == 'monetary' ? 'selected' : '' }}>Monetary</option>
                                    <option value="percentage" {{ request('type') == 'percentage' ? 'selected' : '' }}>Percentage</option>
                                    <option value="fixed" {{ request('type') == 'fixed' ? 'selected' : '' }}>Fixed</option>
                                    <option value="variable" {{ request('type') == 'variable' ? 'selected' : '' }}>Variable</option>
                                </select>
                            </div>
                            <div class="col-md-3">
                                <label for="status" class="form-label">Status</label>
                                <select name="status" id="status" class="form-select">
                                    <option value="">All Status</option>
                                    <option value="1" {{ request('status') == '1' ? 'selected' : '' }}>Active</option>
                                    <option value="0" {{ request('status') == '0' ? 'selected' : '' }}>Inactive</option>
                                </select>
                            </div>
                            <div class="col-md-2 d-flex align-items-end">
                                <button type="submit" class="btn btn-outline-primary me-2">Filter</button>
                                <a href="{{ route('hr.benefits.index') }}" class="btn btn-outline-secondary">Clear</a>
                            </div>
                        </div>
                    </form>

                    <!-- Benefits Table -->
                    <div class="table-responsive">
                        <table class="table table-striped table-hover">
                            <thead class="table-dark">
                                <tr>
                                    <th>Name</th>
                                    <th>Type</th>
                                    <th>Amount/Percentage</th>
                                    <th>Description</th>
                                    <th>Status</th>
                                    <th>Created</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($benefits as $benefit)
                                    <tr>
                                        <td>
                                            <div class="fw-bold">{{ $benefit->name }}</div>
                                        </td>
                                        <td>
                                            <span class="badge bg-{{ $benefit->type == 'monetary' ? 'success' : ($benefit->type == 'percentage' ? 'info' : ($benefit->type == 'fixed' ? 'warning' : 'secondary')) }}">
                                                {{ ucfirst($benefit->type) }}
                                            </span>
                                        </td>
                                        <td>
                                            @if($benefit->type == 'percentage')
                                                {{ $benefit->percentage }}%
                                            @elseif($benefit->amount)
                                                {{ number_format($benefit->amount, 2) }} {{ config('app.currency_symbol', 'KSh') }}
                                            @else
                                                <span class="text-muted">Not specified</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if($benefit->description)
                                                {{ Str::limit($benefit->description, 50) }}
                                            @else
                                                <span class="text-muted">No description</span>
                                            @endif
                                        </td>
                                        <td>
                                            <span class="badge bg-{{ $benefit->is_active ? 'success' : 'secondary' }}">
                                                {{ $benefit->is_active ? 'Active' : 'Inactive' }}
                                            </span>
                                        </td>
                                        <td>{{ $benefit->created_at->format('M d, Y') }}</td>
                                        <td>
                                            <div class="btn-group" role="group">
                                                <a href="{{ route('hr.benefits.show', $benefit->id) }}" class="btn btn-sm btn-info" title="View">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                                <a href="{{ route('hr.benefits.edit', $benefit->id) }}" class="btn btn-sm btn-warning" title="Edit">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                                <form action="{{ route('hr.benefits.destroy', $benefit->id) }}" method="POST" class="d-inline">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-sm btn-danger" 
                                                            onclick="return confirm('Are you sure you want to delete this benefit?')" title="Delete">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="7" class="text-center">No benefits found.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    @if($benefits->hasPages())
                        <div class="d-flex justify-content-center mt-4">
                            {{ $benefits->appends(request()->query())->links() }}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
