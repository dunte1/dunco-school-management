@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Per-School Settings</h1>
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
    <form action="{{ route('settings.per_school') }}" method="GET" class="mb-4">
        <div class="row g-2 align-items-end">
            <div class="col-auto">
                <label for="school_id" class="form-label">Select School</label>
                <select name="school_id" id="school_id" class="form-control" onchange="this.form.submit()">
                    @foreach($schools as $school)
                        <option value="{{ $school->id }}" {{ $schoolId == $school->id ? 'selected' : '' }}>{{ $school->name }}</option>
                    @endforeach
                </select>
            </div>
        </div>
    </form>
    <form action="{{ route('settings.per_school.update') }}" method="POST">
        @csrf
        <input type="hidden" name="school_id" value="{{ $schoolId }}">
        <div class="mb-3">
            <label for="grading_system" class="form-label">Grading System</label>
            <input type="text" name="grading_system" id="grading_system" class="form-control" value="{{ old('grading_system', $settings['grading_system'] ?? '') }}">
        </div>
        <div class="mb-3">
            <label for="attendance_type" class="form-label">Attendance Type</label>
            <select name="attendance_type" id="attendance_type" class="form-control">
                @foreach($attendanceTypes as $key => $label)
                    <option value="{{ $key }}" {{ (old('attendance_type', $settings['attendance_type'] ?? '') == $key) ? 'selected' : '' }}>{{ $label }}</option>
                @endforeach
            </select>
        </div>
        <div class="mb-3">
            <label for="term_start" class="form-label">Term/Session Start Date</label>
            <input type="date" name="term_start" id="term_start" class="form-control" value="{{ old('term_start', $settings['term_start'] ?? '') }}">
        </div>
        <div class="mb-3">
            <label for="term_end" class="form-label">Term/Session End Date</label>
            <input type="date" name="term_end" id="term_end" class="form-control" value="{{ old('term_end', $settings['term_end'] ?? '') }}">
        </div>
        <div class="mb-3">
            <label for="default_currency" class="form-label">Default Currency</label>
            <select name="default_currency" id="default_currency" class="form-control">
                @foreach($currencies as $code => $label)
                    <option value="{{ $code }}" {{ (old('default_currency', $settings['default_currency'] ?? '') == $code) ? 'selected' : '' }}>{{ $label }}</option>
                @endforeach
            </select>
        </div>
        <div class="mb-3">
            <label for="fee_structure" class="form-label">Fee Structure Config</label>
            <textarea name="fee_structure" id="fee_structure" class="form-control" rows="2">{{ old('fee_structure', $settings['fee_structure'] ?? '') }}</textarea>
        </div>
        <div class="mb-3">
            <label for="school_notice" class="form-label">School-Specific Notices</label>
            <textarea name="school_notice" id="school_notice" class="form-control" rows="2">{{ old('school_notice', $settings['school_notice'] ?? '') }}</textarea>
        </div>
        <button type="submit" class="btn btn-success">Save Settings</button>
    </form>
</div>
@endsection 