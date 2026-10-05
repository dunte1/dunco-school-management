@extends('layouts.app')

@section('title', 'Staff Contract Details')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h4 class="mb-0">Contract Details</h4>
                    <div class="btn-group">
                        <a href="{{ route('hr.staff.contracts.edit', $contract->id) }}" class="btn btn-warning">
                            <i class="fas fa-edit me-2"></i>Edit
                        </a>
                        <a href="{{ route('hr.staff.contracts.index') }}" class="btn btn-secondary">
                            <i class="fas fa-arrow-left me-2"></i>Back to List
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-8">
                            <div class="row mb-3">
                                <div class="col-sm-3">
                                    <strong>Staff Member:</strong>
                                </div>
                                <div class="col-sm-9">
                                    <div class="d-flex align-items-center">
                                        <div class="avatar-sm bg-primary text-white rounded-circle d-flex align-items-center justify-content-center me-2">
                                            {{ substr($contract->staff->first_name, 0, 1) }}{{ substr($contract->staff->last_name, 0, 1) }}
                                        </div>
                                        <div>
                                            <div class="fw-bold">{{ $contract->staff->first_name }} {{ $contract->staff->last_name }}</div>
                                            <small class="text-muted">{{ $contract->staff->staff_id }}</small>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="row mb-3">
                                <div class="col-sm-3">
                                    <strong>Position:</strong>
                                </div>
                                <div class="col-sm-9">
                                    {{ $contract->position }}
                                </div>
                            </div>
                            
                            <div class="row mb-3">
                                <div class="col-sm-3">
                                    <strong>Department:</strong>
                                </div>
                                <div class="col-sm-9">
                                    {{ $contract->department->name ?? 'N/A' }}
                                </div>
                            </div>
                            
                            <div class="row mb-3">
                                <div class="col-sm-3">
                                    <strong>Contract Type:</strong>
                                </div>
                                <div class="col-sm-9">
                                    <span class="badge bg-{{ $contract->contract_type == 'permanent' ? 'success' : ($contract->contract_type == 'temporary' ? 'warning' : ($contract->contract_type == 'probation' ? 'info' : 'secondary')) }}">
                                        {{ ucfirst($contract->contract_type) }}
                                    </span>
                                </div>
                            </div>
                            
                            <div class="row mb-3">
                                <div class="col-sm-3">
                                    <strong>Start Date:</strong>
                                </div>
                                <div class="col-sm-9">
                                    {{ \Carbon\Carbon::parse($contract->start_date)->format('F d, Y') }}
                                </div>
                            </div>
                            
                            @if($contract->end_date)
                            <div class="row mb-3">
                                <div class="col-sm-3">
                                    <strong>End Date:</strong>
                                </div>
                                <div class="col-sm-9">
                                    <span class="badge bg-{{ $contract->end_date < now() ? 'danger' : ($contract->end_date < now()->addDays(30) ? 'warning' : 'success') }}">
                                        {{ \Carbon\Carbon::parse($contract->end_date)->format('F d, Y') }}
                                    </span>
                                </div>
                            </div>
                            @endif
                            
                            @if($contract->salary)
                            <div class="row mb-3">
                                <div class="col-sm-3">
                                    <strong>Salary:</strong>
                                </div>
                                <div class="col-sm-9">
                                    {{ number_format($contract->salary, 2) }} {{ config('app.currency_symbol', 'KSh') }}
                                </div>
                            </div>
                            @endif
                            
                            <div class="row mb-3">
                                <div class="col-sm-3">
                                    <strong>Status:</strong>
                                </div>
                                <div class="col-sm-9">
                                    <span class="badge bg-{{ $contract->status == 'active' ? 'success' : ($contract->status == 'inactive' ? 'secondary' : ($contract->status == 'expired' ? 'danger' : 'warning')) }}">
                                        {{ ucfirst($contract->status) }}
                                    </span>
                                </div>
                            </div>
                            
                            @if($contract->description)
                            <div class="row mb-3">
                                <div class="col-sm-3">
                                    <strong>Description:</strong>
                                </div>
                                <div class="col-sm-9">
                                    {{ $contract->description }}
                                </div>
                            </div>
                            @endif
                            
                            <div class="row mb-3">
                                <div class="col-sm-3">
                                    <strong>Created:</strong>
                                </div>
                                <div class="col-sm-9">
                                    {{ $contract->created_at->format('F d, Y \a\t g:i A') }}
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-md-4">
                            <div class="card">
                                <div class="card-header">
                                    <h6 class="mb-0">Contract Document</h6>
                                </div>
                                <div class="card-body text-center">
                                    @if($contract->document_path)
                                        <div class="mb-3">
                                            <i class="fas fa-file-pdf fa-3x text-danger"></i>
                                        </div>
                                        <p class="mb-3">{{ basename($contract->document_path) }}</p>
                                        <a href="{{ Storage::url($contract->document_path) }}" target="_blank" class="btn btn-primary">
                                            <i class="fas fa-download me-2"></i>Download
                                        </a>
                                    @else
                                        <div class="text-muted">
                                            <i class="fas fa-file fa-3x"></i>
                                            <p class="mt-2">No document available</p>
                                        </div>
                                    @endif
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
