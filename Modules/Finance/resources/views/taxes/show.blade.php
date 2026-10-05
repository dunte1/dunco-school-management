@extends('layouts.app')

@section('title', 'Tax Details - Finance Module')

@section('content')
<div class="container-fluid">
    <!-- Header Section -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h2 mb-0">Tax Details</h1>
            <p class="text-muted mb-0">View tax information</p>
        </div>
        <div class="d-flex gap-2">
            <a href="{{ route('finance.taxes.index') }}" class="btn btn-outline-primary">
                <i class="fas fa-arrow-left me-2"></i>Back to Taxes
            </a>
            <a href="{{ route('finance.taxes.edit', $tax) }}" class="btn btn-primary">
                <i class="fas fa-edit me-2"></i>Edit Tax
            </a>
        </div>
    </div>

    <!-- Tax Details Card -->
    <div class="row">
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white">
                    <h5 class="mb-0"><i class="fas fa-percentage me-2"></i>Tax Information</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label text-muted">Tax Name</label>
                                <p class="h5 mb-0">{{ $tax->name }}</p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label text-muted">Rate</label>
                                <p class="h4 text-primary mb-0">{{ $tax->rate }}%</p>
                            </div>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label text-muted">Type</label>
                                <p class="mb-0">
                                    <span class="badge bg-info">{{ ucfirst($tax->type) }}</span>
                                </p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label text-muted">Status</label>
                                <p class="mb-0">
                                    @if($tax->is_active)
                                        <span class="badge bg-success">Active</span>
                                    @else
                                        <span class="badge bg-secondary">Inactive</span>
                                    @endif
                                </p>
                            </div>
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label text-muted">Description</label>
                        <p class="mb-0">{{ $tax->description ?: 'No description provided' }}</p>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label text-muted">Created</label>
                                <p class="mb-0">{{ $tax->created_at->format('M d, Y \a\t g:i A') }}</p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label text-muted">Last Updated</label>
                                <p class="mb-0">{{ $tax->updated_at->format('M d, Y \a\t g:i A') }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-lg-4">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white">
                    <h5 class="mb-0"><i class="fas fa-chart-bar me-2"></i>Quick Actions</h5>
                </div>
                <div class="card-body">
                    <div class="d-grid gap-2">
                        <a href="{{ route('finance.taxes.edit', $tax) }}" class="btn btn-primary">
                            <i class="fas fa-edit me-2"></i>Edit Tax
                        </a>
                        <a href="{{ route('finance.taxes.index') }}" class="btn btn-outline-secondary">
                            <i class="fas fa-list me-2"></i>View All Taxes
                        </a>
                    </div>
                </div>
            </div>
            
            <div class="card border-0 shadow-sm mt-3">
                <div class="card-header bg-white">
                    <h5 class="mb-0"><i class="fas fa-info-circle me-2"></i>Tax Information</h5>
                </div>
                <div class="card-body">
                    <div class="text-center">
                        <div class="mb-2">
                            @if($tax->is_active)
                                <i class="fas fa-check-circle fa-2x text-success"></i>
                            @else
                                <i class="fas fa-times-circle fa-2x text-secondary"></i>
                            @endif
                        </div>
                        <p class="mb-0 {{ $tax->is_active ? 'text-success' : 'text-secondary' }}">
                            {{ $tax->is_active ? 'Active' : 'Inactive' }}
                        </p>
                        <small class="text-muted">
                            {{ $tax->is_active ? 'This tax is currently active' : 'This tax is currently inactive' }}
                        </small>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
