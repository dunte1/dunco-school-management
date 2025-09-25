@extends('layouts.app')
@section('content')
<div class="container py-4">
    <h2 class="mb-4">QR Code Attendance Logs</h2>
    <form id="qr-filter-form" class="row g-3 mb-4">
        <div class="col-md-3">
            <label>Student ID</label>
            <input type="text" class="form-control" id="filter-student">
        </div>
        <div class="col-md-3">
            <label>Session ID</label>
            <input type="text" class="form-control" id="filter-session">
        </div>
        <div class="col-md-3">
            <label>Date</label>
            <input type="date" class="form-control" id="filter-date">
        </div>
        <div class="col-md-3 d-flex align-items-end">
            <button type="submit" class="btn btn-primary w-100">Search</button>
        </div>
    </form>
    <div id="qr-logs-list"></div>
</div>
@endsection
@push('scripts')
<script>
function fetchQrLogs() {
    const params = new URLSearchParams({
        student_id: document.getElementById('filter-student').value,
        session_id: document.getElementById('filter-session').value,
        date: document.getElementById('filter-date').value
    });
    fetch(`/api/attendance/qr-logs?${params}`)
        .then(res => res.json())
        .then(data => renderQrLogs(data.data));
}
function renderQrLogs(logs) {
    const list = document.getElementById('qr-logs-list');
    if (!logs.length) {
        list.innerHTML = '<div class="alert alert-info">No logs found.</div>';
        return;
    }
    let html = '<table class="table table-bordered"><thead><tr><th>Student ID</th><th>Session ID</th><th>Scanned At</th><th>Status</th></tr></thead><tbody>';
    logs.forEach(l => {
        html += `<tr><td>${l.student_id}</td><td>${l.session_id}</td><td>${l.scanned_at}</td><td>${l.status}</td></tr>`;
    });
    html += '</tbody></table>';
    list.innerHTML = html;
}
document.getElementById('qr-filter-form').addEventListener('submit', function(e) {
    e.preventDefault();
    fetchQrLogs();
});
document.addEventListener('DOMContentLoaded', fetchQrLogs);
</script>
@endpush 