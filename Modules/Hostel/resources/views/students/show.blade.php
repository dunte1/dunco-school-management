@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="mb-0">Student Details</h2>
        <div>
            <a href="{{ route('hostel.students.edit', $student) }}" class="btn btn-primary me-2">
                <i class="fas fa-edit me-2"></i>Edit Student
            </a>
            <a href="{{ route('hostel.students.index') }}" class="btn btn-outline-secondary">
                <i class="fas fa-arrow-left me-2"></i>Back to Students
            </a>
        </div>
    </div>

    <div class="row">
        <div class="col-md-8">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex align-items-center mb-3">
                        <h3 class="card-title mb-0">{{ $student->name }}</h3>
                        <span class="badge bg-{{ $student->status == 'active' ? 'success' : ($student->status == 'inactive' ? 'secondary' : 'warning') }} ms-2">
                            {{ ucfirst($student->status) }}
                        </span>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6">
                            <h6>Student ID</h6>
                            <p class="text-muted">{{ $student->student_id }}</p>
                        </div>
                        <div class="col-md-6">
                            <h6>Email</h6>
                            <p class="text-muted">
                                <a href="mailto:{{ $student->email }}" class="text-decoration-none">
                                    {{ $student->email }}
                                </a>
                            </p>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <h6>Phone</h6>
                            <p class="text-muted">
                                <a href="tel:{{ $student->phone }}" class="text-decoration-none">
                                    {{ $student->phone }}
                                </a>
                            </p>
                        </div>
                        <div class="col-md-6">
                            <h6>Hostel</h6>
                            <p class="text-muted">
                                <a href="{{ route('hostel.hostels.show', $student->hostel) }}" class="text-decoration-none">
                                    {{ $student->hostel->name }}
                                </a>
                            </p>
                        </div>
                    </div>

                    @if($student->room)
                    <div class="row">
                        <div class="col-md-6">
                            <h6>Room</h6>
                            <p class="text-muted">
                                <a href="{{ route('hostel.rooms.show', $student->room) }}" class="text-decoration-none">
                                    {{ $student->room->name }}
                                </a>
                            </p>
                        </div>
                        <div class="col-md-6">
                            <h6>Floor</h6>
                            <p class="text-muted">{{ $student->room->floor->name ?? 'N/A' }}</p>
                        </div>
                    </div>
                    @endif

                    <div class="row">
                        <div class="col-md-6">
                            <h6>Check-in Date</h6>
                            <p class="text-muted">{{ $student->check_in_date->format('F d, Y') }}</p>
                        </div>
                        <div class="col-md-6">
                            <h6>Check-out Date</h6>
                            <p class="text-muted">
                                @if($student->check_out_date)
                                    {{ $student->check_out_date->format('F d, Y') }}
                                @else
                                    <span class="text-muted">Not checked out</span>
                                @endif
                            </p>
                        </div>
                    </div>

                    @if($student->emergency_contact || $student->emergency_phone)
                    <hr>
                    <h5>Emergency Contact</h5>
                    <div class="row">
                        @if($student->emergency_contact)
                        <div class="col-md-6">
                            <h6>Contact Person</h6>
                            <p class="text-muted">{{ $student->emergency_contact }}</p>
                        </div>
                        @endif
                        @if($student->emergency_phone)
                        <div class="col-md-6">
                            <h6>Emergency Phone</h6>
                            <p class="text-muted">
                                <a href="tel:{{ $student->emergency_phone }}" class="text-decoration-none">
                                    {{ $student->emergency_phone }}
                                </a>
                            </p>
                        </div>
                        @endif
                    </div>
                    @endif

                    <div class="row mt-3">
                        <div class="col-md-6">
                            <h6>Registered</h6>
                            <p class="text-muted">{{ $student->created_at->format('F d, Y \a\t g:i A') }}</p>
                        </div>
                        <div class="col-md-6">
                            <h6>Last Updated</h6>
                            <p class="text-muted">{{ $student->updated_at->format('F d, Y \a\t g:i A') }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Statistics</h5>
                </div>
                <div class="card-body">
                    <div class="text-center mb-3">
                        <h4 class="text-primary">{{ $student->stay_duration }}</h4>
                        <p class="text-muted mb-0">Days in Hostel</p>
                    </div>
                    
                    <div class="text-center mb-3">
                        <h4 class="text-success">{{ $student->total_fees_paid ?? 0 }}</h4>
                        <p class="text-muted mb-0">Total Fees Paid</p>
                    </div>
                    
                    <div class="text-center mb-3">
                        <h4 class="text-warning">{{ $student->total_fees_due ?? 0 }}</h4>
                        <p class="text-muted mb-0">Outstanding Fees</p>
                    </div>
                </div>
            </div>

            <div class="card mt-3">
                <div class="card-header">
                    <h5 class="mb-0">Actions</h5>
                </div>
                <div class="card-body">
                    <div class="d-grid gap-2">
                        <a href="{{ route('hostel.students.edit', $student) }}" class="btn btn-primary">
                            <i class="fas fa-edit me-2"></i>Edit Student
                        </a>
                        <form action="{{ route('hostel.students.destroy', $student) }}" method="POST" class="d-inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger w-100" 
                                    onclick="return confirm('Are you sure you want to remove this student? This action cannot be undone.')">
                                <i class="fas fa-trash me-2"></i>Remove Student
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
