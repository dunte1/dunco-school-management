@extends('layouts.app')

@section('title', 'Deductions Management')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h4 class="mb-0">Deductions Management</h4>
                    <a href="{{ route('hr.deductions.create') }}" class="btn btn-primary">
                        <i class="fas fa-plus me-2"></i>New Deduction
                    </a>
                </div>
                <div class="card-body">
                    <!-- Search and Filter Form -->
                    <form method="GET" action="{{ route('hr.deductions.index') }}" class="mb-4">
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
                                    <option value="tax" {{ request('type') == 'tax' ? 'selected' : '' }}>Tax</option>
                                    <option value="insurance" {{ request('type') == 'insurance' ? 'selected' : '' }}>Insurance</option>
                                    <option value="loan" {{ request('type') == 'loan' ? 'selected' : '' }}>Loan</option>
                                    <option value="advance" {{ request('type') == 'advance' ? 'selected' : '' }}>Advance</option>
                                    <option value="other" {{ request('type') == 'other' ? 'selected' : '' }}>Other</option>
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
                                <a href="{{ route('hr.deductions.index') }}" class="btn btn-outline-secondary">Clear</a>
                            </div>
                        </div>
                    </form>

                    <!-- Deductions Table -->
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
                                @forelse($deductions as $deduction)
                                    <tr>
                                        <td>
                                            <div class="fw-bold">{{ $deduction->name }}</div>
                                        </td>
                                        <td>
                                            <span class="badge bg-{{ $deduction->type == 'tax' ? 'danger' : ($deduction->type == 'insurance' ? 'info' : ($deduction->type == 'loan' ? 'warning' : ($deduction->type == 'advance' ? 'primary' : 'secondary'))) }}">
                                                {{ ucfirst($deduction->type) }}
                                            </span>
                                        </td>
                                        <td>
                                            @if($deduction->type == 'percentage' || $deduction->percentage)
                                                {{ $deduction->percentage }}%
                                            @elseif($deduction->amount)
                                                {{ number_format($deduction->amount, 2) }} {{ config('app.currency_symbol', 'KSh') }}
                                            @else
                                                <span class="text-muted">Not specified</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if($deduction->description)
                                                {{ Str::limit($deduction->description, 50) }}
                                            @else
                                                <span class="text-muted">No description</span>
                                            @endif
                                        </td>
                                        <td>
                                            <span class="badge bg-{{ $deduction->is_active ? 'success' : 'secondary' }}">
                                                {{ $deduction->is_active ? 'Active' : 'Inactive' }}
                                            </span>
                                        </td>
                                        <td>{{ $deduction->created_at->format('M d, Y') }}</td>
                                        <td>
                                            <div class="btn-group" role="group">
                                                <a href="{{ route('hr.deductions.show', $deduction->id) }}" class="btn btn-sm btn-info" title="View">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                                <a href="{{ route('hr.deductions.edit', $deduction->id) }}" class="btn btn-sm btn-warning" title="Edit">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                                <form action="{{ route('hr.deductions.destroy', $deduction->id) }}" method="POST" class="d-inline">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-sm btn-danger" 
                                                            onclick="return confirm('Are you sure you want to delete this deduction?')" title="Delete">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="7" class="text-center">No deductions found.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    @if($deductions->hasPages())
                        <div class="d-flex justify-content-center mt-4">
                            {{ $deductions->appends(request()->query())->links() }}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
