@extends('layouts.app')

@section('content')
<div class="container py-4">
    <h2 class="mb-4">Attendance Settings</h2>
    <form id="attendance-settings-form" class="row g-3">
        <div class="col-md-4">
            <label>Default Marking Start</label>
            <input type="time" class="form-control" name="default_marking_start" id="default_marking_start">
        </div>
        <div class="col-md-4">
            <label>Default Marking End</label>
            <input type="time" class="form-control" name="default_marking_end" id="default_marking_end">
        </div>
        <div class="col-md-4">
            <label>Late Threshold</label>
            <input type="time" class="form-control" name="late_threshold" id="late_threshold">
        </div>
        <div class="col-md-4">
            <label>Minimum Attendance % (for exam eligibility)</label>
            <input type="number" class="form-control" name="min_attendance_percent" id="min_attendance_percent" min="0" max="100">
        </div>
        <div class="col-md-4">
            <label>Allow Backdated Entries</label>
            <select class="form-control" name="allow_backdated_entries" id="allow_backdated_entries">
                <option value="0">No</option>
                <option value="1">Yes</option>
            </select>
        </div>
        <div class="col-md-4">
            <label>Teacher Can Backdate</label>
            <select class="form-control" name="teacher_can_backdate" id="teacher_can_backdate">
                <option value="0">No</option>
                <option value="1">Yes</option>
            </select>
        </div>
        <div class="col-12 mt-4">
            <h5>Notification Preferences</h5>
        </div>
        <div class="col-md-3">
            <div class="form-check">
                <input class="form-check-input" type="checkbox" name="notify_absent" id="notify_absent" value="1">
                <label class="form-check-label" for="notify_absent">Notify on Absence</label>
            </div>
        </div>
        <div class="col-md-3">
            <div class="form-check">
                <input class="form-check-input" type="checkbox" name="notify_late" id="notify_late" value="1">
                <label class="form-check-label" for="notify_late">Notify on Late Arrival</label>
            </div>
        </div>
        <div class="col-md-3">
            <label>Notification Channel</label>
            <select class="form-control" name="notify_channel" id="notify_channel">
                <option value="email">Email</option>
                <option value="sms">SMS</option>
                <option value="both">Both</option>
            </select>
        </div>
        <div class="col-md-3">
            <label>Chronic Absentee Threshold (days)</label>
            <input type="number" class="form-control" name="chronic_absent_threshold" id="chronic_absent_threshold" min="1">
        </div>
        <div class="col-12">
            <label>Custom Notification Message</label>
            <textarea class="form-control" name="custom_message" id="custom_message" rows="3" placeholder="Optional custom message for notifications"></textarea>
        </div>
        <div class="col-12 mt-3">
            <button type="submit" class="btn btn-primary">Save Settings</button>
        </div>
    </form>
</div>
<div class="card mt-4">
    <div class="card-header">Notification Settings</div>
    <div class="card-body">
        <form id="notification-settings-form">
            <div class="mb-3">
                <label>Enable SMS Notifications</label>
                <input type="checkbox" id="enable-sms" checked>
            </div>
            <div class="mb-3">
                <label>X-days-absent Alert Threshold</label>
                <input type="number" class="form-control" id="x-days-threshold" min="2" value="3">
            </div>
            <div class="mb-3">
                <label>X-days-absent Alert Message</label>
                <input type="text" class="form-control" id="x-days-alert-message" value="Your child has been absent for multiple days. Please contact the school.">
            </div>
            <button type="submit" class="btn btn-primary">Save Notification Settings</button>
        </form>
    </div>
</div>
<div class="card mt-4">
    <div class="card-header">Advanced Attendance Features</div>
    <div class="card-body">
        <form id="advanced-features-form">
            <div class="mb-2">
                <input type="checkbox" id="enable-biometric"> <label for="enable-biometric">Enable Biometric Attendance</label>
                <a href="#" class="ms-2">View Biometric Logs</a>
            </div>
            <div class="mb-2">
                <input type="checkbox" id="enable-qr"> <label for="enable-qr">Enable QR Code Attendance</label>
                <a href="#" class="ms-2">View QR Logs</a>
            </div>
            <div class="mb-2">
                <input type="checkbox" id="enable-face"> <label for="enable-face">Enable Face Recognition Attendance</label>
                <a href="#" class="ms-2">View Face Logs</a>
            </div>
            <div class="mb-2">
                <input type="checkbox" id="enable-parent-ack"> <label for="enable-parent-ack">Enable Parent Acknowledgment</label>
                <a href="#" class="ms-2">View Acknowledgments</a>
            </div>
            <button type="submit" class="btn btn-primary mt-2">Save Advanced Settings</button>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
// Load current settings via AJAX
fetch('/api/v1/attendance/settings')
    .then(res => res.json())
    .then(settings => {
        if (!settings) return;
        document.getElementById('default_marking_start').value = settings.default_marking_start || '';
        document.getElementById('default_marking_end').value = settings.default_marking_end || '';
        document.getElementById('late_threshold').value = settings.late_threshold || '';
        document.getElementById('min_attendance_percent').value = settings.min_attendance_percent || '';
        document.getElementById('allow_backdated_entries').value = settings.allow_backdated_entries ? '1' : '0';
        document.getElementById('teacher_can_backdate').value = settings.teacher_can_backdate ? '1' : '0';
        document.getElementById('notify_absent').checked = !!settings.notify_absent;
        document.getElementById('notify_late').checked = !!settings.notify_late;
        document.getElementById('notify_channel').value = settings.notify_channel || 'email';
        document.getElementById('chronic_absent_threshold').value = settings.chronic_absent_threshold || '';
        document.getElementById('custom_message').value = settings.custom_message || '';
    });

document.getElementById('attendance-settings-form').addEventListener('submit', function(e) {
    e.preventDefault();
    const form = e.target;
    const formData = new FormData(form);
    // Checkbox fix
    if (!form.notify_absent.checked) formData.set('notify_absent', 0);
    if (!form.notify_late.checked) formData.set('notify_late', 0);
    fetch('/api/v1/attendance/settings', {
        method: 'POST',
        headers: {
            'Accept': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
        },
        body: formData
    })
    .then(res => res.json())
    .then(data => {
        if (data.success) {
            alert('Settings saved successfully!');
        } else {
            alert('Error: ' + (data.message || 'Could not save settings.'));
        }
    })
    .catch(() => alert('An error occurred while saving settings.'));
});

document.getElementById('notification-settings-form').addEventListener('submit', function(e) {
    e.preventDefault();
    alert('Settings saved (demo only, connect to backend to persist).');
});

document.getElementById('advanced-features-form').addEventListener('submit', function(e) {
    e.preventDefault();
    alert('Advanced settings saved (demo only, connect to backend to persist).');
});
</script>
@endpush 