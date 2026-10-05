@extends('portal::components.layouts.master')

@section('content')
<div class="container-fluid py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h3 class="fw-bold mb-0"><i class="fas fa-cog me-2"></i>Settings</h3>
        @if(Auth::check() && Auth::user()->hasRole('parent'))
        <form method="GET" action="{{ route('portal.settings') }}" class="d-flex align-items-center gap-2">
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
                    <h5 class="mb-0"><i class="fas fa-cog me-2"></i>Account Settings</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <h6>General Settings</h6>
                            <p class="text-muted">Manage your account settings and preferences.</p>
                            <a href="{{ route('portal.edit-settings') }}" class="btn btn-primary">
                                <i class="fas fa-edit me-2"></i>Edit Settings
                            </a>
                        </div>
                        <div class="col-md-6">
                            <h6>Security Settings</h6>
                            <p class="text-muted">Update your password and security preferences.</p>
                            <a href="{{ route('portal.security-settings') }}" class="btn btn-secondary">
                                <i class="fas fa-shield-alt me-2"></i>Security Settings
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
