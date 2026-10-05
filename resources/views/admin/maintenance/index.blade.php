@extends('layouts.app')

@section('content')
<div class="container py-4">
    <h1 class="h4 mb-3">Maintenance Mode</h1>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <div class="card">
        <div class="card-body">
            <p class="mb-3">Status: <span class="badge {{ $isDown ? 'bg-danger' : 'bg-success' }}">{{ $isDown ? 'Active' : 'Inactive' }}</span></p>
            <form action="{{ route('admin.maintenance.activate') }}" method="POST" class="d-inline">
                @csrf
                <button class="btn btn-warning" {{ $isDown ? 'disabled' : '' }} onclick="return confirm('Activate maintenance mode? You may be logged out.');">Activate</button>
            </form>
            <form action="{{ route('admin.maintenance.deactivate') }}" method="POST" class="d-inline ms-2">
                @csrf
                <button class="btn btn-primary" {{ $isDown ? '' : 'disabled' }}>Deactivate</button>
            </form>
            <hr>
            <h5 class="mb-3">App Settings</h5>
            <form method="POST" action="{{ route('admin.settings.app.update') }}" class="row g-2">
                @csrf
                <div class="col-md-3">
                    <label class="form-label">Enable Online Exams</label>
                    @php($v = \App\Models\Setting::where('key','app.enable_online_exams')->value('value'))
                    <select class="form-select" name="app.enable_online_exams">
                        <option value="1" {{ $v ? 'selected' : '' }}>Yes</option>
                        <option value="0" {{ !$v ? 'selected' : '' }}>No</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label">Enable Payments</label>
                    @php($p = \App\Models\Setting::where('key','app.enable_payments')->value('value'))
                    <select class="form-select" name="app.enable_payments">
                        <option value="1" {{ $p ? 'selected' : '' }}>Yes</option>
                        <option value="0" {{ !$p ? 'selected' : '' }}>No</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label">Min App Version</label>
                    @php($min = \App\Models\Setting::where('key','app.min_version')->value('value'))
                    <input class="form-control" name="app.min_version" value="{{ $min ?? '1.0.0' }}"/>
                </div>
                <div class="col-md-3">
                    <label class="form-label">Force Update</label>
                    @php($f = \App\Models\Setting::where('key','app.force_update')->value('value'))
                    <select class="form-select" name="app.force_update">
                        <option value="1" {{ $f ? 'selected' : '' }}>Yes</option>
                        <option value="0" {{ !$f ? 'selected' : '' }}>No</option>
                    </select>
                </div>
                <div class="col-12 mt-2">
                    <button class="btn btn-success">Save App Settings</button>
                </div>
            </form>

            <hr>
            <h5 class="mb-3">Notification Settings</h5>
            <form method="POST" action="{{ route('admin.settings.app.update') }}" class="row g-2">
                @csrf
                <div class="col-md-3">
                    <label class="form-label">Enable Birthday Wishes</label>
                    @php($be = \App\Models\Setting::where('key','notifications.birthday.enabled')->value('value'))
                    <select class="form-select" name="notifications.birthday.enabled">
                        <option value="1" {{ $be ? 'selected' : '' }}>Yes</option>
                        <option value="0" {{ !$be ? 'selected' : '' }}>No</option>
                    </select>
                </div>
                <div class="col-md-9">
                    <label class="form-label">Birthday Template</label>
                    @php($bt = \App\Models\Setting::where('key','notifications.birthday.template')->value('value'))
                    <input class="form-control" name="notifications.birthday.template" value="{{ $bt ?? 'Happy Birthday, {name}! Wishing you a wonderful year ahead!' }}" />
                    <small class="text-muted">Variables: {name}</small>
                </div>
                <div class="col-12 mt-2">
                    <button class="btn btn-success">Save Notification Settings</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection


