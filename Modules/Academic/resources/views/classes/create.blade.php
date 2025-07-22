@extends('academic::components.layouts.master')

@section('content')
<div class="container-fluid">
    <!-- Page Header -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">
            <i class="fas fa-plus text-primary"></i>
            Create New Class
        </h1>
        <a href="{{ route('academic.classes.index') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Back to Classes
        </a>
    </div>

    <div class="row">
        <div class="col-lg-8">
            <div class="card shadow">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Class Information</h6>
                </div>
                <div class="card-body">
                    <form method="POST" action="{{ route('academic.classes.store') }}" id="classForm">
                        @csrf
                        
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="name" class="form-label">Class Name <span class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('name') is-invalid @enderror" 
                                       id="name" name="name" value="{{ old('name') }}" required>
                                @error('name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="code" class="form-label">Class Code <span class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('code') is-invalid @enderror" 
                                       id="code" name="code" value="{{ old('code') }}" required>
                                @error('code')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
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
                            <div class="col-md-4 mb-3">
                                <label for="capacity" class="form-label">Capacity <span class="text-danger">*</span></label>
                                <input type="number" class="form-control @error('capacity') is-invalid @enderror" 
                                       id="capacity" name="capacity" value="{{ old('capacity', 30) }}" 
                                       min="1" max="100" required>
                                @error('capacity')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-4 mb-3">
                                <label for="teacher_id" class="form-label">Class Teacher</label>
                                <select class="form-select @error('teacher_id') is-invalid @enderror" 
                                        id="teacher_id" name="teacher_id">
                                    <option value="">Select Teacher</option>
                                    @foreach($teachers as $teacher)
                                        <option value="{{ $teacher->id }}" {{ old('teacher_id') == $teacher->id ? 'selected' : '' }}>
                                            {{ $teacher->name }} ({{ $teacher->email }})
                                        </option>
                                    @endforeach
                                </select>
                                @error('teacher_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-4 mb-3">
                                <label for="academic_year" class="form-label">Academic Year <span class="text-danger">*</span></label>
                                <select class="form-select @error('academic_year') is-invalid @enderror" 
                                        id="academic_year" name="academic_year" required>
                                    <option value="">Select Academic Year</option>
                                    @foreach($academicYears as $year)
                                        <option value="{{ $year }}" {{ old('academic_year') == $year ? 'selected' : '' }}>
                                            {{ $year }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('academic_year')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Subjects</label>
                            <div class="row">
                                @foreach($subjects as $subject)
                                    <div class="col-md-4 mb-2">
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" 
                                                   name="subjects[]" value="{{ $subject->id }}" 
                                                   id="subject_{{ $subject->id }}"
                                                   {{ in_array($subject->id, old('subjects', [])) ? 'checked' : '' }}>
                                            <label class="form-check-label" for="subject_{{ $subject->id }}">
                                                <strong>{{ $subject->name }}</strong>
                                                <br><small class="text-muted">{{ $subject->code }} ({{ $subject->credits }} credits)</small>
                                            </label>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                            @if($subjects->count() == 0)
                                <div class="alert alert-info">
                                    <i class="fas fa-info-circle"></i>
                                    No subjects available. <a href="{{ route('academic.subjects.create') }}">Create subjects first</a>.
                                </div>
                            @endif
                        </div>

                        <div class="d-flex justify-content-end gap-2">
                            <a href="{{ route('academic.classes.index') }}" class="btn btn-secondary">
                                <i class="fas fa-times"></i> Cancel
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save"></i> Create Class
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Sidebar -->
        <div class="col-lg-4">
            <div class="card shadow">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Quick Tips</h6>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <h6><i class="fas fa-lightbulb text-warning"></i> Class Code</h6>
                        <p class="text-muted small">Use a unique, short code (e.g., "10A", "11B") for easy identification.</p>
                    </div>
                    <div class="mb-3">
                        <h6><i class="fas fa-users text-info"></i> Capacity</h6>
                        <p class="text-muted small">Set a reasonable capacity based on your classroom size and teaching requirements.</p>
                    </div>
                    <div class="mb-3">
                        <h6><i class="fas fa-book text-success"></i> Subjects</h6>
                        <p class="text-muted small">Select subjects that will be taught in this class. You can modify this later.</p>
                    </div>
                    <div class="mb-3">
                        <h6><i class="fas fa-calendar text-primary"></i> Academic Year</h6>
                        <p class="text-muted small">Choose the academic year for this class (e.g., "2023-2024").</p>
                    </div>
                </div>
            </div>

            <div class="card shadow mt-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Recent Classes</h6>
                </div>
                <div class="card-body">
                    <div class="list-group list-group-flush">
                        @foreach($recentClasses ?? [] as $class)
                            <div class="list-group-item d-flex justify-content-between align-items-center">
                                <div>
                                    <h6 class="mb-1">{{ $class->name }}</h6>
                                    <small class="text-muted">{{ $class->code }}</small>
                                </div>
                                <span class="badge badge-primary badge-pill">{{ $class->academic_year }}</span>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    // Auto-generate class code from name
    document.getElementById('name').addEventListener('input', function() {
        const name = this.value;
        const codeInput = document.getElementById('code');
        
        if (name && !codeInput.value) {
            // Generate a simple code from the name
            const code = name.replace(/[^A-Za-z0-9]/g, '').substring(0, 5).toUpperCase();
            codeInput.value = code;
        }
    });

    // Form validation
    document.getElementById('classForm').addEventListener('submit', function(e) {
        const requiredFields = this.querySelectorAll('[required]');
        let isValid = true;

        requiredFields.forEach(field => {
            if (!field.value.trim()) {
                field.classList.add('is-invalid');
                isValid = false;
            } else {
                field.classList.remove('is-invalid');
            }
        });

        if (!isValid) {
            e.preventDefault();
            alert('Please fill in all required fields.');
        }
    });
</script>
@endpush
@endsection 