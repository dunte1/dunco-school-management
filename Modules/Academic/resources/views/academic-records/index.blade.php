@extends('layouts.app')

@section('title', 'Academic Records Management')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h4 class="mb-0">Academic Records</h4>
                    <div>
                        <a href="{{ route('academic.academic-records.create') }}" class="btn btn-primary me-2">
                            <i class="fas fa-plus me-2"></i>New Record
                        </a>
                        <a href="{{ route('academic.academic-records.statistics') }}" class="btn btn-info">
                            <i class="fas fa-chart-bar me-2"></i>Statistics
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <!-- Search and Filter Form -->
                    <form method="GET" action="{{ route('academic.academic-records.index') }}" class="mb-4">
                        <div class="row">
                            <div class="col-md-2">
                                <input type="text" name="search" class="form-control" placeholder="Search students..." value="{{ request('search') }}">
                            </div>
                            <div class="col-md-2">
                                <select name="student_id" class="form-control">
                                    <option value="">All Students</option>
                                    @foreach($students as $student)
                                        <option value="{{ $student->id }}" {{ request('student_id') == $student->id ? 'selected' : '' }}>
                                            {{ $student->first_name }} {{ $student->last_name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-2">
                                <select name="class_id" class="form-control">
                                    <option value="">All Classes</option>
                                    @foreach($classes as $class)
                                        <option value="{{ $class->id }}" {{ request('class_id') == $class->id ? 'selected' : '' }}>
                                            {{ $class->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-2">
                                <select name="subject_id" class="form-control">
                                    <option value="">All Subjects</option>
                                    @foreach($subjects as $subject)
                                        <option value="{{ $subject->id }}" {{ request('subject_id') == $subject->id ? 'selected' : '' }}>
                                            {{ $subject->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-2">
                                <input type="text" name="academic_year" class="form-control" placeholder="Academic Year" value="{{ request('academic_year') }}">
                            </div>
                            <div class="col-md-2">
                                <button type="submit" class="btn btn-secondary me-2">Filter</button>
                                <a href="{{ route('academic.academic-records.index') }}" class="btn btn-outline-secondary">Clear</a>
                            </div>
                        </div>
                    </form>

                    <!-- Academic Records Table -->
                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>Student</th>
                                    <th>Class</th>
                                    <th>Subject</th>
                                    <th>Academic Year</th>
                                    <th>Term</th>
                                    <th>Exam Type</th>
                                    <th>Marks</th>
                                    <th>Percentage</th>
                                    <th>Grade</th>
                                    <th>Exam Date</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($academicRecords as $record)
                                <tr>
                                    <td>{{ $record->student->first_name ?? '' }} {{ $record->student->last_name ?? '' }}</td>
                                    <td>{{ $record->class->name ?? 'N/A' }}</td>
                                    <td>{{ $record->subject->name ?? 'N/A' }}</td>
                                    <td>{{ $record->academic_year }}</td>
                                    <td>{{ $record->term }}</td>
                                    <td>{{ $record->exam_type }}</td>
                                    <td>{{ $record->marks_obtained }}/{{ $record->total_marks }}</td>
                                    <td>
                                        <span class="badge bg-{{ $record->percentage >= 80 ? 'success' : ($record->percentage >= 60 ? 'warning' : 'danger') }}">
                                            {{ number_format($record->percentage, 1) }}%
                                        </span>
                                    </td>
                                    <td>
                                        <span class="badge bg-{{ $record->grade == 'A+' || $record->grade == 'A' ? 'success' : ($record->grade == 'B+' || $record->grade == 'B' ? 'warning' : 'danger') }}">
                                            {{ $record->grade }}
                                        </span>
                                    </td>
                                    <td>{{ $record->exam_date ? \Carbon\Carbon::parse($record->exam_date)->format('M d, Y') : 'N/A' }}</td>
                                    <td>
                                        <div class="btn-group" role="group">
                                            <a href="{{ route('academic.academic-records.show', $record->id) }}" class="btn btn-sm btn-info">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            <a href="{{ route('academic.academic-records.edit', $record->id) }}" class="btn btn-sm btn-warning">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <form action="{{ route('academic.academic-records.destroy', $record->id) }}" method="POST" class="d-inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure you want to delete this record?')">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="11" class="text-center">No academic records found.</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    <div class="d-flex justify-content-center">
                        {{ $academicRecords->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
