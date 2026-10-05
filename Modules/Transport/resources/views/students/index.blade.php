@extends('layouts.app')

@section('title', 'Transport Students')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h4 class="mb-0">Transport Students</h4>
                    <a href="{{ route('transport.students.create') }}" class="btn btn-primary">
                        <i class="fas fa-plus"></i> Add Student
                    </a>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>Student ID</th>
                                    <th>Name</th>
                                    <th>Parent</th>
                                    <th>Route</th>
                                    <th>Pickup Location</th>
                                    <th>Dropoff Location</th>
                                    <th>Status</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($students ?? [] as $student)
                                <tr>
                                    <td>{{ $student->student_id ?? 'N/A' }}</td>
                                    <td>{{ $student->name ?? 'N/A' }}</td>
                                    <td>{{ $student->parent_name ?? 'N/A' }}</td>
                                    <td>{{ $student->route->name ?? 'N/A' }}</td>
                                    <td>{{ $student->pickup_location ?? 'N/A' }}</td>
                                    <td>{{ $student->dropoff_location ?? 'N/A' }}</td>
                                    <td>
                                        <span class="badge bg-{{ $student->status === 'active' ? 'success' : ($student->status === 'inactive' ? 'secondary' : 'warning') }}">
                                            {{ ucfirst($student->status ?? 'N/A') }}
                                        </span>
                                    </td>
                                    <td>
                                        <a href="{{ route('transport.students.show', $student->id ?? 1) }}" class="btn btn-sm btn-info">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <a href="{{ route('transport.students.edit', $student->id ?? 1) }}" class="btn btn-sm btn-warning">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="8" class="text-center">No students found.</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
