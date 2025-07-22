@extends('layouts.app')

@section('content')
    <div class="container-fluid py-4" style="min-height: 90vh; background: linear-gradient(135deg, #f8fafc 0%, #e0e7ff 100%);">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1 class="fw-bold mb-0" style="letter-spacing: -1px;"><i class="fas fa-users text-primary me-2"></i>HR Dashboard</h1>
            <span class="fs-5 text-muted">Welcome to the Staff & HR Management Module</span>
        </div>
        <div class="row g-4 mb-4">
            <div class="col-md-3">
                <div class="card shadow-sm border-0 text-center">
                    <div class="card-body">
                        <div class="fs-2 mb-2 text-primary"><i class="fas fa-users"></i></div>
                        <div class="fw-bold fs-4">{{ $totalStaff ?? 0 }}</div>
                        <div class="text-muted">Total Staff</div>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card shadow-sm border-0 text-center">
                    <div class="card-body">
                        <div class="fs-2 mb-2 text-warning"><i class="fas fa-plane-departure"></i></div>
                        <div class="fw-bold fs-4">{{ $onLeave ?? 0 }}</div>
                        <div class="text-muted">On Leave</div>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card shadow-sm border-0 text-center">
                    <div class="card-body">
                        <div class="fs-2 mb-2 text-danger"><i class="fas fa-clock"></i></div>
                        <div class="fw-bold fs-4">{{ $pendingApprovals ?? 0 }}</div>
                        <div class="text-muted">Pending Approvals</div>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <a href="{{ route('hr.performance_reviews.index') }}" class="card shadow-sm border-0 text-center text-decoration-none h-100">
                    <div class="card-body">
                        <div class="fs-2 mb-2 text-warning"><i class="fas fa-star-half-alt"></i></div>
                        <div class="fw-bold fs-4">Performance</div>
                        <div class="text-muted">Reviews</div>
                    </div>
                </a>
            </div>
        </div>
        <div class="row g-4 mb-4">
            <div class="col-md-3">
                <a href="{{ route('hr.staff.index') }}" class="card h-100 shadow-sm border-0 text-decoration-none">
                    <div class="card-body text-center">
                        <i class="fas fa-user-tie fa-2x mb-2 text-primary"></i>
                        <h5 class="card-title">Staff Management</h5>
                        <p class="card-text small">Manage all staff records, profiles, and details.</p>
                    </div>
                </a>
            </div>
            <div class="col-md-3">
                <a href="{{ route('attendance.index') }}" class="card h-100 shadow-sm border-0 text-decoration-none">
                    <div class="card-body text-center">
                        <i class="fas fa-calendar-check fa-2x mb-2 text-success"></i>
                        <h5 class="card-title">Attendance</h5>
                        <p class="card-text small">Track staff attendance and working hours.</p>
                    </div>
                </a>
            </div>
            <div class="col-md-3">
                <a href="{{ route('hr.leave.index') }}" class="card h-100 shadow-sm border-0 text-decoration-none">
                    <div class="card-body text-center">
                        <i class="fas fa-plane-departure fa-2x mb-2 text-warning"></i>
                        <h5 class="card-title">Leave Management</h5>
                        <p class="card-text small">Approve, reject, and manage staff leave.</p>
                    </div>
                </a>
            </div>
            <div class="col-md-3">
                <a href="{{ route('hr.payroll.index') }}" class="card h-100 shadow-sm border-0 text-decoration-none">
                    <div class="card-body text-center">
                        <i class="fas fa-money-check-alt fa-2x mb-2 text-success"></i>
                        <h5 class="card-title">Payroll</h5>
                        <p class="card-text small">Manage payroll, salaries, and payments.</p>
                    </div>
                </a>
            </div>
            <div class="col-md-3">
                <a href="{{ route('hr.contract.index') }}" class="card h-100 shadow-sm border-0 text-decoration-none">
                    <div class="card-body text-center">
                        <i class="fas fa-file-contract fa-2x mb-2 text-dark"></i>
                        <h5 class="card-title">Contracts & Job Info</h5>
                        <p class="card-text small">Manage contracts and job assignments.</p>
                    </div>
                </a>
            </div>
            <div class="col-md-3">
                <a href="{{ route('hr.roles.index') }}" class="card h-100 shadow-sm border-0 text-decoration-none">
                    <div class="card-body text-center">
                        <i class="fas fa-user-shield fa-2x mb-2 text-secondary"></i>
                        <h5 class="card-title">Roles</h5>
                        <p class="card-text small">Assign and manage staff roles.</p>
                    </div>
                </a>
            </div>
            <div class="col-md-3">
                <a href="{{ route('hr.permissions.index') }}" class="card h-100 shadow-sm border-0 text-decoration-none">
                    <div class="card-body text-center">
                        <i class="fas fa-key fa-2x mb-2 text-info"></i>
                        <h5 class="card-title">Permissions</h5>
                        <p class="card-text small">Manage access rights and permissions.</p>
                    </div>
                </a>
            </div>
        </div>
        <div class="row mb-4">
            <div class="col-md-12">
                <div class="card shadow-sm border-0">
                    <div class="card-body d-flex flex-wrap gap-2 justify-content-center">
                        <a href="{{ route('hr.staff.index') }}" class="btn btn-primary mb-2"><i class="fas fa-users me-1"></i> Staff</a>
                        <a href="{{ route('hr.roles.index') }}" class="btn btn-secondary mb-2"><i class="fas fa-user-shield me-1"></i> Roles</a>
                        <a href="{{ route('hr.permissions.index') }}" class="btn btn-info mb-2"><i class="fas fa-key me-1"></i> Permissions</a>
                        <a href="{{ route('attendance.index') }}" class="btn btn-success mb-2"><i class="fas fa-calendar-check me-1"></i> Attendance</a>
                        <a href="{{ route('hr.leave.index') }}" class="btn btn-warning mb-2"><i class="fas fa-plane-departure me-1"></i> Leave</a>
                        <a href="{{ route('hr.payroll.index') }}" class="btn btn-success mb-2"><i class="fas fa-money-check-alt me-1"></i> Payroll</a>
                        <a href="{{ route('hr.contract.index') }}" class="btn btn-dark mb-2"><i class="fas fa-file-contract me-1"></i> Contracts</a>
                        <a href="{{ route('hr.performance_reviews.index') }}" class="btn btn-warning mb-2"><i class="fas fa-star-half-alt me-1"></i> Performance</a>
                        <a href="{{ route('hr.departments.index') }}" class="btn btn-info mb-2"><i class="fas fa-building me-1"></i> Departments</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection 