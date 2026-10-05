@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="mb-0">Communication Settings</h2>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="row">
        <!-- SMS Settings -->
        <div class="col-md-6 mb-4">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">
                        <i class="fas fa-sms me-2"></i>SMS Settings
                    </h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('communication.settings.sms') }}" method="POST">
                        @csrf
                        
                        <div class="mb-3">
                            <label for="sms_provider" class="form-label">SMS Provider</label>
                            <select class="form-select" id="sms_provider" name="sms_provider">
                                <option value="africas_talking" {{ old('sms_provider', 'africas_talking') == 'africas_talking' ? 'selected' : '' }}>Africa's Talking</option>
                                <option value="twilio" {{ old('sms_provider', 'twilio') == 'twilio' ? 'selected' : '' }}>Twilio</option>
                                <option value="custom" {{ old('sms_provider', 'custom') == 'custom' ? 'selected' : '' }}>Custom Provider</option>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label for="sms_api_key" class="form-label">API Key</label>
                            <input type="password" class="form-control" id="sms_api_key" name="sms_api_key" 
                                   value="{{ old('sms_api_key') }}" placeholder="Enter SMS API Key">
                        </div>

                        <div class="mb-3">
                            <label for="sms_username" class="form-label">Username</label>
                            <input type="text" class="form-control" id="sms_username" name="sms_username" 
                                   value="{{ old('sms_username') }}" placeholder="Enter SMS Username">
                        </div>

                        <div class="mb-3">
                            <label for="sms_sender_id" class="form-label">Sender ID</label>
                            <input type="text" class="form-control" id="sms_sender_id" name="sms_sender_id" 
                                   value="{{ old('sms_sender_id', 'SCHOOL') }}" placeholder="Enter Sender ID">
                        </div>

                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save me-2"></i>Update SMS Settings
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <!-- Email Settings -->
        <div class="col-md-6 mb-4">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">
                        <i class="fas fa-envelope me-2"></i>Email Settings
                    </h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('communication.settings.email') }}" method="POST">
                        @csrf
                        
                        <div class="mb-3">
                            <label for="email_provider" class="form-label">Email Provider</label>
                            <select class="form-select" id="email_provider" name="email_provider">
                                <option value="smtp" {{ old('email_provider', 'smtp') == 'smtp' ? 'selected' : '' }}>SMTP</option>
                                <option value="mailgun" {{ old('email_provider', 'mailgun') == 'mailgun' ? 'selected' : '' }}>Mailgun</option>
                                <option value="sendgrid" {{ old('email_provider', 'sendgrid') == 'sendgrid' ? 'selected' : '' }}>SendGrid</option>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label for="email_host" class="form-label">SMTP Host</label>
                            <input type="text" class="form-control" id="email_host" name="email_host" 
                                   value="{{ old('email_host') }}" placeholder="smtp.gmail.com">
                        </div>

                        <div class="mb-3">
                            <label for="email_port" class="form-label">SMTP Port</label>
                            <input type="number" class="form-control" id="email_port" name="email_port" 
                                   value="{{ old('email_port', 587) }}" placeholder="587">
                        </div>

                        <div class="mb-3">
                            <label for="email_username" class="form-label">Email Username</label>
                            <input type="email" class="form-control" id="email_username" name="email_username" 
                                   value="{{ old('email_username') }}" placeholder="your-email@domain.com">
                        </div>

                        <div class="mb-3">
                            <label for="email_password" class="form-label">Email Password</label>
                            <input type="password" class="form-control" id="email_password" name="email_password" 
                                   value="{{ old('email_password') }}" placeholder="Enter email password">
                        </div>

                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save me-2"></i>Update Email Settings
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Push Notification Settings -->
        <div class="col-md-6 mb-4">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">
                        <i class="fas fa-bell me-2"></i>Push Notification Settings
                    </h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('communication.settings.push') }}" method="POST">
                        @csrf
                        
                        <div class="mb-3">
                            <label for="fcm_server_key" class="form-label">FCM Server Key</label>
                            <input type="password" class="form-control" id="fcm_server_key" name="fcm_server_key" 
                                   value="{{ old('fcm_server_key') }}" placeholder="Enter FCM Server Key">
                        </div>

                        <div class="mb-3">
                            <label for="fcm_sender_id" class="form-label">FCM Sender ID</label>
                            <input type="text" class="form-control" id="fcm_sender_id" name="fcm_sender_id" 
                                   value="{{ old('fcm_sender_id') }}" placeholder="Enter FCM Sender ID">
                        </div>

                        <div class="mb-3">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="push_enabled" 
                                       name="push_enabled" value="1" checked>
                                <label class="form-check-label" for="push_enabled">
                                    Enable Push Notifications
                                </label>
                            </div>
                        </div>

                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save me-2"></i>Update Push Settings
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <!-- General Settings -->
        <div class="col-md-6 mb-4">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">
                        <i class="fas fa-cog me-2"></i>General Settings
                    </h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('communication.settings.general') }}" method="POST">
                        @csrf
                        
                        <div class="mb-3">
                            <label for="default_sender_name" class="form-label">Default Sender Name</label>
                            <input type="text" class="form-control" id="default_sender_name" name="default_sender_name" 
                                   value="{{ old('default_sender_name', 'School Administration') }}" placeholder="Enter default sender name">
                        </div>

                        <div class="mb-3">
                            <label for="message_retry_attempts" class="form-label">Message Retry Attempts</label>
                            <input type="number" class="form-control" id="message_retry_attempts" name="message_retry_attempts" 
                                   value="{{ old('message_retry_attempts', 3) }}" min="1" max="10">
                        </div>

                        <div class="mb-3">
                            <label for="message_delay" class="form-label">Message Delay (seconds)</label>
                            <input type="number" class="form-control" id="message_delay" name="message_delay" 
                                   value="{{ old('message_delay', 1) }}" min="0" max="60">
                        </div>

                        <div class="mb-3">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="auto_send" 
                                       name="auto_send" value="1" checked>
                                <label class="form-check-label" for="auto_send">
                                    Auto-send messages
                                </label>
                            </div>
                        </div>

                        <div class="mb-3">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="delivery_reports" 
                                       name="delivery_reports" value="1" checked>
                                <label class="form-check-label" for="delivery_reports">
                                    Enable delivery reports
                                </label>
                            </div>
                        </div>

                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save me-2"></i>Update General Settings
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- System Information -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">
                        <i class="fas fa-info-circle me-2"></i>System Information
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-3">
                            <h6>Total Messages Sent</h6>
                            <p class="text-muted">{{ $totalMessages ?? 0 }}</p>
                        </div>
                        <div class="col-md-3">
                            <h6>Active Contacts</h6>
                            <p class="text-muted">{{ $activeContacts ?? 0 }}</p>
                        </div>
                        <div class="col-md-3">
                            <h6>Active Groups</h6>
                            <p class="text-muted">{{ $activeGroups ?? 0 }}</p>
                        </div>
                        <div class="col-md-3">
                            <h6>Message Templates</h6>
                            <p class="text-muted">{{ $totalTemplates ?? 0 }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
