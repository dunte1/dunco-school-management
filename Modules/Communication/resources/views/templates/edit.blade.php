@extends('communication::layouts.master')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1 class="mb-0">Edit Template</h1>
    <div class="d-flex gap-2">
        <a href="{{ route('communication.templates.show', $template) }}" class="btn btn-outline-primary">
            <i class="fas fa-eye me-2"></i>View Template
        </a>
        <a href="{{ route('communication.templates.index') }}" class="btn btn-outline-secondary">
            <i class="fas fa-arrow-left me-2"></i>Back to Templates
        </a>
    </div>
</div>

<div class="card">
    <div class="card-body">
        <form method="POST" action="{{ route('communication.templates.update', $template) }}">
            @csrf
            @method('PUT')
            
            <div class="row">
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="name" class="form-label">Template Name *</label>
                        <input type="text" class="form-control @error('name') is-invalid @enderror" 
                               id="name" name="name" value="{{ old('name', $template->name) }}" required>
                        @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="type" class="form-label">Template Type *</label>
                        <select class="form-select @error('type') is-invalid @enderror" id="type" name="type" required>
                            <option value="">Select Type</option>
                            <option value="email" {{ old('type', $template->type) == 'email' ? 'selected' : '' }}>Email</option>
                            <option value="sms" {{ old('type', $template->type) == 'sms' ? 'selected' : '' }}>SMS</option>
                            <option value="notification" {{ old('type', $template->type) == 'notification' ? 'selected' : '' }}>Notification</option>
                        </select>
                        @error('type')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>
            
            <div class="mb-3">
                <label for="subject" class="form-label">Subject *</label>
                <input type="text" class="form-control @error('subject') is-invalid @enderror" 
                       id="subject" name="subject" value="{{ old('subject', $template->subject) }}" required>
                @error('subject')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
            
            <div class="mb-3">
                <label for="body" class="form-label">Template Body *</label>
                <textarea class="form-control @error('body') is-invalid @enderror" 
                          id="body" name="body" rows="10" required>{{ old('body', $template->body) }}</textarea>
                <div class="form-text">
                    You can use placeholders like {{name}}, {{email}}, {{school_name}}, etc. These will be replaced with actual values when the template is used.
                </div>
                @error('body')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
            
            <div class="mb-3">
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" id="is_active" name="is_active" value="1" 
                           {{ old('is_active', $template->is_active) ? 'checked' : '' }}>
                    <label class="form-check-label" for="is_active">
                        Active Template
                    </label>
                </div>
            </div>
            
            <div class="d-flex gap-2">
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save me-2"></i>Update Template
                </button>
                <a href="{{ route('communication.templates.show', $template) }}" class="btn btn-outline-secondary">Cancel</a>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
    // Simple preview functionality
    document.getElementById('body').addEventListener('input', function() {
        // You can add live preview functionality here
    });
</script>
@endpush
@endsection
