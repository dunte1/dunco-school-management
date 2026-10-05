@extends('layouts.app')

@section('title', 'Ledger Entry Details - Finance Module')

@section('content')
<div class="container-fluid">
    <!-- Header Section -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h2 mb-0">Ledger Entry Details</h1>
            <p class="text-muted mb-0">View ledger entry information and details</p>
        </div>
        <div class="d-flex gap-2">
            <a href="{{ route('finance.ledger.index') }}" class="btn btn-outline-primary">
                <i class="fas fa-arrow-left me-2"></i>Back to Ledgers
            </a>
            <a href="{{ route('finance.ledger.edit', $entry->id) }}" class="btn btn-primary">
                <i class="fas fa-edit me-2"></i>Edit Entry
            </a>
        </div>
    </div>

    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <!-- Entry Details Card -->
    <div class="row">
        <div class="col-lg-8">
            <div class="card border-0 shadow-lg">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0"><i class="fas fa-book me-2"></i>Ledger Entry Information</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <h6 class="fw-bold text-muted">Entry ID</h6>
                            <p class="mb-3">#{{ $entry->id ?? 'N/A' }}</p>
                            
                            <h6 class="fw-bold text-muted">Account</h6>
                            <p class="mb-3">{{ $entry->account ?? 'N/A' }}</p>
                            
                            <h6 class="fw-bold text-muted">Type</h6>
                            <p class="mb-3">
                                @if(isset($entry->type))
                                    @if($entry->type === 'debit')
                                        <span class="badge bg-danger fs-6">Debit</span>
                                    @elseif($entry->type === 'credit')
                                        <span class="badge bg-success fs-6">Credit</span>
                                    @else
                                        <span class="badge bg-secondary fs-6">{{ ucfirst($entry->type) }}</span>
                                    @endif
                                @else
                                    <span class="badge bg-secondary fs-6">Unknown</span>
                                @endif
                            </p>
                        </div>
                        <div class="col-md-6">
                            <h6 class="fw-bold text-muted">Amount</h6>
                            <p class="mb-3 h4 text-primary">KES {{ number_format($entry->amount ?? 0, 2) }}</p>
                            
                            <h6 class="fw-bold text-muted">Date</h6>
                            <p class="mb-3">{{ $entry->date ? \Carbon\Carbon::parse($entry->date)->format('M d, Y') : 'N/A' }}</p>
                            
                            <h6 class="fw-bold text-muted">Created</h6>
                            <p class="mb-3">{{ $entry->created_at ? \Carbon\Carbon::parse($entry->created_at)->format('M d, Y H:i') : 'N/A' }}</p>
                        </div>
                    </div>
                    
                    <div class="mt-4">
                        <h6 class="fw-bold text-muted">Description</h6>
                        <p class="mb-0">{{ $entry->description ?? 'No description provided' }}</p>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-lg-4">
            <div class="card border-0 shadow-lg">
                <div class="card-header bg-info text-white">
                    <h5 class="mb-0"><i class="fas fa-cogs me-2"></i>Actions</h5>
                </div>
                <div class="card-body">
                    <div class="d-grid gap-2">
                        <a href="#" class="btn btn-outline-primary">
                            <i class="fas fa-print me-2"></i>Print Entry
                        </a>
                        <a href="{{ route('finance.ledger.edit', $entry->id) }}" class="btn btn-outline-warning">
                            <i class="fas fa-edit me-2"></i>Edit Entry
                        </a>
                        <form action="{{ route('finance.ledger.destroy', $entry->id) }}" method="POST" class="d-inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-outline-danger w-100" onclick="return confirm('Are you sure you want to delete this entry?')">
                                <i class="fas fa-trash me-2"></i>Delete Entry
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
