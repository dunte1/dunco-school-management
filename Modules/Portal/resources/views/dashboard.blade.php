@extends('portal::components.layouts.master')

@section('content')
<div class="container-fluid py-4">
    @if(Auth::user()->hasRole('parent'))
    <div class="mb-3 d-flex justify-content-end">
        <form method="GET" action="{{ route('portal.dashboard') }}" class="d-flex align-items-center gap-2">
            <label for="student_id" class="fw-semibold me-2">Viewing for:</label>
            <select name="student_id" id="student_id" class="form-select w-auto" onchange="this.form.submit()">
                @foreach($all_students as $child)
                    <option value="{{ $child->id }}" @if(request('student_id', $child->id) == $child->id) selected @endif>{{ $child->name }}</option>
                @endforeach
            </select>
        </form>
    </div>
    @endif
    <div class="row">
        <div class="col-12">
            <div class="card bg-primary text-white mb-4">
                <div class="card-body p-4 d-flex justify-content-between align-items-center">
                    <div>
                        <h4 class="mb-1 text-white">Welcome, {{ Auth::user()->name }}!</h4>
                        <p class="mb-0">Here's your summary for today.</p>
                    </div>
                    <img src="/logo.png" alt="School Logo" height="50" style="border-radius:12px;">
                </div>
            </div>
        </div>
    </div>
    <div class="row g-4">
        {{-- Stat Cards --}}
        <div class="col-xl-3 col-md-6">
            <div class="card h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-muted">Attendance</h6>
                            <h4 class="fw-bold">92%</h4>
                        </div>
                        <div class="icon-shape bg-soft-primary text-primary rounded-circle">
                            <i class="fas fa-user-check"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6">
            <div class="card h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-muted">Outstanding Fees</h6>
                            <h4 class="fw-bold">Ksh {{ number_format($dueFees->sum('outstanding_amount'), 0) }}</h4>
                        </div>
                        <div class="icon-shape bg-soft-danger text-danger rounded-circle">
                            <i class="fas fa-coins"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6">
            <div class="card h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-muted">Upcoming Events</h6>
                            <h4 class="fw-bold">{{ $events->count() }}</h4>
                        </div>
                        <div class="icon-shape bg-soft-info text-info rounded-circle">
                            <i class="fas fa-calendar-alt"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6">
            <div class="card h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-muted">Notifications</h6>
                            <h4 class="fw-bold">{{ $notifications->count() }}</h4>
                        </div>
                        <div class="icon-shape bg-soft-warning text-warning rounded-circle">
                            <i class="fas fa-bell"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        {{-- Main Content --}}
        <div class="col-lg-8">
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0">Upcoming Assignments</h5>
                </div>
                <div class="card-body">
                    {{-- Placeholder --}}
                    <div class="text-center text-muted py-4">
                        <i class="fas fa-check-circle fa-2x mb-2"></i>
                        <p>No upcoming assignments. Well done!</p>
                    </div>
                </div>
            </div>
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Recent Grades</h5>
                </div>
                <div class="card-body">
                    @if($recentGrades->count())
                    <ul class="list-group list-group-flush">
                        @foreach($recentGrades as $grade)
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            <span>{{ $grade->subject->name ?? 'N/A' }} ({{ $grade->exam_type }})</span>
                            <span class="badge bg-primary rounded-pill">{{ $grade->grade }}</span>
                        </li>
                        @endforeach
                    </ul>
                    @else
                    <div class="text-center text-muted py-4">
                        <i class="fas fa-graduation-cap fa-2x mb-2"></i>
                        <p>No recent grades to display.</p>
                    </div>
                    @endif
                </div>
            </div>
        </div>
        <div class="col-lg-4">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Today's Schedule</h5>
                </div>
                <div class="card-body">
                    {{-- Placeholder --}}
                    <div class="text-center text-muted py-4">
                        <i class="fas fa-calendar-day fa-2x mb-2"></i>
                        <p>No classes scheduled for today.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 