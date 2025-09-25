@extends('portal::components.layouts.master')

@section('content')
<div class="container-fluid py-4">

    @if (session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    @endif

    @if ($errors->any())
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <h5 class="alert-heading">Please fix the following errors:</h5>
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    @endif

    {{-- Section A: Profile Header --}}
    <div class="card mb-4">
        <div class="card-body d-flex align-items-center">
            <img src="{{ $user->avatar ? asset('storage/' . $user->avatar) : 'https://ui-avatars.com/api/?name=' . urlencode($user->name) . '&background=random&color=fff' }}" class="rounded-circle me-3" width="80" alt="Avatar">
            <div>
                <h4 class="fw-bold mb-0">{{ $user->name }}</h4>
                <p class="text-muted mb-0">{{ $user->email }}</p>
                @if($user->hasRole('student'))
                    <span class="badge bg-primary">Student</span>
                @elseif($user->hasRole('parent'))
                    <span class="badge bg-info">Parent</span>
                @else
                    <span class="badge bg-secondary">{{ ucfirst($user->roles->first()?->name ?? 'User') }}</span>
                @endif
            </div>
        </div>
    </div>

    {{-- Tab Navigation --}}
    <ul class="nav nav-pills nav-fill mb-4" id="profile-tabs" role="tablist">
        <li class="nav-item" role="presentation">
            <button class="nav-link active" id="personal-tab" data-bs-toggle="tab" data-bs-target="#personal" type="button" role="tab">Personal Info</button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link" id="security-tab" data-bs-toggle="tab" data-bs-target="#security" type="button" role="tab">Security</button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link" id="preferences-tab" data-bs-toggle="tab" data-bs-target="#preferences" type="button" role="tab">Preferences</button>
        </li>
    </ul>

    {{-- Tab Content --}}
    <div class="tab-content" id="profile-tabs-content">
        {{-- Personal Information Tab --}}
        <div class="tab-pane fade show active" id="personal" role="tabpanel">
            <div class="card">
                <div class="card-header"><h5 class="mb-0">Personal & Contact Details</h5></div>
                <div class="card-body">
                    <form action="{{ route('portal.profile.update') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PATCH')
                        <input type="hidden" name="form_type" value="personal">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Full Name</label>
                                <input type="text" class="form-control" value="{{ $user->name }}" readonly>
                            </div>
                             <div class="col-md-6 mb-3">
                                <label class="form-label">Email Address</label>
                                <input type="email" name="email" class="form-control" value="{{ old('email', $user->email) }}">
                            </div>
                             <div class="col-md-6 mb-3">
                                <label class="form-label">Phone Number</label>
                                <input type="text" name="phone" class="form-control" value="{{ old('phone', $user->phone) }}">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="avatar" class="form-label">Profile Photo</label>
                                <input type="file" id="avatar" name="avatar" class="form-control">
                            </div>
                        </div>
                        <button type="submit" class="btn btn-primary">Save Changes</button>
                    </form>
                </div>
            </div>
        </div>

        {{-- Security Tab --}}
        <div class="tab-pane fade" id="security" role="tabpanel">
            <div class="card">
                <div class="card-header"><h5 class="mb-0">Security Settings</h5></div>
                <div class="card-body">
                    <form action="{{ route('portal.profile.update') }}" method="POST">
                        @csrf
                        @method('PATCH')
                        <input type="hidden" name="form_type" value="security">
                        <h6 class="mb-3">Change Password</h6>
                        <div class="mb-3">
                            <label for="current_password" class="form-label">Current Password</label>
                            <input type="password" id="current_password" name="current_password" class="form-control">
                        </div>
                        <div class="mb-3">
                            <label for="password" class="form-label">New Password</label>
                            <input type="password" id="password" name="password" class="form-control">
                        </div>
                        <div class="mb-3">
                            <label for="password_confirmation" class="form-label">Confirm New Password</label>
                            <input type="password" id="password_confirmation" name="password_confirmation" class="form-control">
                        </div>
                        <button type="submit" class="btn btn-primary">Update Password</button>
                    </form>
                </div>
            </div>
        </div>

        {{-- Preferences Tab --}}
        <div class="tab-pane fade" id="preferences" role="tabpanel">
             <div class="card">
                <div class="card-header"><h5 class="mb-0">Portal Preferences</h5></div>
                <div class="card-body">
                     <form action="{{ route('portal.profile.update') }}" method="POST">
                        @csrf
                        @method('PATCH')
                        <input type="hidden" name="form_type" value="preferences">
                        <h6 class="mb-3">Theme</h6>
                         <div class="form-check">
                           <input class="form-check-input" type="radio" name="theme" id="light" value="light" {{ old('theme', $user->getSetting('theme', 'light')) == 'light' ? 'checked' : '' }}>
                           <label class="form-check-label" for="light">Light Mode</label>
                         </div>
                         <div class="form-check mb-3">
                           <input class="form-check-input" type="radio" name="theme" id="dark" value="dark" {{ old('theme', $user->getSetting('theme')) == 'dark' ? 'checked' : '' }}>
                           <label class="form-check-label" for="dark">Dark Mode</label>
                         </div>
                        <hr>
                        <h6 class="mt-4 mb-3">Notification Preferences</h6>
                        <div class="form-check form-switch">
                          <input class="form-check-input" type="checkbox" name="notifications[email]" id="email_notifications" {{ $user->getSetting('notifications.email', true) ? 'checked' : '' }}>
                          <label class="form-check-label" for="email_notifications">Email Notifications</label>
                        </div>
                         <div class="form-check form-switch">
                          <input class="form-check-input" type="checkbox" name="notifications[sms]" id="sms_notifications" {{ $user->getSetting('notifications.sms', false) ? 'checked' : '' }}>
                          <label class="form-check-label" for="sms_notifications">SMS Notifications</label>
                        </div>
                        <button type="submit" class="btn btn-primary mt-3">Save Preferences</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 