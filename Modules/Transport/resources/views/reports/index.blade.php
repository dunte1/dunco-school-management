@extends('layouts.app')

@section('title', 'Transport Reports')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Transport Reports Dashboard</h3>
                    <div class="card-tools">
                        <a href="{{ route('transport.reports.export', ['type' => 'overview']) }}" class="btn btn-sm btn-primary">
                            <i class="fas fa-download"></i> Export Overview CSV
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <!-- Statistics Cards -->
                    <div class="row mb-4">
                        <div class="col-lg-3 col-6">
                            <div class="small-box bg-info">
                                <div class="inner">
                                    <h3>{{ $stats['total_trips'] ?? 0 }}</h3>
                                    <p>Total Trips</p>
                                </div>
                                <div class="icon">
                                    <i class="fas fa-route"></i>
                                </div>
                                <a href="{{ route('transport.reports.trips') }}" class="small-box-footer">
                                    More info <i class="fas fa-arrow-circle-right"></i>
                                </a>
                            </div>
                        </div>
                        <div class="col-lg-3 col-6">
                            <div class="small-box bg-success">
                                <div class="inner">
                                    <h3>{{ $stats['total_vehicles'] ?? 0 }}</h3>
                                    <p>Total Vehicles</p>
                                </div>
                                <div class="icon">
                                    <i class="fas fa-bus"></i>
                                </div>
                                <a href="{{ route('transport.reports.vehicles') }}" class="small-box-footer">
                                    More info <i class="fas fa-arrow-circle-right"></i>
                                </a>
                            </div>
                        </div>
                        <div class="col-lg-3 col-6">
                            <div class="small-box bg-warning">
                                <div class="inner">
                                    <h3>{{ $stats['total_drivers'] ?? 0 }}</h3>
                                    <p>Total Drivers</p>
                                </div>
                                <div class="icon">
                                    <i class="fas fa-user-tie"></i>
                                </div>
                                <a href="{{ route('transport.reports.drivers') }}" class="small-box-footer">
                                    More info <i class="fas fa-arrow-circle-right"></i>
                                </a>
                            </div>
                        </div>
                        <div class="col-lg-3 col-6">
                            <div class="small-box bg-danger">
                                <div class="inner">
                                    <h3>{{ $stats['total_students'] ?? 0 }}</h3>
                                    <p>Total Students</p>
                                </div>
                                <div class="icon">
                                    <i class="fas fa-users"></i>
                                </div>
                                <a href="{{ route('transport.students.index') }}" class="small-box-footer">
                                    More info <i class="fas fa-arrow-circle-right"></i>
                                </a>
                            </div>
                        </div>
                    </div>

                    <!-- Quick Reports Navigation -->
                    <div class="row">
                        <div class="col-md-6">
                            <div class="card">
                                <div class="card-header">
                                    <h5>Quick Reports</h5>
                                </div>
                                <div class="card-body">
                                    <div class="list-group">
                                        <a href="{{ route('transport.reports.trips') }}" class="list-group-item list-group-item-action">
                                            <i class="fas fa-route mr-2"></i> Trip Reports
                                        </a>
                                        <a href="{{ route('transport.reports.vehicles') }}" class="list-group-item list-group-item-action">
                                            <i class="fas fa-bus mr-2"></i> Vehicle Reports
                                        </a>
                                        <a href="{{ route('transport.reports.drivers') }}" class="list-group-item list-group-item-action">
                                            <i class="fas fa-user-tie mr-2"></i> Driver Reports
                                        </a>
                                        <a href="{{ route('transport.reports.fees') }}" class="list-group-item list-group-item-action">
                                            <i class="fas fa-money-bill-wave mr-2"></i> Fee Reports
                                        </a>
                                        <a href="{{ route('transport.reports.maintenance') }}" class="list-group-item list-group-item-action">
                                            <i class="fas fa-wrench mr-2"></i> Maintenance Reports
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="card">
                                <div class="card-header">
                                    <h5>Recent Activity</h5>
                                </div>
                                <div class="card-body">
                                    <div class="table-responsive">
                                        <table class="table table-sm">
                                            <thead>
                                                <tr>
                                                    <th>Type</th>
                                                    <th>Count</th>
                                                    <th>Status</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr>
                                                    <td>Completed Trips</td>
                                                    <td>{{ $stats['completed_trips'] ?? 0 }}</td>
                                                    <td><span class="badge badge-success">Active</span></td>
                                                </tr>
                                                <tr>
                                                    <td>Pending Fees</td>
                                                    <td>{{ $stats['pending_fees'] ?? 0 }}</td>
                                                    <td><span class="badge badge-warning">Pending</span></td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection 