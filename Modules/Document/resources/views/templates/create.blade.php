@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="mb-0">Create Document Template</h2>
        <a href="{{ route('document.templates.index') }}" class="btn btn-outline-secondary">
            <i class="fas fa-arrow-left me-2"></i>Back to Templates
        </a>
    </div>

    <div class="row">
        <div class="col-md-8">
            <div class="card">
                <div class="card-body">
                    <form action="{{ route('document.templates.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        
                        <div class="mb-3">
                            <label for="name" class="form-label">Template Name</label>
                            <input type="text" class="form-control @error('name') is-invalid @enderror" 
                                   id="name" name="name" value="{{ old('name') }}" required>
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="description" class="form-label">Description</label>
                            <textarea class="form-control @error('description') is-invalid @enderror" 
                                      id="description" name="description" rows="3">{{ old('description') }}</textarea>
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
                                        <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>
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
                                        <option value="letter" {{ old('type') == 'letter' ? 'selected' : '' }}>Letter</option>
                                        <option value="report" {{ old('type') == 'report' ? 'selected' : '' }}>Report</option>
                                        <option value="form" {{ old('type') == 'form' ? 'selected' : '' }}>Form</option>
                                        <option value="certificate" {{ old('type') == 'certificate' ? 'selected' : '' }}>Certificate</option>
                                        <option value="other" {{ old('type') == 'other' ? 'selected' : '' }}>Other</option>
                                    </select>
                                    @error('type')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="template_file" class="form-label">Template File</label>
                            <input type="file" class="form-control @error('template_file') is-invalid @enderror" 
                                   id="template_file" name="template_file" accept=".docx,.doc,.pdf,.txt" required>
                            <div class="form-text">Supported formats: DOCX, DOC, PDF, TXT (Max: 10MB)</div>
                            @error('template_file')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="preview_image" class="form-label">Preview Image (Optional)</label>
                            <input type="file" class="form-control @error('preview_image') is-invalid @enderror" 
                                   id="preview_image" name="preview_image" accept="image/*">
                            <div class="form-text">Upload a preview image for the template (Max: 2MB)</div>
                            @error('preview_image')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="tags" class="form-label">Tags</label>
                            <input type="text" class="form-control @error('tags') is-invalid @enderror" 
                                   id="tags" name="tags" value="{{ old('tags') }}" 
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
                                               {{ old('is_public') ? 'checked' : '' }}>
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
                                               {{ old('is_active', true) ? 'checked' : '' }}>
                                        <label class="form-check-label" for="is_active">
                                            Active
                                        </label>
                                    </div>
                                    <div class="form-text">Enable this template for use</div>
                                </div>
                            </div>
                        </div>

                        <div class="d-flex justify-content-end">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save me-2"></i>Create Template
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Template Guidelines</h5>
                </div>
                <div class="card-body">
                    <h6>File Requirements:</h6>
                    <ul class="list-unstyled small">
                        <li>• Maximum file size: 10MB</li>
                        <li>• Supported formats: DOCX, DOC, PDF, TXT</li>
                        <li>• Use placeholders like {{name}} for dynamic content</li>
                    </ul>
                    
                    <hr>
                    
                    <h6>Template Types:</h6>
                    <ul class="list-unstyled small">
                        <li>• <strong>Letter:</strong> Official correspondence</li>
                        <li>• <strong>Report:</strong> Data and analysis reports</li>
                        <li>• <strong>Form:</strong> Fillable forms and applications</li>
                        <li>• <strong>Certificate:</strong> Awards and certificates</li>
                    </ul>
                    
                    <hr>
                    
                    <h6>Best Practices:</h6>
                    <ul class="list-unstyled small">
                        <li>• Use clear, descriptive names</li>
                        <li>• Add helpful descriptions</li>
                        <li>• Tag templates appropriately</li>
                        <li>• Test templates before publishing</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
