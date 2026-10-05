@extends('layouts.app')

@section('title', 'Position Details')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h4 class="mb-0">Position Details</h4>
                    <div class="btn-group">
                        <a href="{{ route('hr.positions.edit', $position->id) }}" class="btn btn-warning">
                            <i class="fas fa-edit me-2"></i>Edit
                        </a>
                        <a href="{{ route('hr.positions.index') }}" class="btn btn-secondary">
                            <i class="fas fa-arrow-left me-2"></i>Back to List
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-8">
                            <div class="row mb-3">
                                <div class="col-sm-3">
                                    <strong>Position Title:</strong>
                                </div>
                                <div class="col-sm-9">
                                    <h5 class="mb-0">{{ $position->title }}</h5>
                                </div>
                            </div>
                            
                            @if($position->department)
                            <div class="row mb-3">
                                <div class="col-sm-3">
                                    <strong>Department:</strong>
                                </div>
                                <div class="col-sm-9">
                                    <span class="badge bg-info fs-6">{{ $position->department->name }}</span>
                                </div>
                            </div>
                            @endif
                            
                            @if($position->salary_range)
                            <div class="row mb-3">
                                <div class="col-sm-3">
                                    <strong>Salary Range:</strong>
                                </div>
                                <div class="col-sm-9">
                                    <span class="badge bg-success fs-6">{{ $position->salary_range }}</span>
                                </div>
                            </div>
                            @endif
                            
                            <div class="row mb-3">
                                <div class="col-sm-3">
                                    <strong>Status:</strong>
                                </div>
                                <div class="col-sm-9">
                                    <span class="badge bg-{{ $position->is_active ? 'success' : 'secondary' }} fs-6">
                                        {{ $position->is_active ? 'Active' : 'Inactive' }}
                                    </span>
                                </div>
                            </div>
                            
                            @if($position->description)
                            <div class="row mb-3">
                                <div class="col-sm-3">
                                    <strong>Description:</strong>
                                </div>
                                <div class="col-sm-9">
                                    <div class="border rounded p-3 bg-light">
                                        {{ $position->description }}
                                    </div>
                                </div>
                            </div>
                            @endif
                            
                            @if($position->requirements)
                            <div class="row mb-3">
                                <div class="col-sm-3">
                                    <strong>Requirements:</strong>
                                </div>
                                <div class="col-sm-9">
                                    <div class="border rounded p-3 bg-light">
                                        {{ $position->requirements }}
                                    </div>
                                </div>
                            </div>
                            @endif
                            
                            <div class="row mb-3">
                                <div class="col-sm-3">
                                    <strong>Created:</strong>
                                </div>
                                <div class="col-sm-9">
                                    {{ $position->created_at->format('F d, Y \a\t g:i A') }}
                                </div>
                            </div>
                            
                            <div class="row mb-3">
                                <div class="col-sm-3">
                                    <strong>Last Updated:</strong>
                                </div>
                                <div class="col-sm-9">
                                    {{ $position->updated_at->format('F d, Y \a\t g:i A') }}
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
                                        <a href="{{ route('hr.positions.edit', $position->id) }}" class="btn btn-warning">
                                            <i class="fas fa-edit me-2"></i>Edit Position
                                        </a>
                                        <a href="{{ route('hr.staff.create') }}?position_id={{ $position->id }}" class="btn btn-primary">
                                            <i class="fas fa-user-plus me-2"></i>Add Staff to Position
                                        </a>
                                        <a href="{{ route('hr.positions.index') }}" class="btn btn-outline-secondary">
                                            <i class="fas fa-list me-2"></i>View All Positions
                                        </a>
                                    </div>
                                </div>
                            </div>
                            
                            @if($position->staff && $position->staff->count() > 0)
                            <div class="card mt-3">
                                <div class="card-header">
                                    <h6 class="mb-0">Staff in this Position</h6>
                                </div>
                                <div class="card-body">
                                    @foreach($position->staff as $staff)
                                        <div class="d-flex align-items-center mb-2">
                                            <div class="avatar-sm bg-primary text-white rounded-circle d-flex align-items-center justify-content-center me-2">
                                                {{ substr($staff->first_name, 0, 1) }}{{ substr($staff->last_name, 0, 1) }}
                                            </div>
                                            <div>
                                                <div class="fw-bold">{{ $staff->first_name }} {{ $staff->last_name }}</div>
                                                <small class="text-muted">{{ $staff->staff_id }}</small>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
