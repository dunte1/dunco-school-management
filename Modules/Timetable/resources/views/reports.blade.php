@extends('layouts.app')

@section('content')
<div class="container py-4">
    <h2 class="mb-4">Timetable Reports</h2>
    <div class="mb-4">
        <div class="card">
            <div class="card-body">
                <h5>Total Schedules</h5>
                <p class="fs-3 fw-bold">{{ $totalSchedules }}</p>
            </div>
        </div>
    </div>
    <div class="mb-4">
        <div class="d-flex justify-content-between align-items-center mb-2">
            <h5>Teacher Workload</h5>
            <div>
                <a href="#" class="btn btn-sm btn-outline-secondary disabled">Export CSV</a>
                <a href="#" class="btn btn-sm btn-outline-danger disabled">Export PDF</a>
            </div>
        </div>
        <div class="table-responsive">
            <table class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th>Teacher</th>
                        <th>Periods</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($teacherWorkload as $t)
                    <tr>
                        <td>{{ $t['teacher_name'] }}</td>
                        <td>{{ $t['periods'] }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    <div class="mb-4">
        <div class="d-flex justify-content-between align-items-center mb-2">
            <h5>Room Utilization</h5>
            <div>
                <a href="#" class="btn btn-sm btn-outline-secondary disabled">Export CSV</a>
                <a href="#" class="btn btn-sm btn-outline-danger disabled">Export PDF</a>
            </div>
        </div>
        <div class="table-responsive">
            <table class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th>Room</th>
                        <th>Periods</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($roomUtilization as $r)
                    <tr>
                        <td>{{ $r['room_name'] }}</td>
                        <td>{{ $r['periods'] }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    <div class="mb-4">
        <div class="d-flex justify-content-between align-items-center mb-2">
            <h5>Class Schedule Density</h5>
            <div>
                <a href="#" class="btn btn-sm btn-outline-secondary disabled">Export CSV</a>
                <a href="#" class="btn btn-sm btn-outline-danger disabled">Export PDF</a>
            </div>
        </div>
        <div class="table-responsive">
            <table class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th>Class</th>
                        <th>Periods</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($classDensity as $c)
                    <tr>
                        <td>{{ $c['class_name'] }}</td>
                        <td>{{ $c['periods'] }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection 