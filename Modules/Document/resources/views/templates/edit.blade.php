@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="mb-0">Edit Template</h2>
        <a href="{{ route('document.templates.index') }}" class="btn btn-outline-secondary">
            <i class="fas fa-arrow-left me-2"></i>Back to Templates
        </a>
    </div>

    <div class="row">
        <div class="col-md-8">
            <div class="card">
                <div class="card-body">
                    <form action="{{ route('document.templates.update', $template) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        
                        <div class="mb-3">
                            <label for="name" class="form-label">Template Name</label>
                            <input type="text" class="form-control @error('name') is-invalid @enderror" 
                                   id="name" name="name" value="{{ old('name', $template->name) }}" required>
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="description" class="form-label">Description</label>
                            <textarea class="form-control @error('description') is-invalid @enderror" 
                                      id="description" name="description" rows="3">{{ old('description', $template->description) }}</textarea>
                            @error('description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="category_id" class="form-label">Category</label>
                                    <select class="form-select @error('category_id') is-invalid @enderror" id="category_id" name="category_id">
                                        <option value="">Select a category...</option>
                                        @foreach($categories as $category)
                                        <option value="{{ $category->id }}" {{ old('category_id', $template->category_id) == $category->id ? 'selected' : '' }}>
                                            {{ $category->name }}
                                        </option>
                                        @endforeach
                                    </select>
                                    @error('category_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="type" class="form-label">Template Type</label>
                                    <select class="form-select @error('type') is-invalid @enderror" id="type" name="type" required>
                                        <option value="">Select type...</option>
                                        <option value="letter" {{ old('type', $template->type) == 'letter' ? 'selected' : '' }}>Letter</option>
                                        <option value="report" {{ old('type', $template->type) == 'report' ? 'selected' : '' }}>Report</option>
                                        <option value="form" {{ old('type', $template->type) == 'form' ? 'selected' : '' }}>Form</option>
                                        <option value="certificate" {{ old('type', $template->type) == 'certificate' ? 'selected' : '' }}>Certificate</option>
                                        <option value="other" {{ old('type', $template->type) == 'other' ? 'selected' : '' }}>Other</option>
                                    </select>
                                    @error('type')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Current Template File</label>
                            <div class="d-flex align-items-center p-3 bg-light rounded">
                                <i class="fas fa-file-{{ $template->file_type == 'pdf' ? 'pdf' : 'alt' }} me-3 text-primary fa-2x"></i>
                                <div>
                                    <h6 class="mb-0">{{ $template->file_name }}</h6>
                                    <small class="text-muted">{{ number_format($template->file_size / 1024, 2) }} KB</small>
                                </div>
                                <div class="ms-auto">
                                    <a href="{{ route('document.templates.download', $template) }}" class="btn btn-sm btn-outline-primary">
                                        <i class="fas fa-download"></i>
                                    </a>
                                </div>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="template_file" class="form-label">Replace Template File (Optional)</label>
                            <input type="file" class="form-control @error('template_file') is-invalid @enderror" 
                                   id="template_file" name="template_file" accept=".docx,.doc,.pdf,.txt">
                            <div class="form-text">Leave empty to keep current file. Supported formats: DOCX, DOC, PDF, TXT (Max: 10MB)</div>
                            @error('template_file')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        @if($template->preview_image)
                        <div class="mb-3">
                            <label class="form-label">Current Preview Image</label>
                            <div class="d-flex align-items-center p-3 bg-light rounded">
                                <img src="{{ Storage::url($template->preview_image) }}" alt="Preview" class="img-thumbnail me-3" style="max-width: 100px;">
                                <div>
                                    <h6 class="mb-0">Preview Image</h6>
                                    <small class="text-muted">Current preview image</small>
                                </div>
                            </div>
                        </div>
                        @endif

                        <div class="mb-3">
                            <label for="preview_image" class="form-label">Update Preview Image (Optional)</label>
                            <input type="file" class="form-control @error('preview_image') is-invalid @enderror" 
                                   id="preview_image" name="preview_image" accept="image/*">
                            <div class="form-text">Upload a new preview image (Max: 2MB)</div>
                            @error('preview_image')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="tags" class="form-label">Tags</label>
                            <input type="text" class="form-control @error('tags') is-invalid @enderror" 
                                   id="tags" name="tags" value="{{ old('tags', $template->tags) }}" 
                                   placeholder="Enter tags separated by commas">
                            <div class="form-text">Separate multiple tags with commas</div>
                            @error('tags')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" id="is_public" name="is_public" value="1" 
                                               {{ old('is_public', $template->is_public) ? 'checked' : '' }}>
                                        <label class="form-check-label" for="is_public">
                                            Public Template
                                        </label>
                                    </div>
                                    <div class="form-text">Make this template available to all users</div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" id="is_active" name="is_active" value="1" 
                                               {{ old('is_active', $template->is_active) ? 'checked' : '' }}>
                                        <label class="form-check-label" for="is_active">
                                            Active
                                        </label>
                                    </div>
                                    <div class="form-text">Enable this template for use</div>
                                </div>
                            </div>
                        </div>

                        <div class="d-flex justify-content-between">
                            <a href="{{ route('document.templates.index') }}" class="btn btn-outline-secondary">
                                <i class="fas fa-times me-2"></i>Cancel
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save me-2"></i>Update Template
                            </button>
                        </div>
                    </form>
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
                        <h6>Created</h6>
                        <p class="text-muted mb-0">{{ $template->created_at->format('M d, Y g:i A') }}</p>
                    </div>
                    
                    <div class="mb-3">
                        <h6>Last Updated</h6>
                        <p class="text-muted mb-0">{{ $template->updated_at->format('M d, Y g:i A') }}</p>
                    </div>
                    
                    <div class="mb-3">
                        <h6>Usage Count</h6>
                        <p class="text-muted mb-0">{{ $template->usage_count ?? 0 }} times</p>
                    </div>
                    
                    <div class="mb-3">
                        <h6>Status</h6>
                        <span class="badge bg-{{ $template->is_active ? 'success' : 'secondary' }}">
                            {{ $template->is_active ? 'Active' : 'Inactive' }}
                        </span>
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
