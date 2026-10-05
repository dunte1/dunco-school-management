@extends('layouts.app')

@section('title', 'Edit Staff Document')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="mb-0">Edit Staff Document</h4>
                </div>
                <div class="card-body">
                    <form action="{{ route('hr.staff.documents.update', $document->id) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="staff_id" class="form-label">Staff Member <span class="text-danger">*</span></label>
                                    <select name="staff_id" id="staff_id" class="form-select @error('staff_id') is-invalid @enderror" required>
                                        <option value="">Select Staff Member</option>
                                        @foreach($staff as $staffMember)
                                            <option value="{{ $staffMember->id }}" {{ (old('staff_id', $document->staff_id) == $staffMember->id) ? 'selected' : '' }}>
                                                {{ $staffMember->first_name }} {{ $staffMember->last_name }} ({{ $staffMember->staff_id }})
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('staff_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="type" class="form-label">Document Type <span class="text-danger">*</span></label>
                                    <select name="type" id="type" class="form-select @error('type') is-invalid @enderror" required>
                                        <option value="">Select Document Type</option>
                                        <option value="contract" {{ old('type', $document->type) == 'contract' ? 'selected' : '' }}>Contract</option>
                                        <option value="id_card" {{ old('type', $document->type) == 'id_card' ? 'selected' : '' }}>ID Card</option>
                                        <option value="certificate" {{ old('type', $document->type) == 'certificate' ? 'selected' : '' }}>Certificate</option>
                                        <option value="other" {{ old('type', $document->type) == 'other' ? 'selected' : '' }}>Other</option>
                                    </select>
                                    @error('type')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-12">
                                <div class="mb-3">
                                    <label for="title" class="form-label">Document Title <span class="text-danger">*</span></label>
                                    <input type="text" name="title" id="title" class="form-control @error('title') is-invalid @enderror" 
                                           value="{{ old('title', $document->title) }}" required>
                                    @error('title')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-12">
                                <div class="mb-3">
                                    <label for="description" class="form-label">Description</label>
                                    <textarea name="description" id="description" class="form-control @error('description') is-invalid @enderror" 
                                              rows="3">{{ old('description', $document->description) }}</textarea>
                                    @error('description')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-12">
                                <div class="mb-3">
                                    <label for="file" class="form-label">Document File</label>
                                    <input type="file" name="file" id="file" class="form-control @error('file') is-invalid @enderror" 
                                           accept=".pdf,.doc,.docx,.jpg,.jpeg,.png">
                                    <div class="form-text">Accepted formats: PDF, DOC, DOCX, JPG, JPEG, PNG (Max: 10MB). Leave empty to keep current file.</div>
                                    @if($document->file_path)
                                        <div class="mt-2">
                                            <small class="text-muted">Current file: {{ basename($document->file_path) }}</small>
                                        </div>
                                    @endif
                                    @error('file')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="expiry_date" class="form-label">Expiry Date</label>
                                    <input type="date" name="expiry_date" id="expiry_date" class="form-control @error('expiry_date') is-invalid @enderror" 
                                           value="{{ old('expiry_date', $document->expiry_date ? $document->expiry_date->format('Y-m-d') : '') }}">
                                    @error('expiry_date')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <div class="form-check mt-4">
                                        <input type="checkbox" name="is_active" id="is_active" class="form-check-input" 
                                               value="1" {{ old('is_active', $document->is_active) ? 'checked' : '' }}>
                                        <label for="is_active" class="form-check-label">Active Document</label>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="d-flex justify-content-end gap-2">
                            <a href="{{ route('hr.staff.documents.show', $document->id) }}" class="btn btn-secondary">Cancel</a>
                            <button type="submit" class="btn btn-primary">Update Document</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
