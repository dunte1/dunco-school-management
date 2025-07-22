@extends('layouts.app')
@section('content')
<div class="container py-4">
    <h2 class="mb-4">Parent Acknowledgment Logs</h2>
    <form id="ack-filter-form" class="row g-3 mb-4">
        <div class="col-md-3">
            <label>Attendance Record ID</label>
            <input type="text" class="form-control" id="filter-record">
        </div>
        <div class="col-md-3">
            <label>Parent ID</label>
            <input type="text" class="form-control" id="filter-parent">
        </div>
        <div class="col-md-3">
            <label>Date</label>
            <input type="date" class="form-control" id="filter-date">
        </div>
        <div class="col-md-3 d-flex align-items-end">
            <button type="submit" class="btn btn-primary w-100">Search</button>
        </div>
    </form>
    <div id="ack-logs-list"></div>
</div>
@endsection
@push('scripts')
<script>
function fetchAckLogs() {
    const params = new URLSearchParams({
        attendance_record_id: document.getElementById('filter-record').value,
        parent_id: document.getElementById('filter-parent').value,
        date: document.getElementById('filter-date').value
    });
    fetch(`/api/attendance/acknowledgment-logs?${params}`)
        .then(res => res.json())
        .then(data => renderAckLogs(data.data));
}
function renderAckLogs(logs) {
    const list = document.getElementById('ack-logs-list');
    if (!logs.length) {
        list.innerHTML = '<div class="alert alert-info">No logs found.</div>';
        return;
    }
    let html = '<table class="table table-bordered"><thead><tr><th>Attendance Record ID</th><th>Parent ID</th><th>Acknowledged At</th><th>Channel</th></tr></thead><tbody>';
    logs.forEach(l => {
        html += `<tr><td>${l.attendance_record_id}</td><td>${l.parent_id}</td><td>${l.acknowledged_at}</td><td>${l.channel}</td></tr>`;
    });
    html += '</tbody></table>';
    list.innerHTML = html;
}
document.getElementById('ack-filter-form').addEventListener('submit', function(e) {
    e.preventDefault();
    fetchAckLogs();
});
document.addEventListener('DOMContentLoaded', fetchAckLogs);
</script>
@endpush 