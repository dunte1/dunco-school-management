@extends('layouts.app')

@section('content')
<h2>Attendance</h2>

@if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
@endif
@if(session('error'))
    <div class="alert alert-danger">{{ session('error') }}</div>
@endif

<div class="mb-3 d-flex gap-2">
    <form method="POST" action="{{ route('attendance.clock-in') }}">
        @csrf
        <input type="hidden" name="staff_id" value="{{ auth()->user()->staff_id ?? '' }}">
        <button type="submit" class="btn btn-primary">Clock In</button>
    </form>
    <form method="POST" action="{{ route('attendance.clock-out') }}">
        @csrf
        <input type="hidden" name="staff_id" value="{{ auth()->user()->staff_id ?? '' }}">
        <button type="submit" class="btn btn-secondary">Clock Out</button>
    </form>
</div>

<form method="GET" class="row g-2 mb-3">
    <div class="col-md-3">
        <input type="date" name="date" class="form-control" value="{{ request('date') }}">
    </div>
    <div class="col-md-3">
        <select name="staff_id" class="form-select">
            <option value="">All Staff</option>
            @foreach($staff as $s)
                <option value="{{ $s->id }}" {{ request('staff_id') == $s->id ? 'selected' : '' }}>{{ $s->first_name }} {{ $s->last_name }}</option>
            @endforeach
        </select>
    </div>
    <div class="col-md-3">
        <select name="status" class="form-select">
            <option value="">All Status</option>
            <option value="present" {{ request('status') == 'present' ? 'selected' : '' }}>Present</option>
            <option value="absent" {{ request('status') == 'absent' ? 'selected' : '' }}>Absent</option>
            <option value="leave" {{ request('status') == 'leave' ? 'selected' : '' }}>Leave</option>
            <option value="late" {{ request('status') == 'late' ? 'selected' : '' }}>Late</option>
            <option value="half-day" {{ request('status') == 'half-day' ? 'selected' : '' }}>Half-day</option>
        </select>
    </div>
    <div class="col-md-3">
        <button type="submit" class="btn btn-primary">Filter</button>
        <a href="{{ route('hr.attendance.create') }}" class="btn btn-success">Add Attendance</a>
    </div>
</form>
<table class="table table-bordered table-hover">
    <thead class="table-light">
        <tr>
            <th>Date</th>
            <th>Staff</th>
            <th>Clock In</th>
            <th>Clock Out</th>
            <th>Status</th>
            <th>Remarks</th>
        </tr>
    </thead>
    <tbody>
        @foreach($attendance as $att)
        <tr>
            <td>{{ $att->date }}</td>
            <td>{{ $att->staff->first_name }} {{ $att->staff->last_name }}</td>
            <td>{{ $att->clock_in }}</td>
            <td>{{ $att->clock_out }}</td>
            <td><span class="badge bg-{{ $att->status == 'present' ? 'success' : ($att->status == 'absent' ? 'danger' : 'warning') }}">{{ ucfirst($att->status) }}</span></td>
            <td>{{ $att->remarks }}</td>
        </tr>
        @endforeach
    </tbody>
</table>
{{ $attendance->links() }}
@endsection 