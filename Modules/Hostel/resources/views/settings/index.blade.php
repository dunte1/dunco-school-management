@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="mb-0">Hostel Settings</h2>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="row">
        <!-- General Settings -->
        <div class="col-md-6 mb-4">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">
                        <i class="fas fa-cog me-2"></i>General Settings
                    </h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('hostel.settings.general') }}" method="POST">
                        @csrf
                        
                        <div class="mb-3">
                            <label for="hostel_name" class="form-label">Default Hostel Name</label>
                            <input type="text" class="form-control" id="hostel_name" name="hostel_name" 
                                   value="{{ old('hostel_name', 'Main Hostel') }}">
                        </div>

                        <div class="mb-3">
                            <label for="max_students_per_room" class="form-label">Max Students per Room</label>
                            <input type="number" class="form-control" id="max_students_per_room" name="max_students_per_room" 
                                   value="{{ old('max_students_per_room', 2) }}" min="1">
                        </div>

                        <div class="mb-3">
                            <label for="check_in_time" class="form-label">Check-in Time</label>
                            <input type="time" class="form-control" id="check_in_time" name="check_in_time" 
                                   value="{{ old('check_in_time', '18:00') }}">
                        </div>

                        <div class="mb-3">
                            <label for="check_out_time" class="form-label">Check-out Time</label>
                            <input type="time" class="form-control" id="check_out_time" name="check_out_time" 
                                   value="{{ old('check_out_time', '08:00') }}">
                        </div>

                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save me-2"></i>Update General Settings
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <!-- Fee Settings -->
        <div class="col-md-6 mb-4">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">
                        <i class="fas fa-dollar-sign me-2"></i>Fee Settings
                    </h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('hostel.settings.fees') }}" method="POST">
                        @csrf
                        
                        <div class="mb-3">
                            <label for="monthly_fee" class="form-label">Monthly Hostel Fee</label>
                            <div class="input-group">
                                <span class="input-group-text">KSh</span>
                                <input type="number" class="form-control" id="monthly_fee" name="monthly_fee" 
                                       value="{{ old('monthly_fee', 5000) }}" min="0" step="0.01">
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="security_deposit" class="form-label">Security Deposit</label>
                            <div class="input-group">
                                <span class="input-group-text">KSh</span>
                                <input type="number" class="form-control" id="security_deposit" name="security_deposit" 
                                       value="{{ old('security_deposit', 2000) }}" min="0" step="0.01">
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="late_fee_percentage" class="form-label">Late Fee Percentage</label>
                            <div class="input-group">
                                <input type="number" class="form-control" id="late_fee_percentage" name="late_fee_percentage" 
                                       value="{{ old('late_fee_percentage', 5) }}" min="0" max="100" step="0.01">
                                <span class="input-group-text">%</span>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="grace_period_days" class="form-label">Grace Period (Days)</label>
                            <input type="number" class="form-control" id="grace_period_days" name="grace_period_days" 
                                   value="{{ old('grace_period_days', 7) }}" min="0">
                        </div>

                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save me-2"></i>Update Fee Settings
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Notification Settings -->
        <div class="col-md-6 mb-4">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">
                        <i class="fas fa-bell me-2"></i>Notification Settings
                    </h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('hostel.settings.notifications') }}" method="POST">
                        @csrf
                        
                        <div class="mb-3">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="email_notifications" 
                                       name="email_notifications" value="1" checked>
                                <label class="form-check-label" for="email_notifications">
                                    Email Notifications
                                </label>
                            </div>
                        </div>

                        <div class="mb-3">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="fee_reminders" 
                                       name="fee_reminders" value="1" checked>
                                <label class="form-check-label" for="fee_reminders">
                                    Fee Payment Reminders
                                </label>
                            </div>
                        </div>

                        <div class="mb-3">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="maintenance_alerts" 
                                       name="maintenance_alerts" value="1" checked>
                                <label class="form-check-label" for="maintenance_alerts">
                                    Maintenance Alerts
                                </label>
                            </div>
                        </div>

                        <div class="mb-3">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="visitor_notifications" 
                                       name="visitor_notifications" value="1" checked>
                                <label class="form-check-label" for="visitor_notifications">
                                    Visitor Notifications
                                </label>
                            </div>
                        </div>

                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save me-2"></i>Update Notification Settings
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <!-- System Information -->
        <div class="col-md-6 mb-4">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">
                        <i class="fas fa-info-circle me-2"></i>System Information
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-6">
                            <h6>Total Hostels</h6>
                            <p class="text-muted">{{ $totalHostels ?? 0 }}</p>
                        </div>
                        <div class="col-6">
                            <h6>Total Rooms</h6>
                            <p class="text-muted">{{ $totalRooms ?? 0 }}</p>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-6">
                            <h6>Active Students</h6>
                            <p class="text-muted">{{ $activeStudents ?? 0 }}</p>
                        </div>
                        <div class="col-6">
                            <h6>Occupancy Rate</h6>
                            <p class="text-muted">{{ $occupancyRate ?? 0 }}%</p>
                        </div>
                    </div>
                    
                    <hr>
                    
                    <div class="text-center">
                        <small class="text-muted">Last updated: {{ now()->format('F d, Y \a\t g:i A') }}</small>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
