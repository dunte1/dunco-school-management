@extends('layouts.app')

@section('title', 'Notification Settings')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4 class="mb-0"><i class="fas fa-cog me-2"></i>Notification Settings</h4>
    </div>

    <div class="row mb-4">
        <div class="col-md-4">
            <div class="card shadow-sm text-center p-4">
                <i class="fas fa-paper-plane fa-2x text-success mb-2"></i>
                <h3 class="text-success">{{ $stats['total_sent'] }}</h3>
                <small class="text-muted">Total Sent</small>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card shadow-sm text-center p-4">
                <i class="fas fa-exclamation-triangle fa-2x text-danger mb-2"></i>
                <h3 class="text-danger">{{ $stats['total_failed'] }}</h3>
                <small class="text-muted">Total Failed</small>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card shadow-sm text-center p-4">
                <i class="fas fa-clock fa-2x text-warning mb-2"></i>
                <h3 class="text-warning">{{ $stats['total_pending'] }}</h3>
                <small class="text-muted">Total Pending</small>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-6">
            <div class="card shadow-sm mb-4">
                <div class="card-header"><h5 class="mb-0">Channel Statistics</h5></div>
                <div class="card-body">
                    <table class="table">
                        <tr>
                            <td><i class="fas fa-envelope me-2 text-primary"></i>Email Notifications</td>
                            <td class="text-end"><strong>{{ $stats['email_count'] }}</strong></td>
                        </tr>
                        <tr>
                            <td><i class="fas fa-sms me-2 text-success"></i>SMS Notifications</td>
                            <td class="text-end"><strong>{{ $stats['sms_count'] }}</strong></td>
                        </tr>
                        <tr>
                            <td><i class="fas fa-file-alt me-2 text-info"></i>Total Templates</td>
                            <td class="text-end"><strong>{{ $stats['templates_count'] }}</strong></td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="card shadow-sm mb-4">
                <div class="card-header"><h5 class="mb-0">Email Configuration</h5></div>
                <div class="card-body">
                    <div class="mb-3">
                        <label class="form-label">SMTP Host</label>
                        <input type="text" class="form-control" value="{{ config('mail.mailers.smtp.host', '') }}" disabled>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">SMTP Port</label>
                        <input type="text" class="form-control" value="{{ config('mail.mailers.smtp.port', '') }}" disabled>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">From Address</label>
                        <input type="text" class="form-control" value="{{ config('mail.from.address', '') }}" disabled>
                    </div>
                    <small class="text-muted">Email settings are configured in .env file. Contact your administrator to modify.</small>
                </div>
            </div>
        </div>
    </div>

    <div class="card shadow-sm mb-4">
        <div class="card-header"><h5 class="mb-0">Notification Preferences</h5></div>
        <div class="card-body">
            <form>
                <div class="row">
                    <div class="col-md-4 mb-3">
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" id="emailNotif" checked>
                            <label class="form-check-label" for="emailNotif">Enable Email Notifications</label>
                        </div>
                    </div>
                    <div class="col-md-4 mb-3">
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" id="smsNotif">
                            <label class="form-check-label" for="smsNotif">Enable SMS Notifications</label>
                        </div>
                    </div>
                    <div class="col-md-4 mb-3">
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" id="pushNotif" checked>
                            <label class="form-check-label" for="pushNotif">Enable Push Notifications</label>
                        </div>
                    </div>
                </div>
                <button type="button" class="btn btn-primary" disabled><i class="fas fa-save me-1"></i> Save Preferences</button>
                <small class="text-muted ms-2">Coming soon</small>
            </form>
        </div>
    </div>
</div>
@endsection
