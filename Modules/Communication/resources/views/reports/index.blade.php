@extends('communication::layouts.master')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1 class="mb-0">Reports & Analytics</h1>
    <div>
        <a href="{{ route('communication.dashboard') }}" class="btn btn-outline-secondary">
            <i class="fas fa-arrow-left me-2"></i>Back to Dashboard
        </a>
    </div>
</div>

<!-- Statistics Cards -->
<div class="row mb-4">
    <div class="col-md-2">
        <div class="card bg-primary text-white">
            <div class="card-body text-center">
                <h3 class="mb-0">{{ $stats['total_contacts'] }}</h3>
                <small>Total Contacts</small>
            </div>
        </div>
    </div>
    <div class="col-md-2">
        <div class="card bg-success text-white">
            <div class="card-body text-center">
                <h3 class="mb-0">{{ $stats['active_contacts'] }}</h3>
                <small>Active Contacts</small>
            </div>
        </div>
    </div>
    <div class="col-md-2">
        <div class="card bg-info text-white">
            <div class="card-body text-center">
                <h3 class="mb-0">{{ $stats['total_groups'] }}</h3>
                <small>Total Groups</small>
            </div>
        </div>
    </div>
    <div class="col-md-2">
        <div class="card bg-warning text-white">
            <div class="card-body text-center">
                <h3 class="mb-0">{{ $stats['total_templates'] }}</h3>
                <small>Templates</small>
            </div>
        </div>
    </div>
    <div class="col-md-2">
        <div class="card bg-danger text-white">
            <div class="card-body text-center">
                <h3 class="mb-0">{{ $stats['total_announcements'] }}</h3>
                <small>Announcements</small>
            </div>
        </div>
    </div>
    <div class="col-md-2">
        <div class="card bg-secondary text-white">
            <div class="card-body text-center">
                <h3 class="mb-0">{{ $stats['total_schedules'] }}</h3>
                <small>Schedules</small>
            </div>
        </div>
    </div>
</div>

<!-- Report Navigation -->
<div class="row mb-4">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">
                    <i class="fas fa-chart-bar me-2"></i>Detailed Reports
                </h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-2">
                        <a href="{{ route('communication.reports.contacts') }}" class="btn btn-outline-primary w-100 mb-2">
                            <i class="fas fa-address-book me-2"></i>Contacts Report
                        </a>
                    </div>
                    <div class="col-md-2">
                        <a href="{{ route('communication.reports.groups') }}" class="btn btn-outline-info w-100 mb-2">
                            <i class="fas fa-users me-2"></i>Groups Report
                        </a>
                    </div>
                    <div class="col-md-2">
                        <a href="{{ route('communication.reports.templates') }}" class="btn btn-outline-warning w-100 mb-2">
                            <i class="fas fa-file-alt me-2"></i>Templates Report
                        </a>
                    </div>
                    <div class="col-md-2">
                        <a href="{{ route('communication.reports.announcements') }}" class="btn btn-outline-danger w-100 mb-2">
                            <i class="fas fa-bullhorn me-2"></i>Announcements Report
                        </a>
                    </div>
                    <div class="col-md-2">
                        <a href="{{ route('communication.reports.schedules') }}" class="btn btn-outline-secondary w-100 mb-2">
                            <i class="fas fa-clock me-2"></i>Schedules Report
                        </a>
                    </div>
                    <div class="col-md-2">
                        <a href="{{ route('communication.import.index') }}" class="btn btn-outline-success w-100 mb-2">
                            <i class="fas fa-upload me-2"></i>Import Data
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Quick Export -->
<div class="row mb-4">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">
                    <i class="fas fa-download me-2"></i>Quick Export
                </h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-2">
                        <a href="{{ route('communication.reports.export', 'contacts') }}" class="btn btn-outline-primary w-100 mb-2">
                            <i class="fas fa-download me-2"></i>Export Contacts
                        </a>
                    </div>
                    <div class="col-md-2">
                        <a href="{{ route('communication.reports.export', 'groups') }}" class="btn btn-outline-info w-100 mb-2">
                            <i class="fas fa-download me-2"></i>Export Groups
                        </a>
                    </div>
                    <div class="col-md-2">
                        <a href="{{ route('communication.reports.export', 'templates') }}" class="btn btn-outline-warning w-100 mb-2">
                            <i class="fas fa-download me-2"></i>Export Templates
                        </a>
                    </div>
                    <div class="col-md-2">
                        <a href="{{ route('communication.reports.export', 'announcements') }}" class="btn btn-outline-danger w-100 mb-2">
                            <i class="fas fa-download me-2"></i>Export Announcements
                        </a>
                    </div>
                    <div class="col-md-2">
                        <a href="{{ route('communication.reports.export', 'schedules') }}" class="btn btn-outline-secondary w-100 mb-2">
                            <i class="fas fa-download me-2"></i>Export Schedules
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
