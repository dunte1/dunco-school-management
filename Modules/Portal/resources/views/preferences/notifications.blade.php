@extends('portal::components.layouts.master')

@section('content')
<div class="container-fluid py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h3 class="fw-bold mb-0"><i class="fas fa-bell me-2"></i>Notification Settings</h3>
        <a href="{{ route('portal.preferences') }}" class="btn btn-outline-secondary">
            <i class="fas fa-arrow-left me-2"></i>Back to Preferences
        </a>
    </div>

    <div class="row g-4">
        <div class="col-lg-8">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0"><i class="fas fa-cog me-2"></i>Notification Preferences</h5>
                </div>
                <div class="card-body">
                    <form method="POST" action="{{ route('portal.profile.update') }}">
                        @csrf
                        <input type="hidden" name="form_type" value="preferences">
                        
                        <div class="row">
                            <div class="col-md-6">
                                <h6 class="fw-bold mb-3">Email Notifications</h6>
                                
                                <div class="form-check mb-3">
                                    <input class="form-check-input" type="checkbox" name="notifications[email][grades]" 
                                           id="email_grades" value="1" {{ $user->getSetting('notifications.email.grades', true) ? 'checked' : '' }}>
                                    <label class="form-check-label" for="email_grades">
                                        <strong>Grade Updates</strong><br>
                                        <small class="text-muted">Get notified when new grades are posted</small>
                                    </label>
                                </div>

                                <div class="form-check mb-3">
                                    <input class="form-check-input" type="checkbox" name="notifications[email][assignments]" 
                                           id="email_assignments" value="1" {{ $user->getSetting('notifications.email.assignments', true) ? 'checked' : '' }}>
                                    <label class="form-check-label" for="email_assignments">
                                        <strong>Assignment Reminders</strong><br>
                                        <small class="text-muted">Reminders for upcoming assignments</small>
                                    </label>
                                </div>

                                <div class="form-check mb-3">
                                    <input class="form-check-input" type="checkbox" name="notifications[email][fees]" 
                                           id="email_fees" value="1" {{ $user->getSetting('notifications.email.fees', true) ? 'checked' : '' }}>
                                    <label class="form-check-label" for="email_fees">
                                        <strong>Fee Notifications</strong><br>
                                        <small class="text-muted">Updates about fee payments and due dates</small>
                                    </label>
                                </div>

                                <div class="form-check mb-3">
                                    <input class="form-check-input" type="checkbox" name="notifications[email][announcements]" 
                                           id="email_announcements" value="1" {{ $user->getSetting('notifications.email.announcements', true) ? 'checked' : '' }}>
                                    <label class="form-check-label" for="email_announcements">
                                        <strong>School Announcements</strong><br>
                                        <small class="text-muted">Important school-wide announcements</small>
                                    </label>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <h6 class="fw-bold mb-3">SMS Notifications</h6>
                                
                                <div class="form-check mb-3">
                                    <input class="form-check-input" type="checkbox" name="notifications[sms][urgent]" 
                                           id="sms_urgent" value="1" {{ $user->getSetting('notifications.sms.urgent', false) ? 'checked' : '' }}>
                                    <label class="form-check-label" for="sms_urgent">
                                        <strong>Urgent Messages</strong><br>
                                        <small class="text-muted">Critical school communications</small>
                                    </label>
                                </div>

                                <div class="form-check mb-3">
                                    <input class="form-check-input" type="checkbox" name="notifications[sms][attendance]" 
                                           id="sms_attendance" value="1" {{ $user->getSetting('notifications.sms.attendance', false) ? 'checked' : '' }}>
                                    <label class="form-check-label" for="sms_attendance">
                                        <strong>Attendance Alerts</strong><br>
                                        <small class="text-muted">Daily attendance notifications</small>
                                    </label>
                                </div>

                                <div class="form-check mb-3">
                                    <input class="form-check-input" type="checkbox" name="notifications[sms][emergency]" 
                                           id="sms_emergency" value="1" {{ $user->getSetting('notifications.sms.emergency', true) ? 'checked' : '' }}>
                                    <label class="form-check-label" for="sms_emergency">
                                        <strong>Emergency Alerts</strong><br>
                                        <small class="text-muted">Emergency notifications and alerts</small>
                                    </label>
                                </div>
                            </div>
                        </div>

                        <hr>

                        <div class="row">
                            <div class="col-12">
                                <h6 class="fw-bold mb-3">Notification Frequency</h6>
                                
                                <div class="form-check mb-2">
                                    <input class="form-check-input" type="radio" name="notification_frequency" 
                                           id="frequency_immediate" value="immediate" {{ $user->getSetting('notification_frequency', 'immediate') === 'immediate' ? 'checked' : '' }}>
                                    <label class="form-check-label" for="frequency_immediate">
                                        <strong>Immediate</strong> - Get notifications as soon as they're available
                                    </label>
                                </div>

                                <div class="form-check mb-2">
                                    <input class="form-check-input" type="radio" name="notification_frequency" 
                                           id="frequency_daily" value="daily" {{ $user->getSetting('notification_frequency') === 'daily' ? 'checked' : '' }}>
                                    <label class="form-check-label" for="frequency_daily">
                                        <strong>Daily Digest</strong> - Receive a summary once per day
                                    </label>
                                </div>

                                <div class="form-check mb-2">
                                    <input class="form-check-input" type="radio" name="notification_frequency" 
                                           id="frequency_weekly" value="weekly" {{ $user->getSetting('notification_frequency') === 'weekly' ? 'checked' : '' }}>
                                    <label class="form-check-label" for="frequency_weekly">
                                        <strong>Weekly Summary</strong> - Get a weekly summary of activities
                                    </label>
                                </div>
                            </div>
                        </div>

                        <div class="d-flex justify-content-end mt-4">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save me-2"></i>Save Notification Settings
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0"><i class="fas fa-info-circle me-2"></i>Notification Info</h5>
                </div>
                <div class="card-body">
                    <div class="alert alert-info">
                        <h6><i class="fas fa-lightbulb me-2"></i>Email Notifications</h6>
                        <p class="mb-0 small">Email notifications are sent to your registered email address. Make sure your email is up to date in your profile settings.</p>
                    </div>

                    <div class="alert alert-warning">
                        <h6><i class="fas fa-mobile-alt me-2"></i>SMS Notifications</h6>
                        <p class="mb-0 small">SMS notifications require a valid phone number. Update your phone number in your profile to receive SMS alerts.</p>
                    </div>

                    <div class="alert alert-success">
                        <h6><i class="fas fa-shield-alt me-2"></i>Privacy</h6>
                        <p class="mb-0 small">Your notification preferences are private and only affect your account. You can change these settings at any time.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
