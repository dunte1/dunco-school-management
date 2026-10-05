@extends('layouts.app')

@section('title', 'Staff Document Details')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h4 class="mb-0">Document Details</h4>
                    <div class="btn-group">
                        <a href="{{ route('hr.staff.documents.edit', $document->id) }}" class="btn btn-warning">
                            <i class="fas fa-edit me-2"></i>Edit
                        </a>
                        <a href="{{ route('hr.staff.documents.index') }}" class="btn btn-secondary">
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
                                            {{ substr($document->staff->first_name, 0, 1) }}{{ substr($document->staff->last_name, 0, 1) }}
                                        </div>
                                        <div>
                                            <div class="fw-bold">{{ $document->staff->first_name }} {{ $document->staff->last_name }}</div>
                                            <small class="text-muted">{{ $document->staff->staff_id }}</small>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="row mb-3">
                                <div class="col-sm-3">
                                    <strong>Document Type:</strong>
                                </div>
                                <div class="col-sm-9">
                                    <span class="badge bg-{{ $document->type == 'contract' ? 'success' : ($document->type == 'id_card' ? 'info' : ($document->type == 'certificate' ? 'warning' : 'secondary')) }}">
                                        {{ ucfirst(str_replace('_', ' ', $document->type)) }}
                                    </span>
                                </div>
                            </div>
                            
                            <div class="row mb-3">
                                <div class="col-sm-3">
                                    <strong>Title:</strong>
                                </div>
                                <div class="col-sm-9">
                                    {{ $document->title }}
                                </div>
                            </div>
                            
                            @if($document->description)
                            <div class="row mb-3">
                                <div class="col-sm-3">
                                    <strong>Description:</strong>
                                </div>
                                <div class="col-sm-9">
                                    {{ $document->description }}
                                </div>
                            </div>
                            @endif
                            
                            <div class="row mb-3">
                                <div class="col-sm-3">
                                    <strong>Upload Date:</strong>
                                </div>
                                <div class="col-sm-9">
                                    {{ $document->created_at->format('F d, Y \a\t g:i A') }}
                                </div>
                            </div>
                            
                            @if($document->expiry_date)
                            <div class="row mb-3">
                                <div class="col-sm-3">
                                    <strong>Expiry Date:</strong>
                                </div>
                                <div class="col-sm-9">
                                    <span class="badge bg-{{ $document->expiry_date < now() ? 'danger' : ($document->expiry_date < now()->addDays(30) ? 'warning' : 'success') }}">
                                        {{ \Carbon\Carbon::parse($document->expiry_date)->format('F d, Y') }}
                                    </span>
                                </div>
                            </div>
                            @endif
                            
                            <div class="row mb-3">
                                <div class="col-sm-3">
                                    <strong>Status:</strong>
                                </div>
                                <div class="col-sm-9">
                                    <span class="badge bg-{{ $document->is_active ? 'success' : 'secondary' }}">
                                        {{ $document->is_active ? 'Active' : 'Inactive' }}
                                    </span>
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-md-4">
                            <div class="card">
                                <div class="card-header">
                                    <h6 class="mb-0">Document File</h6>
                                </div>
                                <div class="card-body text-center">
                                    @if($document->file_path)
                                        <div class="mb-3">
                                            <i class="fas fa-file-pdf fa-3x text-danger"></i>
                                        </div>
                                        <p class="mb-3">{{ basename($document->file_path) }}</p>
                                        <a href="{{ Storage::url($document->file_path) }}" target="_blank" class="btn btn-primary">
                                            <i class="fas fa-download me-2"></i>Download
                                        </a>
                                    @else
                                        <div class="text-muted">
                                            <i class="fas fa-file fa-3x"></i>
                                            <p class="mt-2">No file available</p>
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
