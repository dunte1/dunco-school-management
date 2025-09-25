@extends('layouts.app')
@section('content')
<div class="container py-4">
    <h2 class="mb-4">Face Recognition Attendance Logs</h2>
    <form id="face-filter-form" class="row g-3 mb-4">
        <div class="col-md-3">
            <label>Student ID</label>
            <input type="text" class="form-control" id="filter-student">
        </div>
        <div class="col-md-3">
            <label>Image Hash</label>
            <input type="text" class="form-control" id="filter-image">
        </div>
        <div class="col-md-3">
            <label>Date</label>
            <input type="date" class="form-control" id="filter-date">
        </div>
        <div class="col-md-3 d-flex align-items-end">
            <button type="submit" class="btn btn-primary w-100">Search</button>
        </div>
    </form>
    <div id="face-logs-list"></div>
</div>
@endsection
@push('scripts')
<script>
function fetchFaceLogs() {
    const params = new URLSearchParams({
        student_id: document.getElementById('filter-student').value,
        image_hash: document.getElementById('filter-image').value,
        date: document.getElementById('filter-date').value
    });
    fetch(`/api/attendance/face-logs?${params}`)
        .then(res => res.json())
        .then(data => renderFaceLogs(data.data));
}
function renderFaceLogs(logs) {
    const list = document.getElementById('face-logs-list');
    if (!logs || !logs.length) {
        list.innerHTML = '<div class="alert alert-info">No logs found.</div>';
        return;
    }
    let html = '<table class="table table-bordered"><thead><tr><th>Student ID</th><th>Image Hash</th><th>Scanned At</th><th>Status</th></tr></thead><tbody>';
    logs.forEach(l => {
        html += `<tr><td>${l.student_id}</td><td>${l.image_hash}</td><td>${l.scanned_at}</td><td>${l.status}</td></tr>`;
    });
    html += '</tbody></table>';
    list.innerHTML = html;
}
document.getElementById('face-filter-form').addEventListener('submit', function(e) {
    e.preventDefault();
    fetchFaceLogs();
});
document.addEventListener('DOMContentLoaded', fetchFaceLogs);
</script>
@endpush 