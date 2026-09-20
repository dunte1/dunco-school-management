@extends('layouts.app')

@section('title', 'Edit Document')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4 class="mb-0"><i class="fas fa-file-pen me-2"></i>Edit Document</h4>
        <a href="{{ route('document.show', $document->id) }}" class="btn btn-outline-secondary"><i class="fas fa-arrow-left me-1"></i> Back</a>
    </div>

    <div class="card shadow-sm">
        <div class="card-body">
            <form action="{{ route('document.update', $document->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label">Title <span class="text-danger">*</span></label>
                            <input type="text" name="title" class="form-control" value="{{ old('title', $document->title) }}" required>
                            @error('title') <small class="text-danger">{{ $message }}</small> @enderror
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label">Replace File (optional)</label>
                            <input type="file" name="file" class="form-control">
                            <small class="text-muted">Current: {{ $document->file_name }}</small>
                        </div>
                    </div>
                </div>
                <div class="mb-3">
                    <label class="form-label">Description</label>
                    <textarea name="description" class="form-control" rows="3">{{ old('description', $document->description) }}</textarea>
                </div>
                <div class="row">
                    <div class="col-md-4">
                        <div class="mb-3">
                            <label class="form-label">Category</label>
                            <input type="text" name="category" class="form-control" list="catList" value="{{ old('category', $document->category) }}">
                            <datalist id="catList">
                                @foreach($categories as $cat)
                                    <option value="{{ $cat }}">
                                @endforeach
                            </datalist>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="mb-3">
                            <label class="form-label">Tags</label>
                            <input type="text" name="tags" class="form-control" value="{{ old('tags', is_array($document->tags) ? implode(', ', $document->tags) : '') }}" placeholder="Comma-separated tags">
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="mb-3">
                            <label class="form-label">Visibility <span class="text-danger">*</span></label>
                            <select name="visibility" class="form-select" required>
                                <option value="private" {{ old('visibility', $document->visibility) == 'private' ? 'selected' : '' }}>Private</option>
                                <option value="public" {{ old('visibility', $document->visibility) == 'public' ? 'selected' : '' }}>Public</option>
                                <option value="staff_only" {{ old('visibility', $document->visibility) == 'staff_only' ? 'selected' : '' }}>Staff Only</option>
                                <option value="students_only" {{ old('visibility', $document->visibility) == 'students_only' ? 'selected' : '' }}>Students Only</option>
                            </select>
                        </div>
                    </div>
                </div>
                <button type="submit" class="btn btn-primary"><i class="fas fa-save me-1"></i> Update Document</button>
            </form>
        </div>
    </div>
</div>
@endsection
