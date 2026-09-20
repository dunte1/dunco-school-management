@extends('layouts.app')

@section('title', 'Documents')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4 class="mb-0"><i class="fas fa-folder-open me-2"></i>Document Management</h4>
        <div>
            <a href="{{ route('document.upload') }}" class="btn btn-primary me-2"><i class="fas fa-cloud-upload-alt me-1"></i> Upload</a>
            <a href="{{ route('document.create') }}" class="btn btn-success"><i class="fas fa-plus me-1"></i> New Document</a>
        </div>
    </div>

    <div class="card shadow-sm mb-4">
        <div class="card-body">
            <form method="GET" class="row g-3">
                <div class="col-md-3">
                    <label class="form-label">Search</label>
                    <input type="text" name="search" class="form-control" placeholder="Search documents..." value="{{ request('search') }}">
                </div>
                <div class="col-md-2">
                    <label class="form-label">Category</label>
                    <select name="category" class="form-select">
                        <option value="">All</option>
                        @foreach($categories as $cat)
                            <option value="{{ $cat }}" {{ request('category') == $cat ? 'selected' : '' }}>{{ ucfirst($cat) }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2">
                    <label class="form-label">Visibility</label>
                    <select name="visibility" class="form-select">
                        <option value="">All</option>
                        <option value="public" {{ request('visibility') == 'public' ? 'selected' : '' }}>Public</option>
                        <option value="private" {{ request('visibility') == 'private' ? 'selected' : '' }}>Private</option>
                        <option value="staff_only" {{ request('visibility') == 'staff_only' ? 'selected' : '' }}>Staff Only</option>
                        <option value="students_only" {{ request('visibility') == 'students_only' ? 'selected' : '' }}>Students Only</option>
                    </select>
                </div>
                <div class="col-md-2 d-flex align-items-end">
                    <button type="submit" class="btn btn-primary me-2"><i class="fas fa-filter me-1"></i> Filter</button>
                    <a href="{{ route('document.index') }}" class="btn btn-outline-secondary">Reset</a>
                </div>
            </form>
        </div>
    </div>

    <div class="row">
        @forelse($documents as $doc)
        <div class="col-md-4 col-lg-3 mb-4">
            <div class="card shadow-sm h-100">
                <div class="card-body d-flex flex-column">
                    <div class="text-center mb-3">
                        <i class="{{ $doc->file_icon ?? 'fas fa-file' }}" style="font-size: 3rem;"></i>
                    </div>
                    <h6 class="card-title text-truncate" title="{{ $doc->title }}">{{ $doc->title }}</h6>
                    <p class="card-text text-muted small flex-grow-1">
                        {{ Str::limit($doc->description ?? 'No description', 60) }}
                    </p>
                    <div class="d-flex justify-content-between align-items-center">
                        <small class="text-muted">{{ $doc->formatted_size ?? '' }}</small>
                        @if($doc->category)
                            <span class="badge bg-light text-dark">{{ ucfirst($doc->category) }}</span>
                        @endif
                    </div>
                    <div class="mt-2">
                        <span class="badge bg-{{ $doc->visibility === 'public' ? 'success' : ($doc->visibility === 'private' ? 'danger' : 'warning') }}">
                            {{ ucfirst(str_replace('_', ' ', $doc->visibility)) }}
                        </span>
                    </div>
                </div>
                <div class="card-footer bg-white d-flex justify-content-between">
                    <a href="{{ route('document.show', $doc->id) }}" class="btn btn-sm btn-outline-primary"><i class="fas fa-eye"></i></a>
                    <a href="{{ route('document.download', $doc->id) }}" class="btn btn-sm btn-outline-success"><i class="fas fa-download"></i></a>
                    <a href="{{ route('document.edit', $doc->id) }}" class="btn btn-sm btn-outline-warning"><i class="fas fa-edit"></i></a>
                    <form action="{{ route('document.destroy', $doc->id) }}" method="POST" onsubmit="return confirm('Delete this document?')">
                        @csrf @method('DELETE')
                        <button class="btn btn-sm btn-outline-danger"><i class="fas fa-trash"></i></button>
                    </form>
                </div>
            </div>
        </div>
        @empty
        <div class="col-12">
            <div class="text-center py-5">
                <i class="fas fa-folder-open fa-3x text-muted mb-3"></i>
                <p class="text-muted">No documents found. Upload your first document to get started.</p>
                <a href="{{ route('document.upload') }}" class="btn btn-primary"><i class="fas fa-cloud-upload-alt me-1"></i> Upload Document</a>
            </div>
        </div>
        @endforelse
    </div>

    <div class="d-flex justify-content-center">
        {{ $documents->withQueryString()->links() }}
    </div>
</div>
@endsection
