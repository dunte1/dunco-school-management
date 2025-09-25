@extends('finance::layouts.app')

@section('title', 'Fee Structures - Finance Module')

@section('content')
<div class="container-fluid">
    <!-- Header Section -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h2 mb-0">Fee Structures</h1>
            <p class="text-muted mb-0">Manage school fees and fee categories</p>
        </div>
        <div class="d-flex gap-2">
            <a href="{{ route('finance.fee-types.index') }}" class="btn btn-success">
                <i class="fas fa-list me-2"></i>Manage Fee Types
            </a>
            <a href="{{ route('finance.fee-categories.index') }}" class="btn btn-info">
                <i class="fas fa-tags me-2"></i>Manage Categories
            </a>
            <a href="{{ route('finance.fees.create') }}" class="btn btn-primary">
                <i class="fas fa-plus me-2"></i>Add Fee
            </a>
        </div>
    </div>

    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif
    
    @if ($errors->any())
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="fas fa-exclamation-triangle me-2"></i>
            <ul class="mb-0 ps-3">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <!-- Fees Table -->
    <div class="card border-0 shadow-sm">
        <div class="card-header bg-white">
            <h5 class="mb-0"><i class="fas fa-layer-group me-2"></i>Fee Structures List</h5>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead class="table-light">
                        <tr>
                            <th class="px-4 py-3">Name</th>
                            <th class="px-4 py-3">Category</th>
                            <th class="px-4 py-3">Type</th>
                            <th class="px-4 py-3">Amount</th>
                            <th class="px-4 py-3 text-center">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($fees as $fee)
                        <tr>
                            <td class="px-4 py-3">
                                <div>
                                    <strong>{{ $fee->name }}</strong>
                                    @if($fee->description)
                                        <br><small class="text-muted">{{ $fee->description }}</small>
                                    @endif
                                </div>
                            </td>
                            <td class="px-4 py-3">
                                <span class="badge bg-primary">{{ $fee->category->name ?? 'Uncategorized' }}</span>
                            </td>
                            <td class="px-4 py-3">
                                <span class="badge bg-info">{{ $fee->type->name ?? 'No Type' }}</span>
                            </td>
                            <td class="px-4 py-3">
                                <strong class="text-success">KES {{ number_format($fee->amount, 2) }}</strong>
                            </td>
                            <td class="px-4 py-3 text-center">
                                <div class="btn-group" role="group">
                                    <a href="{{ route('finance.fees.edit', $fee) }}" class="btn btn-sm btn-outline-primary" title="Edit">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <form action="{{ route('finance.fees.destroy', $fee) }}" method="POST" class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-outline-danger" onclick="return confirm('Are you sure you want to delete this fee?')" title="Delete">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="text-center py-5">
                                <div class="text-muted">
                                    <i class="fas fa-inbox fa-3x mb-3"></i>
                                    <h5>No Fee Structures Found</h5>
                                    <p>Start by creating your first fee structure.</p>
                                    <a href="{{ route('finance.fees.create') }}" class="btn btn-primary">
                                        <i class="fas fa-plus me-2"></i>Add First Fee
                                    </a>
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection 