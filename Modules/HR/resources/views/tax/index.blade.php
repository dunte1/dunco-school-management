@extends('layouts.app')

@section('title', 'Tax Management')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h4 class="mb-0">Tax Management</h4>
                    <a href="{{ route('hr.tax.create') }}" class="btn btn-primary">
                        <i class="fas fa-plus me-2"></i>New Tax Rate
                    </a>
                </div>
                <div class="card-body">
                    <!-- Search and Filter Form -->
                    <form method="GET" action="{{ route('hr.tax.index') }}" class="mb-4">
                        <div class="row">
                            <div class="col-md-4">
                                <label for="search" class="form-label">Search</label>
                                <input type="text" name="search" id="search" class="form-control" 
                                       value="{{ request('search') }}" placeholder="Search by name or description...">
                            </div>
                            <div class="col-md-3">
                                <label for="status" class="form-label">Status</label>
                                <select name="status" id="status" class="form-select">
                                    <option value="">All Status</option>
                                    <option value="1" {{ request('status') == '1' ? 'selected' : '' }}>Active</option>
                                    <option value="0" {{ request('status') == '0' ? 'selected' : '' }}>Inactive</option>
                                </select>
                            </div>
                            <div class="col-md-3">
                                <label for="rate_range" class="form-label">Rate Range</label>
                                <select name="rate_range" id="rate_range" class="form-select">
                                    <option value="">All Rates</option>
                                    <option value="0-5" {{ request('rate_range') == '0-5' ? 'selected' : '' }}>0% - 5%</option>
                                    <option value="5-10" {{ request('rate_range') == '5-10' ? 'selected' : '' }}>5% - 10%</option>
                                    <option value="10-20" {{ request('rate_range') == '10-20' ? 'selected' : '' }}>10% - 20%</option>
                                    <option value="20+" {{ request('rate_range') == '20+' ? 'selected' : '' }}>20%+</option>
                                </select>
                            </div>
                            <div class="col-md-2 d-flex align-items-end">
                                <button type="submit" class="btn btn-outline-primary me-2">Filter</button>
                                <a href="{{ route('hr.tax.index') }}" class="btn btn-outline-secondary">Clear</a>
                            </div>
                        </div>
                    </form>

                    <!-- Tax Rates Table -->
                    <div class="table-responsive">
                        <table class="table table-striped table-hover">
                            <thead class="table-dark">
                                <tr>
                                    <th>Name</th>
                                    <th>Rate</th>
                                    <th>Amount Range</th>
                                    <th>Fixed Amount</th>
                                    <th>Description</th>
                                    <th>Status</th>
                                    <th>Created</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($taxes as $tax)
                                    <tr>
                                        <td>
                                            <div class="fw-bold">{{ $tax->name }}</div>
                                        </td>
                                        <td>
                                            <span class="badge bg-primary fs-6">{{ $tax->formatted_rate }}</span>
                                        </td>
                                        <td>
                                            <small class="text-muted">{{ $tax->formatted_range }}</small>
                                        </td>
                                        <td>
                                            @if($tax->fixed_amount > 0)
                                                {{ number_format($tax->fixed_amount, 2) }} {{ config('app.currency_symbol', 'KSh') }}
                                            @else
                                                <span class="text-muted">None</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if($tax->description)
                                                {{ Str::limit($tax->description, 50) }}
                                            @else
                                                <span class="text-muted">No description</span>
                                            @endif
                                        </td>
                                        <td>
                                            <span class="badge bg-{{ $tax->is_active ? 'success' : 'secondary' }}">
                                                {{ $tax->is_active ? 'Active' : 'Inactive' }}
                                            </span>
                                        </td>
                                        <td>{{ $tax->created_at->format('M d, Y') }}</td>
                                        <td>
                                            <div class="btn-group" role="group">
                                                <a href="{{ route('hr.tax.show', $tax->id) }}" class="btn btn-sm btn-info" title="View">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                                <a href="{{ route('hr.tax.edit', $tax->id) }}" class="btn btn-sm btn-warning" title="Edit">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                                <form action="{{ route('hr.tax.destroy', $tax->id) }}" method="POST" class="d-inline">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-sm btn-danger" 
                                                            onclick="return confirm('Are you sure you want to delete this tax rate?')" title="Delete">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="8" class="text-center">No tax rates found.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    @if($taxes->hasPages())
                        <div class="d-flex justify-content-center mt-4">
                            {{ $taxes->appends(request()->query())->links() }}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
