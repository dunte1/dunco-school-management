@extends('portal::components.layouts.master')

@section('content')
<div class="container-fluid py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h3 class="fw-bold mb-0"><i class="fas fa-shield-alt me-2"></i>Security Settings</h3>
        <a href="{{ route('portal.settings') }}" class="btn btn-outline-secondary">
            <i class="fas fa-arrow-left me-2"></i>Back to Settings
        </a>
    </div>

    <div class="row g-4">
        <div class="col-lg-8">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0"><i class="fas fa-key me-2"></i>Change Password</h5>
                </div>
                <div class="card-body">
                    <form method="POST" action="{{ route('portal.profile.update') }}">
                        @csrf
                        <input type="hidden" name="form_type" value="security">
                        
                        <div class="mb-3">
                            <label for="current_password" class="form-label">Current Password <span class="text-danger">*</span></label>
                            <input type="password" class="form-control @error('current_password') is-invalid @enderror" 
                                   id="current_password" name="current_password" required>
                            @error('current_password')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="password" class="form-label">New Password <span class="text-danger">*</span></label>
                            <input type="password" class="form-control @error('password') is-invalid @enderror" 
                                   id="password" name="password" required minlength="8">
                            @error('password')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <div class="form-text">Password must be at least 8 characters long.</div>
                        </div>

                        <div class="mb-3">
                            <label for="password_confirmation" class="form-label">Confirm New Password <span class="text-danger">*</span></label>
                            <input type="password" class="form-control" 
                                   id="password_confirmation" name="password_confirmation" required>
                        </div>

                        <div class="d-flex justify-content-end">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save me-2"></i>Update Password
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <div class="card mt-4">
                <div class="card-header">
                    <h5 class="mb-0"><i class="fas fa-history me-2"></i>Login History</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-sm">
                            <thead>
                                <tr>
                                    <th>Date & Time</th>
                                    <th>IP Address</th>
                                    <th>Device</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>{{ now()->format('M d, Y g:i A') }}</td>
                                    <td>{{ request()->ip() }}</td>
                                    <td>{{ request()->userAgent() ? substr(request()->userAgent(), 0, 50) . '...' : 'Unknown' }}</td>
                                    <td><span class="badge bg-success">Current Session</span></td>
                                </tr>
                                <tr>
                                    <td>{{ now()->subHours(2)->format('M d, Y g:i A') }}</td>
                                    <td>192.168.1.100</td>
                                    <td>Mozilla/5.0 (Windows NT 10.0; Win64; x64)...</td>
                                    <td><span class="badge bg-secondary">Ended</span></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0"><i class="fas fa-info-circle me-2"></i>Security Tips</h5>
                </div>
                <div class="card-body">
                    <div class="alert alert-info">
                        <h6><i class="fas fa-lightbulb me-2"></i>Password Security</h6>
                        <ul class="mb-0 small">
                            <li>Use a combination of letters, numbers, and symbols</li>
                            <li>Make it at least 8 characters long</li>
                            <li>Avoid using personal information</li>
                            <li>Don't reuse passwords from other accounts</li>
                        </ul>
                    </div>

                    <div class="alert alert-warning">
                        <h6><i class="fas fa-exclamation-triangle me-2"></i>Account Security</h6>
                        <ul class="mb-0 small">
                            <li>Always log out when using shared computers</li>
                            <li>Report suspicious activity immediately</li>
                            <li>Keep your contact information updated</li>
                            <li>Use a secure internet connection</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
