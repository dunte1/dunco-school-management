@extends('portal::components.layouts.master')

@section('content')
<div class="container-fluid py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h3 class="fw-bold mb-0"><i class="fas fa-sliders-h me-2"></i>Preferences</h3>
        @if(Auth::check() && Auth::user()->hasRole('parent'))
        <form method="GET" action="{{ route('portal.preferences') }}" class="d-flex align-items-center gap-2">
            <label for="student_id" class="fw-semibold me-2">Viewing for:</label>
            <select name="student_id" id="student_id" class="form-select w-auto" onchange="this.form.submit()">
                @foreach($all_students as $child)
                    <option value="{{ $child->id }}" @if(request('student_id', $child->id) == $child->id) selected @endif>{{ $child->name }}</option>
                @endforeach
            </select>
        </form>
        @endif
    </div>

    <div class="row g-4">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0"><i class="fas fa-sliders-h me-2"></i>User Preferences</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <h6>Notification Preferences</h6>
                            <p class="text-muted">Manage your notification settings.</p>
                            <a href="{{ route('portal.notification-settings') }}" class="btn btn-primary">
                                <i class="fas fa-bell me-2"></i>Notification Settings
                            </a>
                        </div>
                        <div class="col-md-6">
                            <h6>Display Preferences</h6>
                            <p class="text-muted">Customize your display and theme preferences.</p>
                            <a href="{{ route('portal.display-settings') }}" class="btn btn-secondary">
                                <i class="fas fa-palette me-2"></i>Display Settings
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
