@extends('layouts.app')
@section('content')
<div class="container py-4">
    <h2 class="mb-4">Biometric Attendance Logs</h2>
    <form id="biometric-filter-form" class="row g-3 mb-4">
        <div class="col-md-3">
            <label>Student ID</label>
            <input type="text" class="form-control" id="filter-student">
        </div>
        <div class="col-md-3">
            <label>Device ID</label>
            <input type="text" class="form-control" id="filter-device">
        </div>
        <div class="col-md-3">
            <label>Date</label>
            <input type="date" class="form-control" id="filter-date">
        </div>
        <div class="col-md-3 d-flex align-items-end">
            <button type="submit" class="btn btn-primary w-100">Search</button>
        </div>
    </form>
    <div id="biometric-logs-list"></div>
</div>
@endsection
@push('scripts')
<script>
function fetchBiometricLogs() {
    const params = new URLSearchParams({
        student_id: document.getElementById('filter-student').value,
        device_id: document.getElementById('filter-device').value,
        date: document.getElementById('filter-date').value
    });
    fetch(`/api/attendance/biometric-logs?${params}`)
        .then(res => res.json())
        .then(data => renderBiometricLogs(data.data));
}
function renderBiometricLogs(logs) {
    const list = document.getElementById('biometric-logs-list');
    if (!logs || !logs.length) {
        list.innerHTML = '<div class="alert alert-info">No logs found.</div>';
        return;
    }
    let html = '<table class="table table-bordered"><thead><tr><th>Student ID</th><th>Device ID</th><th>Scanned At</th><th>Status</th><th>Raw Data</th></tr></thead><tbody>';
    logs.forEach(l => {
        html += `<tr><td>${l.student_id}</td><td>${l.device_id}</td><td>${l.scanned_at}</td><td>${l.status}</td><td><code>${l.raw_data || ''}</code></td></tr>`;
    });
    html += '</tbody></table>';
    list.innerHTML = html;
}
document.getElementById('biometric-filter-form').addEventListener('submit', function(e) {
    e.preventDefault();
    fetchBiometricLogs();
});
document.addEventListener('DOMContentLoaded', fetchBiometricLogs);
</script>
@endpush 