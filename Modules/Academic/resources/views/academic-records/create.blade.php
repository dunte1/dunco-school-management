@extends('layouts.app')

@section('title', 'Create New Academic Record')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="mb-0">Create New Academic Record</h4>
                </div>
                <div class="card-body">
                    <form action="{{ route('academic.academic-records.store') }}" method="POST">
                        @csrf
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="student_id" class="form-label">Student</label>
                                    <select name="student_id" id="student_id" class="form-control @error('student_id') is-invalid @enderror" required>
                                        <option value="">Select Student</option>
                                        @foreach($students as $student)
                                            <option value="{{ $student->id }}" {{ old('student_id') == $student->id ? 'selected' : '' }}>
                                                {{ $student->first_name }} {{ $student->last_name }} ({{ $student->student_id }})
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('student_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="class_id" class="form-label">Class</label>
                                    <select name="class_id" id="class_id" class="form-control @error('class_id') is-invalid @enderror" required>
                                        <option value="">Select Class</option>
                                        @foreach($classes as $class)
                                            <option value="{{ $class->id }}" {{ old('class_id') == $class->id ? 'selected' : '' }}>
                                                {{ $class->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('class_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="subject_id" class="form-label">Subject</label>
                                    <select name="subject_id" id="subject_id" class="form-control @error('subject_id') is-invalid @enderror" required>
                                        <option value="">Select Subject</option>
                                        @foreach($subjects as $subject)
                                            <option value="{{ $subject->id }}" {{ old('subject_id') == $subject->id ? 'selected' : '' }}>
                                                {{ $subject->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('subject_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="academic_year" class="form-label">Academic Year</label>
                                    <input type="text" name="academic_year" id="academic_year" class="form-control @error('academic_year') is-invalid @enderror" value="{{ old('academic_year', date('Y') . '-' . (date('Y') + 1)) }}" required>
                                    @error('academic_year')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label for="term" class="form-label">Term</label>
                                    <select name="term" id="term" class="form-control @error('term') is-invalid @enderror" required>
                                        <option value="">Select Term</option>
                                        <option value="First Term" {{ old('term') == 'First Term' ? 'selected' : '' }}>First Term</option>
                                        <option value="Second Term" {{ old('term') == 'Second Term' ? 'selected' : '' }}>Second Term</option>
                                        <option value="Third Term" {{ old('term') == 'Third Term' ? 'selected' : '' }}>Third Term</option>
                                    </select>
                                    @error('term')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label for="exam_type" class="form-label">Exam Type</label>
                                    <select name="exam_type" id="exam_type" class="form-control @error('exam_type') is-invalid @enderror" required>
                                        <option value="">Select Exam Type</option>
                                        <option value="Mid Term" {{ old('exam_type') == 'Mid Term' ? 'selected' : '' }}>Mid Term</option>
                                        <option value="End Term" {{ old('exam_type') == 'End Term' ? 'selected' : '' }}>End Term</option>
                                        <option value="Final Exam" {{ old('exam_type') == 'Final Exam' ? 'selected' : '' }}>Final Exam</option>
                                        <option value="Assignment" {{ old('exam_type') == 'Assignment' ? 'selected' : '' }}>Assignment</option>
                                        <option value="Quiz" {{ old('exam_type') == 'Quiz' ? 'selected' : '' }}>Quiz</option>
                                    </select>
                                    @error('exam_type')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label for="exam_date" class="form-label">Exam Date</label>
                                    <input type="date" name="exam_date" id="exam_date" class="form-control @error('exam_date') is-invalid @enderror" value="{{ old('exam_date', date('Y-m-d')) }}" required>
                                    @error('exam_date')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label for="marks_obtained" class="form-label">Marks Obtained</label>
                                    <input type="number" name="marks_obtained" id="marks_obtained" class="form-control @error('marks_obtained') is-invalid @enderror" value="{{ old('marks_obtained') }}" min="0" step="0.01" required>
                                    @error('marks_obtained')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label for="total_marks" class="form-label">Total Marks</label>
                                    <input type="number" name="total_marks" id="total_marks" class="form-control @error('total_marks') is-invalid @enderror" value="{{ old('total_marks') }}" min="1" step="0.01" required>
                                    @error('total_marks')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label class="form-label">Percentage</label>
                                    <input type="text" id="percentage_display" class="form-control" readonly>
                                </div>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-12">
                                <div class="mb-3">
                                    <label for="remarks" class="form-label">Remarks</label>
                                    <textarea name="remarks" id="remarks" class="form-control @error('remarks') is-invalid @enderror" rows="3">{{ old('remarks') }}</textarea>
                                    @error('remarks')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        
                        <div class="d-flex justify-content-between">
                            <a href="{{ route('academic.academic-records.index') }}" class="btn btn-secondary">Cancel</a>
                            <button type="submit" class="btn btn-primary">Create Academic Record</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const marksObtained = document.getElementById('marks_obtained');
    const totalMarks = document.getElementById('total_marks');
    const percentageDisplay = document.getElementById('percentage_display');
    
    function calculatePercentage() {
        const obtained = parseFloat(marksObtained.value) || 0;
        const total = parseFloat(totalMarks.value) || 0;
        
        if (total > 0) {
            const percentage = (obtained / total) * 100;
            percentageDisplay.value = percentage.toFixed(2) + '%';
        } else {
            percentageDisplay.value = '';
        }
    }
    
    marksObtained.addEventListener('input', calculatePercentage);
    totalMarks.addEventListener('input', calculatePercentage);
});
</script>
@endsection
