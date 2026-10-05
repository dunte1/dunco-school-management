@extends('layouts.app')

@section('title', 'Edit Ledger Entry - Finance Module')

@section('content')
<div class="container-fluid">
    <!-- Header Section -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h2 mb-0">Edit Ledger Entry</h1>
            <p class="text-muted mb-0">Update ledger entry information</p>
        </div>
        <div class="d-flex gap-2">
            <a href="{{ route('finance.ledger.index') }}" class="btn btn-outline-primary">
                <i class="fas fa-arrow-left me-2"></i>Back to Ledgers
            </a>
        </div>
    </div>

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

    <!-- Edit Entry Form -->
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card border-0 shadow-lg">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0"><i class="fas fa-edit me-2"></i>Ledger Entry Information</h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('finance.ledger.update', $entry->id) }}" method="POST">
                        @csrf
                        @method('PUT')
                        
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="account" class="form-label fw-bold">Account</label>
                                <input type="text" class="form-control" id="account" name="account" 
                                       value="{{ old('account', $entry->account) }}" required>
                            </div>
                            
                            <div class="col-md-6 mb-3">
                                <label for="type" class="form-label fw-bold">Type</label>
                                <select class="form-select" id="type" name="type" required>
                                    <option value="">Select Type</option>
                                    <option value="debit" {{ old('type', $entry->type) == 'debit' ? 'selected' : '' }}>Debit</option>
                                    <option value="credit" {{ old('type', $entry->type) == 'credit' ? 'selected' : '' }}>Credit</option>
                                </select>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="amount" class="form-label fw-bold">Amount (KES)</label>
                                <input type="number" class="form-control" id="amount" name="amount" 
                                       value="{{ old('amount', $entry->amount) }}" step="0.01" min="0" required>
                            </div>
                            
                            <div class="col-md-6 mb-3">
                                <label for="date" class="form-label fw-bold">Date</label>
                                <input type="date" class="form-control" id="date" name="date" 
                                       value="{{ old('date', $entry->date) }}" required>
                            </div>
                        </div>
                        
                        <div class="mb-3">
                            <label for="description" class="form-label fw-bold">Description</label>
                            <textarea class="form-control" id="description" name="description" rows="3" 
                                      placeholder="Enter entry description" required>{{ old('description', $entry->description) }}</textarea>
                        </div>
                        
                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save me-2"></i>Update Entry
                            </button>
                            <a href="{{ route('finance.ledger.show', $entry->id) }}" class="btn btn-outline-secondary">
                                <i class="fas fa-eye me-2"></i>View Entry
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
