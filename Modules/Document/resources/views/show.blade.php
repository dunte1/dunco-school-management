@extends('layouts.app')

@section('title', $document->title)

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4 class="mb-0"><i class="{{ $document->file_icon ?? 'fas fa-file' }} me-2"></i>{{ $document->title }}</h4>
        <div>
            <a href="{{ route('document.download', $document->id) }}" class="btn btn-success me-2"><i class="fas fa-download me-1"></i> Download</a>
            <a href="{{ route('document.edit', $document->id) }}" class="btn btn-warning me-2"><i class="fas fa-edit me-1"></i> Edit</a>
            <a href="{{ route('document.index') }}" class="btn btn-outline-secondary"><i class="fas fa-arrow-left me-1"></i> Back</a>
        </div>
    </div>

    <div class="row">
        <div class="col-md-8">
            <div class="card shadow-sm mb-4">
                <div class="card-header"><h5 class="mb-0">Document Details</h5></div>
                <div class="card-body">
                    <table class="table table-borderless">
                        <tr>
                            <th width="200">Title</th>
                            <td>{{ $document->title }}</td>
                        </tr>
                        <tr>
                            <th>Description</th>
                            <td>{{ $document->description ?? 'No description provided.' }}</td>
                        </tr>
                        <tr>
                            <th>File Name</th>
                            <td>{{ $document->file_name }}</td>
                        </tr>
                        <tr>
                            <th>File Type</th>
                            <td><span class="badge bg-secondary">{{ strtoupper($document->file_type ?? 'N/A') }}</span></td>
                        </tr>
                        <tr>
                            <th>File Size</th>
                            <td>{{ $document->formatted_size }}</td>
                        </tr>
                        <tr>
                            <th>Category</th>
                            <td>{{ $document->category ? ucfirst($document->category) : 'Uncategorized' }}</td>
                        </tr>
                        <tr>
                            <th>Tags</th>
                            <td>
                                @if($document->tags && count($document->tags))
                                    @foreach($document->tags as $tag)
                                        <span class="badge bg-light text-dark me-1">{{ $tag }}</span>
                                    @endforeach
                                @else
                                    <span class="text-muted">No tags</span>
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <th>Visibility</th>
                            <td>
                                <span class="badge bg-{{ $document->visibility === 'public' ? 'success' : ($document->visibility === 'private' ? 'danger' : 'warning') }}">
                                    {{ ucfirst(str_replace('_', ' ', $document->visibility)) }}
                                </span>
                            </td>
                        </tr>
                        <tr>
                            <th>Status</th>
                            <td>
                                <span class="badge bg-{{ $document->is_active ? 'success' : 'secondary' }}">
                                    {{ $document->is_active ? 'Active' : 'Inactive' }}
                                </span>
                            </td>
                        </tr>
                        <tr>
                            <th>Uploaded By</th>
                            <td>{{ $document->uploader->name ?? 'System' }}</td>
                        </tr>
                        <tr>
                            <th>Created</th>
                            <td>{{ $document->created_at->format('M d, Y h:i A') }}</td>
                        </tr>
                        <tr>
                            <th>Last Updated</th>
                            <td>{{ $document->updated_at->format('M d, Y h:i A') }}</td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card shadow-sm mb-4">
                <div class="card-header"><h5 class="mb-0">Quick Actions</h5></div>
                <div class="card-body d-grid gap-2">
                    <a href="{{ route('document.download', $document->id) }}" class="btn btn-success">
                        <i class="fas fa-download me-1"></i> Download File
                    </a>
                    <a href="{{ route('document.edit', $document->id) }}" class="btn btn-warning">
                        <i class="fas fa-edit me-1"></i> Edit Details
                    </a>
                    <form action="{{ route('document.toggle-status', $document->id) }}" method="POST">
                        @csrf
                        <button class="btn btn-info w-100">
                            <i class="fas fa-toggle-{{ $document->is_active ? 'on' : 'off' }} me-1"></i>
                            {{ $document->is_active ? 'Deactivate' : 'Activate' }} Document
                        </button>
                    </form>
                    <form action="{{ route('document.destroy', $document->id) }}" method="POST" onsubmit="return confirm('Permanently delete this document?')">
                        @csrf @method('DELETE')
                        <button class="btn btn-danger w-100">
                            <i class="fas fa-trash me-1"></i> Delete Document
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
