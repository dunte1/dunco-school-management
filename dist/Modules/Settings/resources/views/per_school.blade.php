@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Per-School Settings</h1>
    <div id="ajax-alert" class="alert" style="display: none;"></div>
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
    <form id="per-school-settings-form" action="{{ route('settings.per_school.update') }}" method="POST">
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
        <div class="d-flex gap-2">
            <button type="submit" class="btn btn-success">Save Settings</button>
            <button type="button" id="save-ajax" class="btn btn-primary">Save with AJAX</button>
        </div>
    </form>
</div>

<script src="{{ asset('js/settings-utils.js') }}"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('per-school-settings-form');
    const ajaxButton = document.getElementById('save-ajax');
    const ajaxAlert = document.getElementById('ajax-alert');
    
    // Show alert function
    function showAlert(message, type) {
        ajaxAlert.textContent = message;
        ajaxAlert.className = `alert alert-${type}`;
        ajaxAlert.style.display = 'block';
        
        // Auto-hide after 5 seconds
        setTimeout(() => {
            ajaxAlert.style.display = 'none';
        }, 5000);
    }
    
    // AJAX save function
    ajaxButton.addEventListener('click', function() {
        const formData = new FormData(form);
        
        // Show loading state
        ajaxButton.disabled = true;
        ajaxButton.textContent = 'Saving...';
        
        fetch('{{ route("settings.per_school.ajax") }}', {
            method: 'POST',
            body: formData,
            headers: {
                'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                showAlert(data.message, 'success');
                // Update form values with returned settings
                Object.keys(data.settings).forEach(key => {
                    const input = document.querySelector(`[name="${key}"]`);
                    if (input) {
                        if (input.type === 'select-one') {
                            input.value = data.settings[key];
                        } else {
                            input.value = data.settings[key];
                        }
                    }
                });
            } else {
                showAlert(data.message, 'danger');
            }
        })
        .catch(error => {
            showAlert('Error saving settings: ' + error.message, 'danger');
        })
        .finally(() => {
            ajaxButton.disabled = false;
            ajaxButton.textContent = 'Save with AJAX';
        });
    });
    
    // Auto-save on input change (debounced)
    let autoSaveTimeout;
    const inputs = form.querySelectorAll('input, select, textarea');
    
    inputs.forEach(input => {
        input.addEventListener('change', function() {
            clearTimeout(autoSaveTimeout);
            autoSaveTimeout = setTimeout(() => {
                // Only auto-save if AJAX button is available
                if (ajaxButton && !ajaxButton.disabled) {
                    ajaxButton.click();
                }
            }, 2000); // 2 second delay
        });
    });
});
</script>
@endsection 