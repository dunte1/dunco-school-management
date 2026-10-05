@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="mb-0">Document Settings</h2>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="row">
        <!-- Storage Settings -->
        <div class="col-md-6 mb-4">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">
                        <i class="fas fa-hdd me-2"></i>Storage Settings
                    </h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('document.settings.storage') }}" method="POST">
                        @csrf
                        
                        <div class="mb-3">
                            <label for="storage_limit" class="form-label">Storage Limit (bytes)</label>
                            <input type="number" class="form-control @error('storage_limit') is-invalid @enderror" 
                                   id="storage_limit" name="storage_limit" 
                                   value="{{ old('storage_limit', $settings['storage_limit']) }}" required>
                            <div class="form-text">Current: {{ number_format($settings['storage_limit'] / 1024 / 1024 / 1024, 2) }} GB</div>
                            @error('storage_limit')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="max_file_size" class="form-label">Max File Size (bytes)</label>
                            <input type="number" class="form-control @error('max_file_size') is-invalid @enderror" 
                                   id="max_file_size" name="max_file_size" 
                                   value="{{ old('max_file_size', $settings['max_file_size']) }}" required>
                            <div class="form-text">Current: {{ number_format($settings['max_file_size'] / 1024 / 1024, 2) }} MB</div>
                            @error('max_file_size')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="allowed_extensions" class="form-label">Allowed File Extensions</label>
                            <div class="row">
                                @foreach(['pdf', 'doc', 'docx', 'xls', 'xlsx', 'ppt', 'pptx', 'txt', 'jpg', 'jpeg', 'png', 'gif'] as $ext)
                                <div class="col-md-4">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" 
                                               id="ext_{{ $ext }}" name="allowed_extensions[]" 
                                               value="{{ $ext }}"
                                               {{ in_array($ext, $settings['allowed_extensions']) ? 'checked' : '' }}>
                                        <label class="form-check-label" for="ext_{{ $ext }}">
                                            .{{ $ext }}
                                        </label>
                                    </div>
                                </div>
                                @endforeach
                            </div>
                            @error('allowed_extensions')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save me-2"></i>Update Storage Settings
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <!-- Security Settings -->
        <div class="col-md-6 mb-4">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">
                        <i class="fas fa-shield-alt me-2"></i>Security Settings
                    </h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('document.settings.security') }}" method="POST">
                        @csrf
                        
                        <div class="mb-3">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="enable_encryption" 
                                       name="enable_encryption" value="1" 
                                       {{ old('enable_encryption', true) ? 'checked' : '' }}>
                                <label class="form-check-label" for="enable_encryption">
                                    Enable File Encryption
                                </label>
                            </div>
                        </div>

                        <div class="mb-3">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="require_authentication" 
                                       name="require_authentication" value="1" 
                                       {{ old('require_authentication', true) ? 'checked' : '' }}>
                                <label class="form-check-label" for="require_authentication">
                                    Require Authentication for Downloads
                                </label>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="session_timeout" class="form-label">Session Timeout (minutes)</label>
                            <input type="number" class="form-control @error('session_timeout') is-invalid @enderror" 
                                   id="session_timeout" name="session_timeout" 
                                   value="{{ old('session_timeout', 30) }}" required>
                            @error('session_timeout')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="max_login_attempts" class="form-label">Max Login Attempts</label>
                            <input type="number" class="form-control @error('max_login_attempts') is-invalid @enderror" 
                                   id="max_login_attempts" name="max_login_attempts" 
                                   value="{{ old('max_login_attempts', 5) }}" required>
                            @error('max_login_attempts')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save me-2"></i>Update Security Settings
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
                    <form action="{{ route('document.settings.notifications') }}" method="POST">
                        @csrf
                        
                        <div class="mb-3">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="email_notifications" 
                                       name="email_notifications" value="1" 
                                       {{ old('email_notifications', true) ? 'checked' : '' }}>
                                <label class="form-check-label" for="email_notifications">
                                    Email Notifications
                                </label>
                            </div>
                        </div>

                        <div class="mb-3">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="upload_notifications" 
                                       name="upload_notifications" value="1" 
                                       {{ old('upload_notifications', true) ? 'checked' : '' }}>
                                <label class="form-check-label" for="upload_notifications">
                                    Upload Notifications
                                </label>
                            </div>
                        </div>

                        <div class="mb-3">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="share_notifications" 
                                       name="share_notifications" value="1" 
                                       {{ old('share_notifications', true) ? 'checked' : '' }}>
                                <label class="form-check-label" for="share_notifications">
                                    Share Notifications
                                </label>
                            </div>
                        </div>

                        <div class="mb-3">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="system_notifications" 
                                       name="system_notifications" value="1" 
                                       {{ old('system_notifications', true) ? 'checked' : '' }}>
                                <label class="form-check-label" for="system_notifications">
                                    System Notifications
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
                            <h6>Auto Versioning</h6>
                            <p class="text-muted">
                                <span class="badge bg-{{ $settings['auto_versioning'] ? 'success' : 'secondary' }}">
                                    {{ $settings['auto_versioning'] ? 'Enabled' : 'Disabled' }}
                                </span>
                            </p>
                        </div>
                        <div class="col-6">
                            <h6>Sharing</h6>
                            <p class="text-muted">
                                <span class="badge bg-{{ $settings['enable_sharing'] ? 'success' : 'secondary' }}">
                                    {{ $settings['enable_sharing'] ? 'Enabled' : 'Disabled' }}
                                </span>
                            </p>
                        </div>
                    </div>
                    
                    <hr>
                    
                    <div class="row">
                        <div class="col-12">
                            <h6>Current Storage Usage</h6>
                            <div class="progress mb-2">
                                <div class="progress-bar" role="progressbar" style="width: 25%">
                                    25%
                                </div>
                            </div>
                            <small class="text-muted">2.5 GB of 10 GB used</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
