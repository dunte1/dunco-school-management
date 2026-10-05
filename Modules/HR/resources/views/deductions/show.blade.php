@extends('layouts.app')

@section('title', 'Deduction Details')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h4 class="mb-0">Deduction Details</h4>
                    <div class="btn-group">
                        <a href="{{ route('hr.deductions.edit', $deduction->id) }}" class="btn btn-warning">
                            <i class="fas fa-edit me-2"></i>Edit
                        </a>
                        <a href="{{ route('hr.deductions.index') }}" class="btn btn-secondary">
                            <i class="fas fa-arrow-left me-2"></i>Back to List
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-8">
                            <div class="row mb-3">
                                <div class="col-sm-3">
                                    <strong>Deduction Name:</strong>
                                </div>
                                <div class="col-sm-9">
                                    <h5 class="mb-0">{{ $deduction->name }}</h5>
                                </div>
                            </div>
                            
                            <div class="row mb-3">
                                <div class="col-sm-3">
                                    <strong>Type:</strong>
                                </div>
                                <div class="col-sm-9">
                                    <span class="badge bg-{{ $deduction->type == 'tax' ? 'danger' : ($deduction->type == 'insurance' ? 'info' : ($deduction->type == 'loan' ? 'warning' : ($deduction->type == 'advance' ? 'primary' : 'secondary'))) }} fs-6">
                                        {{ ucfirst($deduction->type) }}
                                    </span>
                                </div>
                            </div>
                            
                            @if($deduction->type == 'percentage' || $deduction->percentage)
                            <div class="row mb-3">
                                <div class="col-sm-3">
                                    <strong>Percentage:</strong>
                                </div>
                                <div class="col-sm-9">
                                    <span class="badge bg-info fs-6">{{ $deduction->percentage }}%</span>
                                </div>
                            </div>
                            @elseif($deduction->amount)
                            <div class="row mb-3">
                                <div class="col-sm-3">
                                    <strong>Amount:</strong>
                                </div>
                                <div class="col-sm-9">
                                    <span class="badge bg-danger fs-6">{{ number_format($deduction->amount, 2) }} {{ config('app.currency_symbol', 'KSh') }}</span>
                                </div>
                            </div>
                            @endif
                            
                            <div class="row mb-3">
                                <div class="col-sm-3">
                                    <strong>Status:</strong>
                                </div>
                                <div class="col-sm-9">
                                    <span class="badge bg-{{ $deduction->is_active ? 'success' : 'secondary' }} fs-6">
                                        {{ $deduction->is_active ? 'Active' : 'Inactive' }}
                                    </span>
                                </div>
                            </div>
                            
                            @if($deduction->description)
                            <div class="row mb-3">
                                <div class="col-sm-3">
                                    <strong>Description:</strong>
                                </div>
                                <div class="col-sm-9">
                                    <div class="border rounded p-3 bg-light">
                                        {{ $deduction->description }}
                                    </div>
                                </div>
                            </div>
                            @endif
                            
                            <div class="row mb-3">
                                <div class="col-sm-3">
                                    <strong>Created:</strong>
                                </div>
                                <div class="col-sm-9">
                                    {{ $deduction->created_at->format('F d, Y \a\t g:i A') }}
                                </div>
                            </div>
                            
                            <div class="row mb-3">
                                <div class="col-sm-3">
                                    <strong>Last Updated:</strong>
                                </div>
                                <div class="col-sm-9">
                                    {{ $deduction->updated_at->format('F d, Y \a\t g:i A') }}
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
                                        <a href="{{ route('hr.deductions.edit', $deduction->id) }}" class="btn btn-warning">
                                            <i class="fas fa-edit me-2"></i>Edit Deduction
                                        </a>
                                        <a href="{{ route('hr.deductions.create') }}" class="btn btn-primary">
                                            <i class="fas fa-plus me-2"></i>Create New Deduction
                                        </a>
                                        <a href="{{ route('hr.deductions.index') }}" class="btn btn-outline-secondary">
                                            <i class="fas fa-list me-2"></i>View All Deductions
                                        </a>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="card mt-3">
                                <div class="card-header">
                                    <h6 class="mb-0">Deduction Summary</h6>
                                </div>
                                <div class="card-body">
                                    <div class="row text-center">
                                        <div class="col-6">
                                            <div class="border-end">
                                                <h6 class="text-muted mb-1">Type</h6>
                                                <span class="badge bg-{{ $deduction->type == 'tax' ? 'danger' : ($deduction->type == 'insurance' ? 'info' : ($deduction->type == 'loan' ? 'warning' : ($deduction->type == 'advance' ? 'primary' : 'secondary'))) }}">
                                                    {{ ucfirst($deduction->type) }}
                                                </span>
                                            </div>
                                        </div>
                                        <div class="col-6">
                                            <h6 class="text-muted mb-1">Status</h6>
                                            <span class="badge bg-{{ $deduction->is_active ? 'success' : 'secondary' }}">
                                                {{ $deduction->is_active ? 'Active' : 'Inactive' }}
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
