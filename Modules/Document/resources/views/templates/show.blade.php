@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="mb-0">Template Details</h2>
        <div>
            <a href="{{ route('document.templates.use', $template) }}" class="btn btn-success me-2">
                <i class="fas fa-copy me-2"></i>Use Template
            </a>
            <a href="{{ route('document.templates.edit', $template) }}" class="btn btn-warning me-2">
                <i class="fas fa-edit me-2"></i>Edit
            </a>
            <a href="{{ route('document.templates.index') }}" class="btn btn-outline-secondary">
                <i class="fas fa-arrow-left me-2"></i>Back to Templates
            </a>
        </div>
    </div>

    <div class="row">
        <div class="col-md-8">
            <div class="card">
                <div class="card-body">
                    <div class="row mb-4">
                        <div class="col-md-8">
                            <h3 class="card-title">{{ $template->name }}</h3>
                            <p class="text-muted">{{ $template->description }}</p>
                        </div>
                        <div class="col-md-4 text-end">
                            <span class="badge bg-{{ $template->is_active ? 'success' : 'secondary' }} fs-6">
                                {{ $template->is_active ? 'Active' : 'Inactive' }}
                            </span>
                            @if($template->is_public)
                                <span class="badge bg-info fs-6 ms-2">Public</span>
                            @endif
                        </div>
                    </div>

                    <div class="row mb-4">
                        <div class="col-md-6">
                            <h6>Template File</h6>
                            <div class="d-flex align-items-center p-3 bg-light rounded">
                                <i class="fas fa-file-{{ $template->file_type == 'pdf' ? 'pdf' : 'alt' }} me-3 text-primary fa-2x"></i>
                                <div>
                                    <h6 class="mb-0">{{ $template->file_name }}</h6>
                                    <small class="text-muted">{{ number_format($template->file_size / 1024, 2) }} KB</small>
                                </div>
                                <div class="ms-auto">
                                    <a href="{{ route('document.templates.download', $template) }}" class="btn btn-sm btn-outline-primary">
                                        <i class="fas fa-download me-2"></i>Download
                                    </a>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <h6>Template Type</h6>
                            <div class="d-flex align-items-center p-3 bg-light rounded">
                                <i class="fas fa-{{ $template->type == 'letter' ? 'envelope' : ($template->type == 'report' ? 'chart-bar' : ($template->type == 'form' ? 'list-alt' : 'certificate')) }} me-3 text-info fa-2x"></i>
                                <div>
                                    <h6 class="mb-0">{{ ucfirst($template->type) }}</h6>
                                    <small class="text-muted">Template Type</small>
                                </div>
                            </div>
                        </div>
                    </div>

                    @if($template->preview_image)
                    <div class="mb-4">
                        <h6>Preview</h6>
                        <div class="text-center">
                            <img src="{{ Storage::url($template->preview_image) }}" alt="Template Preview" class="img-fluid rounded shadow" style="max-height: 400px;">
                        </div>
                    </div>
                    @endif

                    @if($template->category)
                    <div class="mb-4">
                        <h6>Category</h6>
                        <span class="badge bg-info fs-6">{{ $template->category->name }}</span>
                    </div>
                    @endif

                    @if($template->tags)
                    <div class="mb-4">
                        <h6>Tags</h6>
                        <div>
                            @foreach(explode(',', $template->tags) as $tag)
                                <span class="badge bg-secondary me-1">{{ trim($tag) }}</span>
                            @endforeach
                        </div>
                    </div>
                    @endif

                    <div class="row">
                        <div class="col-md-6">
                            <h6>Created</h6>
                            <p class="text-muted">{{ $template->created_at->format('F d, Y \a\t g:i A') }}</p>
                        </div>
                        <div class="col-md-6">
                            <h6>Last Updated</h6>
                            <p class="text-muted">{{ $template->updated_at->format('F d, Y \a\t g:i A') }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Quick Actions</h5>
                </div>
                <div class="card-body">
                    <div class="d-grid gap-2">
                        <a href="{{ route('document.templates.use', $template) }}" class="btn btn-success">
                            <i class="fas fa-copy me-2"></i>Use This Template
                        </a>
                        
                        <a href="{{ route('document.templates.download', $template) }}" class="btn btn-outline-primary">
                            <i class="fas fa-download me-2"></i>Download Template
                        </a>
                        
                        <a href="{{ route('document.templates.edit', $template) }}" class="btn btn-outline-warning">
                            <i class="fas fa-edit me-2"></i>Edit Template
                        </a>
                        
                        <a href="{{ route('document.templates.duplicate', $template) }}" class="btn btn-outline-info">
                            <i class="fas fa-copy me-2"></i>Duplicate Template
                        </a>
                    </div>
                </div>
            </div>

            <div class="card mt-3">
                <div class="card-header">
                    <h5 class="mb-0">Template Statistics</h5>
                </div>
                <div class="card-body">
                    <div class="text-center mb-3">
                        <h4 class="text-primary">{{ $template->usage_count ?? 0 }}</h4>
                        <p class="text-muted mb-0">Times Used</p>
                    </div>
                    
                    <div class="text-center mb-3">
                        <h4 class="text-success">{{ $template->download_count ?? 0 }}</h4>
                        <p class="text-muted mb-0">Downloads</p>
                    </div>
                    
                    <div class="text-center">
                        <h4 class="text-info">{{ $template->view_count ?? 0 }}</h4>
                        <p class="text-muted mb-0">Views</p>
                    </div>
                </div>
            </div>

            <div class="card mt-3">
                <div class="card-header">
                    <h5 class="mb-0">Template Information</h5>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <h6>Created By</h6>
                        <p class="text-muted mb-0">{{ $template->created_by_user->name ?? 'Unknown' }}</p>
                    </div>
                    
                    <div class="mb-3">
                        <h6>File Size</h6>
                        <p class="text-muted mb-0">{{ number_format($template->file_size / 1024, 2) }} KB</p>
                    </div>
                    
                    <div class="mb-3">
                        <h6>File Type</h6>
                        <p class="text-muted mb-0">{{ strtoupper($template->file_type) }}</p>
                    </div>
                    
                    <div class="mb-3">
                        <h6>Version</h6>
                        <p class="text-muted mb-0">{{ $template->version ?? '1.0' }}</p>
                    </div>
                </div>
            </div>

            <div class="card mt-3">
                <div class="card-header">
                    <h5 class="mb-0">Danger Zone</h5>
                </div>
                <div class="card-body">
                    <p class="text-muted small">Once you delete this template, there is no going back. Please be certain.</p>
                    <form action="{{ route('document.templates.destroy', $template) }}" method="POST" class="d-inline">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger w-100" 
                                onclick="return confirm('Are you sure you want to delete this template? This action cannot be undone.')">
                            <i class="fas fa-trash me-2"></i>Delete Template
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
