@extends('layouts.app')

@section('title', 'Upload Document')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4 class="mb-0"><i class="fas fa-cloud-upload-alt me-2"></i>Upload Document</h4>
        <a href="{{ route('document.index') }}" class="btn btn-outline-secondary"><i class="fas fa-arrow-left me-1"></i> Back</a>
    </div>

    <div class="card shadow-sm">
        <div class="card-body">
            <form action="{{ route('document.upload.handle') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="row">
                    <div class="col-md-8">
                        <div class="mb-3">
                            <label class="form-label">Select Files <span class="text-danger">*</span></label>
                            <input type="file" name="files[]" class="form-control" multiple required>
                            <small class="text-muted">You can select up to 10 files. Max 50MB each.</small>
                            @error('files') <small class="text-danger">{{ $message }}</small> @enderror
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="mb-3">
                            <label class="form-label">Category</label>
                            <input type="text" name="category" class="form-control" list="categoryList" placeholder="e.g. syllabus, policy">
                            <datalist id="categoryList">
                                @foreach($categories as $cat)
                                    <option value="{{ $cat }}">
                                @endforeach
                            </datalist>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Visibility <span class="text-danger">*</span></label>
                            <select name="visibility" class="form-select" required>
                                <option value="private">Private</option>
                                <option value="public">Public</option>
                                <option value="staff_only">Staff Only</option>
                                <option value="students_only">Students Only</option>
                            </select>
                        </div>
                    </div>
                </div>

                <div class="alert alert-info">
                    <i class="fas fa-info-circle me-1"></i>
                    Supported file types: PDF, Word, Excel, PowerPoint, Images, Videos, Text files.
                </div>

                <button type="submit" class="btn btn-primary"><i class="fas fa-upload me-1"></i> Upload Files</button>
            </form>
        </div>
    </div>
</div>
@endsection
