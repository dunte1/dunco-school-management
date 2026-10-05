@extends('layouts.app')

@section('title', 'Import Questions')

@section('content')
<div class="container-xl py-4">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="card shadow-sm">
                <div class="card-header">
                    <h4 class="mb-0">Import Questions</h4>
                    <p class="text-muted mb-0">Bulk import questions from CSV or Excel files</p>
                </div>
                <div class="card-body">
                    <!-- Import Form -->
                    <form action="{{ route('examination.questions.import.import') }}" method="POST" enctype="multipart/form-data" id="importForm">
                        @csrf
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="file" class="form-label">Select File <span class="text-danger">*</span></label>
                                    <input type="file" class="form-control @error('file') is-invalid @enderror" 
                                           id="file" name="file" accept=".csv,.xlsx,.xls" required>
                                    @error('file')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <div class="form-text">Supported formats: CSV, Excel (.xlsx, .xls). Max size: 10MB</div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="category_id" class="form-label">Category <span class="text-danger">*</span></label>
                                    <select class="form-select @error('category_id') is-invalid @enderror" 
                                            id="category_id" name="category_id" required>
                                        <option value="">Select Category</option>
                                        @foreach(\Modules\Examination\Models\QuestionCategory::all() as $category)
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
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="difficulty" class="form-label">Default Difficulty <span class="text-danger">*</span></label>
                                    <select class="form-select @error('difficulty') is-invalid @enderror" 
                                            id="difficulty" name="difficulty" required>
                                        <option value="easy" {{ old('difficulty') == 'easy' ? 'selected' : '' }}>Easy</option>
                                        <option value="medium" {{ old('difficulty') == 'medium' ? 'selected' : '' }}>Medium</option>
                                        <option value="hard" {{ old('difficulty') == 'hard' ? 'selected' : '' }}>Hard</option>
                                    </select>
                                    @error('difficulty')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <div class="form-check mt-4">
                                        <input class="form-check-input" type="checkbox" id="overwrite" name="overwrite" value="1" {{ old('overwrite') ? 'checked' : '' }}>
                                        <label class="form-check-label" for="overwrite">
                                            Overwrite existing questions
                                        </label>
                                        <div class="form-text">If checked, questions with the same text will be updated instead of skipped</div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="d-flex justify-content-between">
                            <div>
                                <a href="{{ route('examination.questions.index') }}" class="btn btn-outline-secondary">
                                    <i class="fas fa-arrow-left me-2"></i>Back to Questions
                                </a>
                            </div>
                            <div>
                                <button type="button" class="btn btn-outline-info me-2" id="validateBtn">
                                    <i class="fas fa-check-circle me-2"></i>Validate File
                                </button>
                                <button type="submit" class="btn btn-primary" id="importBtn">
                                    <i class="fas fa-upload me-2"></i>Import Questions
                                </button>
                            </div>
                        </div>
                    </form>

                    <!-- Template Downloads -->
                    <hr class="my-4">
                    <div class="row">
                        <div class="col-md-6">
                            <h6>Download Templates</h6>
                            <p class="text-muted small">Download sample files to see the correct format</p>
                            <div class="btn-group" role="group">
                                <a href="{{ route('examination.questions.import.template', 'xlsx') }}" class="btn btn-outline-success btn-sm">
                                    <i class="fas fa-file-excel me-2"></i>Excel Template
                                </a>
                                <a href="{{ route('examination.questions.import.template', 'csv') }}" class="btn btn-outline-info btn-sm">
                                    <i class="fas fa-file-csv me-2"></i>CSV Template
                                </a>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <h6>Supported Question Types</h6>
                            <div class="row">
                                <div class="col-6">
                                    <ul class="list-unstyled small">
                                        <li><span class="badge bg-primary me-1">MCQ</span> Multiple Choice</li>
                                        <li><span class="badge bg-primary me-1">TF</span> True/False</li>
                                        <li><span class="badge bg-primary me-1">Essay</span> Essay Questions</li>
                                        <li><span class="badge bg-primary me-1">Fill</span> Fill in the Blank</li>
                                    </ul>
                                </div>
                                <div class="col-6">
                                    <ul class="list-unstyled small">
                                        <li><span class="badge bg-primary me-1">Match</span> Matching</li>
                                        <li><span class="badge bg-primary me-1">Short</span> Short Answer</li>
                                        <li><span class="badge bg-primary me-1">Code</span> Coding</li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Validation Results -->
                    <div id="validationResults" class="mt-4" style="display: none;">
                        <div class="alert alert-info">
                            <h6><i class="fas fa-info-circle me-2"></i>Validation Results</h6>
                            <div id="validationMessage"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const validateBtn = document.getElementById('validateBtn');
    const importBtn = document.getElementById('importBtn');
    const fileInput = document.getElementById('file');
    const validationResults = document.getElementById('validationResults');
    const validationMessage = document.getElementById('validationMessage');

    validateBtn.addEventListener('click', function() {
        const file = fileInput.files[0];
        if (!file) {
            alert('Please select a file first');
            return;
        }

        const formData = new FormData();
        formData.append('file', file);
        formData.append('_token', '{{ csrf_token() }}');

        validateBtn.disabled = true;
        validateBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Validating...';

        fetch('{{ route("examination.questions.import.validate") }}', {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            validationResults.style.display = 'block';
            
            if (data.valid) {
                validationMessage.innerHTML = '<div class="text-success"><i class="fas fa-check-circle me-2"></i>' + data.message + '</div>';
                importBtn.disabled = false;
            } else {
                let errorHtml = '<div class="text-danger"><i class="fas fa-exclamation-triangle me-2"></i>' + data.message + '</div>';
                if (data.errors && data.errors.length > 0) {
                    errorHtml += '<ul class="mt-2 mb-0">';
                    data.errors.forEach(error => {
                        errorHtml += '<li class="small">' + error + '</li>';
                    });
                    errorHtml += '</ul>';
                }
                validationMessage.innerHTML = errorHtml;
                importBtn.disabled = true;
            }
        })
        .catch(error => {
            validationResults.style.display = 'block';
            validationMessage.innerHTML = '<div class="text-danger"><i class="fas fa-exclamation-triangle me-2"></i>Validation failed: ' + error.message + '</div>';
        })
        .finally(() => {
            validateBtn.disabled = false;
            validateBtn.innerHTML = '<i class="fas fa-check-circle me-2"></i>Validate File';
        });
    });

    // Enable import button only when file is selected
    fileInput.addEventListener('change', function() {
        importBtn.disabled = !this.files[0];
        validationResults.style.display = 'none';
    });
});
</script>
@endpush
@endsection
