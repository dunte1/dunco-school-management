@extends('layouts.app')

@section('title', 'Benefit Details')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h4 class="mb-0">Benefit Details</h4>
                    <div class="btn-group">
                        <a href="{{ route('hr.benefits.edit', $benefit->id) }}" class="btn btn-warning">
                            <i class="fas fa-edit me-2"></i>Edit
                        </a>
                        <a href="{{ route('hr.benefits.index') }}" class="btn btn-secondary">
                            <i class="fas fa-arrow-left me-2"></i>Back to List
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-8">
                            <div class="row mb-3">
                                <div class="col-sm-3">
                                    <strong>Benefit Name:</strong>
                                </div>
                                <div class="col-sm-9">
                                    <h5 class="mb-0">{{ $benefit->name }}</h5>
                                </div>
                            </div>
                            
                            <div class="row mb-3">
                                <div class="col-sm-3">
                                    <strong>Type:</strong>
                                </div>
                                <div class="col-sm-9">
                                    <span class="badge bg-{{ $benefit->type == 'monetary' ? 'success' : ($benefit->type == 'percentage' ? 'info' : ($benefit->type == 'fixed' ? 'warning' : 'secondary')) }} fs-6">
                                        {{ ucfirst($benefit->type) }}
                                    </span>
                                </div>
                            </div>
                            
                            @if($benefit->type == 'percentage' && $benefit->percentage)
                            <div class="row mb-3">
                                <div class="col-sm-3">
                                    <strong>Percentage:</strong>
                                </div>
                                <div class="col-sm-9">
                                    <span class="badge bg-info fs-6">{{ $benefit->percentage }}%</span>
                                </div>
                            </div>
                            @elseif($benefit->amount)
                            <div class="row mb-3">
                                <div class="col-sm-3">
                                    <strong>Amount:</strong>
                                </div>
                                <div class="col-sm-9">
                                    <span class="badge bg-success fs-6">{{ number_format($benefit->amount, 2) }} {{ config('app.currency_symbol', 'KSh') }}</span>
                                </div>
                            </div>
                            @endif
                            
                            <div class="row mb-3">
                                <div class="col-sm-3">
                                    <strong>Status:</strong>
                                </div>
                                <div class="col-sm-9">
                                    <span class="badge bg-{{ $benefit->is_active ? 'success' : 'secondary' }} fs-6">
                                        {{ $benefit->is_active ? 'Active' : 'Inactive' }}
                                    </span>
                                </div>
                            </div>
                            
                            @if($benefit->description)
                            <div class="row mb-3">
                                <div class="col-sm-3">
                                    <strong>Description:</strong>
                                </div>
                                <div class="col-sm-9">
                                    <div class="border rounded p-3 bg-light">
                                        {{ $benefit->description }}
                                    </div>
                                </div>
                            </div>
                            @endif
                            
                            <div class="row mb-3">
                                <div class="col-sm-3">
                                    <strong>Created:</strong>
                                </div>
                                <div class="col-sm-9">
                                    {{ $benefit->created_at->format('F d, Y \a\t g:i A') }}
                                </div>
                            </div>
                            
                            <div class="row mb-3">
                                <div class="col-sm-3">
                                    <strong>Last Updated:</strong>
                                </div>
                                <div class="col-sm-9">
                                    {{ $benefit->updated_at->format('F d, Y \a\t g:i A') }}
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-md-4">
                            <div class="card">
                                <div class="card-header">
                                    <h6 class="mb-0">Quick Actions</h6>
                                </div>
                                <div class="card-body">
                                    <div class="d-grid gap-2">
                                        <a href="{{ route('hr.benefits.edit', $benefit->id) }}" class="btn btn-warning">
                                            <i class="fas fa-edit me-2"></i>Edit Benefit
                                        </a>
                                        <a href="{{ route('hr.benefits.create') }}" class="btn btn-primary">
                                            <i class="fas fa-plus me-2"></i>Create New Benefit
                                        </a>
                                        <a href="{{ route('hr.benefits.index') }}" class="btn btn-outline-secondary">
                                            <i class="fas fa-list me-2"></i>View All Benefits
                                        </a>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="card mt-3">
                                <div class="card-header">
                                    <h6 class="mb-0">Benefit Summary</h6>
                                </div>
                                <div class="card-body">
                                    <div class="row text-center">
                                        <div class="col-6">
                                            <div class="border-end">
                                                <h6 class="text-muted mb-1">Type</h6>
                                                <span class="badge bg-{{ $benefit->type == 'monetary' ? 'success' : ($benefit->type == 'percentage' ? 'info' : ($benefit->type == 'fixed' ? 'warning' : 'secondary')) }}">
                                                    {{ ucfirst($benefit->type) }}
                                                </span>
                                            </div>
                                        </div>
                                        <div class="col-6">
                                            <h6 class="text-muted mb-1">Status</h6>
                                            <span class="badge bg-{{ $benefit->is_active ? 'success' : 'secondary' }}">
                                                {{ $benefit->is_active ? 'Active' : 'Inactive' }}
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
