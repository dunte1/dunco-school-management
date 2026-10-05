@extends('layouts.app')

@section('title', 'Enrollment Details')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h4 class="mb-0">Enrollment Details</h4>
                    <div>
                        <a href="{{ route('academic.enrollments.edit', $student->id) }}" class="btn btn-warning">
                            <i class="fas fa-edit me-2"></i>Edit
                        </a>
                        <a href="{{ route('academic.enrollments.index') }}" class="btn btn-secondary">
                            <i class="fas fa-arrow-left me-2"></i>Back
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <h5>Student Information</h5>
                            <table class="table table-borderless">
                                <tr>
                                    <td><strong>Student ID:</strong></td>
                                    <td>{{ $student->student_id }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Name:</strong></td>
                                    <td>{{ $student->first_name }} {{ $student->last_name }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Class:</strong></td>
                                    <td>{{ $student->class->name ?? 'Not Enrolled' }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Enrollment Date:</strong></td>
                                    <td>{{ $student->admission_date ? \Carbon\Carbon::parse($student->admission_date)->format('M d, Y') : 'N/A' }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Status:</strong></td>
                                    <td>
                                        <span class="badge bg-{{ $student->is_active ? 'success' : 'secondary' }}">
                                            {{ $student->is_active ? 'Active' : 'Inactive' }}
                                        </span>
                                    </td>
                                </tr>
                            </table>
                        </div>
                        <div class="col-md-6">
                            <h5>Enrollment History</h5>
                            @if($student->enrollmentHistory->count() > 0)
                                <div class="table-responsive">
                                    <table class="table table-sm">
                                        <thead>
                                            <tr>
                                                <th>Date</th>
                                                <th>Class</th>
                                                <th>Status</th>
                                                <th>Notes</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($student->enrollmentHistory as $history)
                                            <tr>
                                                <td>{{ \Carbon\Carbon::parse($history->enrollment_date)->format('M d, Y') }}</td>
                                                <td>{{ $history->academicClass->name ?? 'N/A' }}</td>
                                                <td>
                                                    <span class="badge bg-{{ $history->status == 'enrolled' ? 'success' : ($history->status == 'transferred' ? 'warning' : 'danger') }}">
                                                        {{ ucfirst($history->status) }}
                                                    </span>
                                                </td>
                                                <td>{{ $history->notes ?? '-' }}</td>
                                            </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            @else
                                <p class="text-muted">No enrollment history available.</p>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
