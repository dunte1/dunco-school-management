@extends('layouts.app')
@section('content')
<div class="container py-4">
    <h2 class="mb-4 text-uppercase" style="color:#6ec1e4;">Attendance Statistics</h2>
    <form method="GET" action="" class="mb-3 d-flex flex-wrap align-items-end gap-3">
        <div>
            <label for="class_id" class="form-label text-muted text-uppercase">Class</label>
            <select name="class_id" id="class_id" class="form-select rounded-pill bg-dark text-white" style="min-width:200px;">
                <option value="">Select Class</option>
                @foreach($classes as $class)
                    <option value="{{ $class->id }}" @if(request('class_id') == $class->id) selected @endif>{{ $class->name }}</option>
                @endforeach
            </select>
        </div>
        <div>
            <label for="start_date" class="form-label text-muted text-uppercase">Start Date</label>
            <input type="date" name="start_date" id="start_date" value="{{ request('start_date') }}" class="form-control rounded-pill bg-dark text-white" style="min-width:150px;">
        </div>
        <div>
            <label for="end_date" class="form-label text-muted text-uppercase">End Date</label>
            <input type="date" name="end_date" id="end_date" value="{{ request('end_date') }}" class="form-control rounded-pill bg-dark text-white" style="min-width:150px;">
        </div>
        <button type="submit" class="btn btn-primary rounded-pill px-4">View Statistics</button>
    </form>
    @if($selectedClass && $statistics)
    <div class="mb-3 d-flex gap-2">
        <form method="POST" action="{{ route('academic.attendance.export') }}" class="d-inline">
            @csrf
            <input type="hidden" name="class_id" value="{{ request('class_id') }}">
            <input type="hidden" name="start_date" value="{{ request('start_date') }}">
            <input type="hidden" name="end_date" value="{{ request('end_date') }}">
            <input type="hidden" name="format" value="csv">
            <button type="submit" class="btn btn-outline-primary rounded-pill">Export CSV</button>
        </form>
        <form method="POST" action="{{ route('academic.attendance.export') }}" class="d-inline">
            @csrf
            <input type="hidden" name="class_id" value="{{ request('class_id') }}">
            <input type="hidden" name="start_date" value="{{ request('start_date') }}">
            <input type="hidden" name="end_date" value="{{ request('end_date') }}">
            <input type="hidden" name="format" value="xlsx">
            <button type="submit" class="btn btn-outline-success rounded-pill">Export Excel</button>
        </form>
    </div>
    <div class="bg-dark rounded shadow p-4 mt-3">
        <h4 class="text-info mb-3">{{ $selectedClass->name }} Statistics</h4>
        <ul class="list-group list-group-flush">
            <li class="list-group-item bg-dark text-white">Total Records: <span class="fw-bold">{{ $statistics['total'] }}</span></li>
            <li class="list-group-item bg-dark text-white">Present: <span class="fw-bold text-success">{{ $statistics['present'] }}</span></li>
            <li class="list-group-item bg-dark text-white">Absent: <span class="fw-bold text-danger">{{ $statistics['absent'] }}</span></li>
            <li class="list-group-item bg-dark text-white">Late: <span class="fw-bold text-warning">{{ $statistics['late'] }}</span></li>
            <li class="list-group-item bg-dark text-white">Excused: <span class="fw-bold text-info">{{ $statistics['excused'] }}</span></li>
            <li class="list-group-item bg-dark text-white">Attendance Rate: <span class="fw-bold text-primary">{{ $statistics['attendance_rate'] }}%</span></li>
            <li class="list-group-item bg-dark text-white">Absent Rate: <span class="fw-bold text-danger">{{ $statistics['absent_rate'] }}%</span></li>
        </ul>
    </div>
    @elseif(request('class_id'))
        <div class="alert alert-warning mt-4">No statistics found for this class and date range.</div>
    @endif
</div>
@endsection
@push('styles')
<style>
body { background: #0a1931; color: #eaf6fb; }
.list-group-item { border: none; }
.text-success { color: #00bcd4 !important; }
.text-danger { color: #e53935 !important; }
.text-warning { color: #ffb300 !important; }
.text-info { color: #6ec1e4 !important; }
.text-primary { color: #1565c0 !important; }
</style>
@endpush 