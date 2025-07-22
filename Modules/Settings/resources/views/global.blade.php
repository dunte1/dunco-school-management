@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Global Settings</h1>
    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    @if($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif
    <form action="{{ route('settings.global.update') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="mb-3">
            <label for="system_name" class="form-label">System Name</label>
            <input type="text" name="system_name" id="system_name" class="form-control" value="{{ old('system_name', $settings['system_name'] ?? '') }}" required>
        </div>
        <div class="mb-3">
            <label for="logo" class="form-label">Logo</label>
            <input type="file" name="logo" id="logo" class="form-control">
            @if(!empty($settings['logo_path']))
                <img src="{{ asset('storage/' . $settings['logo_path']) }}" alt="Logo" height="40">
                <div class="form-check">
                    <input type="checkbox" name="delete_logo" id="delete_logo" class="form-check-input">
                    <label for="delete_logo" class="form-check-label">Delete Logo</label>
                </div>
            @endif
        </div>
        <div class="mb-3">
            <label for="favicon" class="form-label">Favicon</label>
            <input type="file" name="favicon" id="favicon" class="form-control">
            @if(!empty($settings['favicon_path']))
                <img src="{{ asset('storage/' . $settings['favicon_path']) }}" alt="Favicon" height="24">
                <div class="form-check">
                    <input type="checkbox" name="delete_favicon" id="delete_favicon" class="form-check-input">
                    <label for="delete_favicon" class="form-check-label">Delete Favicon</label>
                </div>
            @endif
        </div>
        <div class="mb-3">
            <label for="default_language" class="form-label">Default Language</label>
            <select name="default_language" id="default_language" class="form-control" required>
                @foreach($languages as $code => $label)
                    <option value="{{ $code }}" {{ (old('default_language', $settings['default_language'] ?? '') == $code) ? 'selected' : '' }}>{{ $label }}</option>
                @endforeach
            </select>
        </div>
        <div class="mb-3">
            <label for="default_timezone" class="form-label">Default Timezone</label>
            <select name="default_timezone" id="default_timezone" class="form-control" required>
                @foreach($timezones as $tz)
                    <option value="{{ $tz }}" {{ (old('default_timezone', $settings['default_timezone'] ?? '') == $tz) ? 'selected' : '' }}>{{ $tz }}</option>
                @endforeach
            </select>
        </div>
        <div class="mb-3 form-check">
            <input type="checkbox" name="enable_exams" id="enable_exams" class="form-check-input" value="1" {{ old('enable_exams', $settings['enable_exams'] ?? false) ? 'checked' : '' }}>
            <label for="enable_exams" class="form-check-label">Enable Exams</label>
        </div>
        <div class="mb-3 form-check">
            <input type="checkbox" name="enable_finance" id="enable_finance" class="form-check-input" value="1" {{ old('enable_finance', $settings['enable_finance'] ?? false) ? 'checked' : '' }}>
            <label for="enable_finance" class="form-check-label">Enable Finance</label>
        </div>
        <div class="mb-3 form-check">
            <input type="checkbox" name="enable_attendance" id="enable_attendance" class="form-check-input" value="1" {{ old('enable_attendance', $settings['enable_attendance'] ?? false) ? 'checked' : '' }}>
            <label for="enable_attendance" class="form-check-label">Enable Attendance</label>
        </div>
        <div class="mb-3 form-check">
            <input type="checkbox" name="enable_library" id="enable_library" class="form-check-input" value="1" {{ old('enable_library', $settings['enable_library'] ?? false) ? 'checked' : '' }}>
            <label for="enable_library" class="form-check-label">Enable Library</label>
        </div>
        <div class="mb-3 form-check">
            <input type="checkbox" name="enable_notifications" id="enable_notifications" class="form-check-input" value="1" {{ old('enable_notifications', $settings['enable_notifications'] ?? false) ? 'checked' : '' }}>
            <label for="enable_notifications" class="form-check-label">Enable Notifications</label>
        </div>
        <h4>SMTP & Mail Settings</h4>
        <div class="mb-3">
            <label for="smtp_host" class="form-label">SMTP Host</label>
            <input type="text" name="smtp_host" id="smtp_host" class="form-control" value="{{ old('smtp_host', $settings['smtp_host'] ?? '') }}">
        </div>
        <div class="mb-3">
            <label for="smtp_port" class="form-label">SMTP Port</label>
            <input type="text" name="smtp_port" id="smtp_port" class="form-control" value="{{ old('smtp_port', $settings['smtp_port'] ?? '') }}">
        </div>
        <div class="mb-3">
            <label for="smtp_user" class="form-label">SMTP User</label>
            <input type="text" name="smtp_user" id="smtp_user" class="form-control" value="{{ old('smtp_user', $settings['smtp_user'] ?? '') }}">
        </div>
        <div class="mb-3">
            <label for="smtp_pass" class="form-label">SMTP Password</label>
            <input type="password" name="smtp_pass" id="smtp_pass" class="form-control" value="{{ old('smtp_pass', $settings['smtp_pass'] ?? '') }}">
        </div>
        <div class="mb-3">
            <label for="smtp_encryption" class="form-label">SMTP Encryption</label>
            <select name="smtp_encryption" id="smtp_encryption" class="form-control">
                <option value="">None</option>
                <option value="ssl" {{ (old('smtp_encryption', $settings['smtp_encryption'] ?? '') == 'ssl') ? 'selected' : '' }}>SSL</option>
                <option value="tls" {{ (old('smtp_encryption', $settings['smtp_encryption'] ?? '') == 'tls') ? 'selected' : '' }}>TLS</option>
            </select>
        </div>
        <div class="mb-3">
            <label for="smtp_from_address" class="form-label">SMTP From Address</label>
            <input type="email" name="smtp_from_address" id="smtp_from_address" class="form-control" value="{{ old('smtp_from_address', $settings['smtp_from_address'] ?? '') }}">
        </div>
        <h4>SMS Gateway Settings</h4>
        <div class="mb-3">
            <label for="sms_gateway_url" class="form-label">SMS Gateway URL</label>
            <input type="text" name="sms_gateway_url" id="sms_gateway_url" class="form-control" value="{{ old('sms_gateway_url', $settings['sms_gateway_url'] ?? '') }}">
        </div>
        <div class="mb-3">
            <label for="sms_api_key" class="form-label">SMS API Key</label>
            <input type="text" name="sms_api_key" id="sms_api_key" class="form-control" value="{{ old('sms_api_key', $settings['sms_api_key'] ?? '') }}">
        </div>
        <div class="mb-3">
            <label for="sms_sender_id" class="form-label">SMS Sender ID</label>
            <input type="text" name="sms_sender_id" id="sms_sender_id" class="form-control" value="{{ old('sms_sender_id', $settings['sms_sender_id'] ?? '') }}">
        </div>
        <h4>API Token</h4>
        <div class="mb-3">
            <label for="api_token" class="form-label">API Token</label>
            <input type="text" name="api_token" id="api_token" class="form-control" value="{{ old('api_token', $settings['api_token'] ?? '') }}">
        </div>
        <button type="submit" class="btn btn-success">Save Settings</button>
    </form>
</div>
@endsection 