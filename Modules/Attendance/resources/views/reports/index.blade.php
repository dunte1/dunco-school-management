@extends('layouts.app')

@section('title', 'Attendance Reports')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h4>Attendance Reports</h4>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-3">
                            <div class="card bg-primary text-white">
                                <div class="card-body text-center">
                                    <h5>Total Records</h5>
                                    <h3>{{ $stats['total_records'] ?? 0 }}</h3>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="card bg-success text-white">
                                <div class="card-body text-center">
                                    <h5>Present</h5>
                                    <h3>{{ $stats['present_count'] ?? 0 }}</h3>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="card bg-danger text-white">
                                <div class="card-body text-center">
                                    <h5>Absent</h5>
                                    <h3>{{ $stats['absent_count'] ?? 0 }}</h3>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="card bg-warning text-white">
                                <div class="card-body text-center">
                                    <h5>Late</h5>
                                    <h3>{{ $stats['late_count'] ?? 0 }}</h3>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="row mt-4">
                        <div class="col-md-12">
                            <h5>Report Types</h5>
                            <div class="list-group">
                                <a href="{{ route('attendance.reports.daily') }}" class="list-group-item list-group-item-action">
                                    <i class="fas fa-calendar-day me-2"></i>Daily Report
                                </a>
                                <a href="{{ route('attendance.reports.weekly') }}" class="list-group-item list-group-item-action">
                                    <i class="fas fa-calendar-week me-2"></i>Weekly Report
                                </a>
                                <a href="{{ route('attendance.reports.monthly') }}" class="list-group-item list-group-item-action">
                                    <i class="fas fa-calendar-alt me-2"></i>Monthly Report
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
