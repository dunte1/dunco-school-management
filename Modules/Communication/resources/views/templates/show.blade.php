@extends('communication::layouts.master')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1 class="mb-0">{{ $template->name }}</h1>
    <div class="d-flex gap-2">
        <a href="{{ route('communication.templates.edit', $template) }}" class="btn btn-outline-primary">
            <i class="fas fa-edit me-2"></i>Edit Template
        </a>
        <a href="{{ route('communication.templates.index') }}" class="btn btn-outline-secondary">
            <i class="fas fa-arrow-left me-2"></i>Back to Templates
        </a>
    </div>
</div>

<div class="row">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Template Details</h5>
            </div>
            <div class="card-body">
                <div class="row mb-3">
                    <div class="col-md-6">
                        <strong>Template Name:</strong>
                        <p>{{ $template->name }}</p>
                    </div>
                    <div class="col-md-6">
                        <strong>Type:</strong>
                        <p><span class="badge bg-info">{{ ucfirst($template->type) }}</span></p>
                    </div>
                </div>
                
                <div class="mb-3">
                    <strong>Subject:</strong>
                    <p>{{ $template->subject }}</p>
                </div>
                
                <div class="mb-3">
                    <strong>Template Body:</strong>
                    <div class="border rounded p-3 bg-light">
                        {!! nl2br(e($template->body)) !!}
                    </div>
                </div>
                
                <div class="mb-3">
                    <strong>Status:</strong>
                    <p>
                        <span class="badge {{ $template->is_active ? 'bg-success' : 'bg-secondary' }}">
                            {{ $template->is_active ? 'Active' : 'Inactive' }}
                        </span>
                    </p>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-md-4">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Template Information</h5>
            </div>
            <div class="card-body">
                <div class="mb-3">
                    <strong>Created By:</strong>
                    <p>{{ $template->creator->name ?? 'Unknown' }}</p>
                </div>
                
                <div class="mb-3">
                    <strong>Created At:</strong>
                    <p>{{ $template->created_at->format('F j, Y \a\t g:i A') }}</p>
                </div>
                
                <div class="mb-3">
                    <strong>Last Updated:</strong>
                    <p>{{ $template->updated_at->format('F j, Y \a\t g:i A') }}</p>
                </div>
                
                <div class="mb-3">
                    <strong>Actions:</strong>
                    <div class="d-grid gap-2">
                        <form method="POST" action="{{ route('communication.templates.toggle-status', $template) }}">
                            @csrf
                            <button type="submit" class="btn btn-outline-warning btn-sm">
                                <i class="fas fa-toggle-{{ $template->is_active ? 'off' : 'on' }} me-2"></i>
                                {{ $template->is_active ? 'Deactivate' : 'Activate' }}
                            </button>
                        </form>
                        
                        <form method="POST" action="{{ route('communication.templates.destroy', $template) }}" onsubmit="return confirm('Are you sure you want to delete this template?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-outline-danger btn-sm">
                                <i class="fas fa-trash me-2"></i>Delete Template
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="card mt-3">
            <div class="card-header">
                <h5 class="mb-0">Available Placeholders</h5>
            </div>
            <div class="card-body">
                <p class="small text-muted">These placeholders can be used in your template:</p>
                <ul class="list-unstyled small">
                    <li><code>{{name}}</code> - Recipient's name</li>
                    <li><code>{{email}}</code> - Recipient's email</li>
                    <li><code>{{school_name}}</code> - School name</li>
                    <li><code>{{date}}</code> - Current date</li>
                    <li><code>{{time}}</code> - Current time</li>
                    <li><code>{{user_name}}</code> - Current user's name</li>
                </ul>
            </div>
        </div>
    </div>
</div>
@endsection
