@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0">HR Dashboard</h1>
        <div class="text-muted">Welcome back, {{ Auth::user()->name }}!</div>
    </div>

    <div class="row mb-4">
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">Total Staff</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $stats['total_staff'] ?? 0 }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-users fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-success shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">Active Staff</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $stats['active_staff'] ?? 0 }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-user-check fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-warning shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">Pending Leaves</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $stats['pending_leaves'] ?? 0 }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-calendar-times fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-info shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-info text-uppercase mb-1">Departments</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $stats['total_departments'] ?? 0 }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-building fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row mb-4">
        <div class="col-lg-6">
            <div class="card shadow">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Quick Actions</h6>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <a href="{{ route('hr.index') }}" class="btn btn-primary btn-block">
                                <i class="fas fa-users me-2"></i>Manage Staff
                            </a>
                        </div>
                        <div class="col-md-6 mb-3">
                            <a href="{{ route('hr.leave.index') }}" class="btn btn-warning btn-block">
                                <i class="fas fa-calendar-alt me-2"></i>Leave Requests
                            </a>
                        </div>
                        <div class="col-md-6 mb-3">
                            <a href="{{ route('hr.payroll.index') }}" class="btn btn-success btn-block">
                                <i class="fas fa-money-bill me-2"></i>Payroll
                            </a>
                        </div>
                        <div class="col-md-6 mb-3">
                            <a href="{{ url('/hr/departments') }}" class="btn btn-info btn-block">
                                <i class="fas fa-building me-2"></i>Departments
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-6">
            <div class="card shadow">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Staff Overview</h6>
                </div>
                <div class="card-body">
                    @php
                        $totalStaff = $stats['total_staff'] ?? 1;
                        $activeStaff = $stats['active_staff'] ?? 0;
                        $activeRate = $totalStaff > 0 ? round(($activeStaff / $totalStaff) * 100, 1) : 0;
                    @endphp
                    <div class="progress mb-3" style="height: 24px;">
                        <div class="progress-bar bg-success" role="progressbar" style="width: {{ $activeRate }}%;" aria-valuenow="{{ $activeRate }}" aria-valuemin="0" aria-valuemax="100">
                            {{ $activeRate }}% Active
                        </div>
                    </div>
                    <p class="mb-1"><strong>Total Staff:</strong> {{ $stats['total_staff'] ?? 0 }}</p>
                    <p class="mb-1"><strong>Active Staff:</strong> {{ $stats['active_staff'] ?? 0 }}</p>
                    <p class="mb-1"><strong>Pending Leaves:</strong> {{ $stats['pending_leaves'] ?? 0 }}</p>
                    <p class="mb-0"><strong>Departments:</strong> {{ $stats['total_departments'] ?? 0 }}</p>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="card shadow">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Recent Leave Requests</h6>
                </div>
                <div class="card-body">
                    @php
                        $recentLeaves = \Modules\HR\Models\Leave::latest()->take(5)->get();
                    @endphp
                    @if($recentLeaves && $recentLeaves->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th>Staff</th>
                                        <th>Leave Type</th>
                                        <th>Start Date</th>
                                        <th>End Date</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($recentLeaves as $leave)
                                    <tr>
                                        <td>{{ $leave->staff->first_name ?? 'N/A' }} {{ $leave->staff->last_name ?? '' }}</td>
                                        <td>{{ $leave->leave_type ?? 'N/A' }}</td>
                                        <td>{{ $leave->start_date ? \Carbon\Carbon::parse($leave->start_date)->format('M d, Y') : 'N/A' }}</td>
                                        <td>{{ $leave->end_date ? \Carbon\Carbon::parse($leave->end_date)->format('M d, Y') : 'N/A' }}</td>
                                        <td>
                                            <span class="badge bg-{{ ($leave->status ?? '') === 'approved' ? 'success' : (($leave->status ?? '') === 'rejected' ? 'danger' : 'warning') }}">
                                                {{ ucfirst($leave->status ?? 'pending') }}
                                            </span>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="text-center py-4">
                            <i class="fas fa-calendar-check fa-3x text-muted mb-3"></i>
                            <h5 class="text-muted">No recent leave requests</h5>
                            <p class="text-muted">Leave requests will appear here once submitted.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
