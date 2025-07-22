@extends('layouts.app')

@section('content')
<div class="container py-4">
    <h2 class="mb-4">Edit Past Attendance Records</h2>
    <form id="filter-form" class="row g-3 mb-4">
        <div class="col-md-2">
            <label>Class</label>
            <input type="text" class="form-control" id="filter-class">
        </div>
        <div class="col-md-2">
            <label>Student</label>
            <input type="text" class="form-control" id="filter-student">
        </div>
        <div class="col-md-2">
            <label>Date</label>
            <input type="date" class="form-control" id="filter-date">
        </div>
        <div class="col-md-2">
            <label>Status</label>
            <select class="form-control" id="filter-status">
                <option value="">All</option>
                <option value="present">Present</option>
                <option value="absent">Absent</option>
                <option value="late">Late</option>
                <option value="excused">Excused</option>
                <option value="sick">Sick</option>
                <option value="on_leave">On Leave</option>
                <option value="suspended">Suspended</option>
            </select>
        </div>
        <div class="col-md-2 d-flex align-items-end">
            <button type="submit" class="btn btn-primary w-100">Search</button>
        </div>
    </form>
    <div id="records-list"></div>
</div>

<!-- Edit Modal -->
<div class="modal fade" id="editModal" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <form id="edit-form">
        <div class="modal-header">
          <h5 class="modal-title" id="editModalLabel">Edit Attendance Record</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <input type="hidden" id="edit-id">
          <div class="mb-3">
            <label>Status</label>
            <select class="form-control" id="edit-status" required>
                <option value="present">Present</option>
                <option value="absent">Absent</option>
                <option value="late">Late</option>
                <option value="excused">Excused</option>
                <option value="sick">Sick</option>
                <option value="on_leave">On Leave</option>
                <option value="suspended">Suspended</option>
            </select>
          </div>
          <div class="mb-3">
            <label>Remarks</label>
            <input type="text" class="form-control" id="edit-remarks">
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
          <button type="submit" class="btn btn-primary">Save</button>
        </div>
      </form>
    </div>
  </div>
</div>

<!-- Audit Log Modal -->
<div class="modal fade" id="auditModal" tabindex="-1" aria-labelledby="auditModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="auditModalLabel">Audit Log</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <div id="audit-log-list"></div>
      </div>
    </div>
  </div>
</div>
@endsection

@push('scripts')
<script>
function fetchRecords() {
    const params = new URLSearchParams({
        class_id: document.getElementById('filter-class').value,
        student_id: document.getElementById('filter-student').value,
        date: document.getElementById('filter-date').value,
        status: document.getElementById('filter-status').value
    });
    fetch(`/api/attendance/past-records?${params}`)
        .then(res => res.json())
        .then(data => renderRecords(data.data));
}

function renderRecords(records) {
    const list = document.getElementById('records-list');
    if (!records.length) {
        list.innerHTML = '<div class="alert alert-info">No records found.</div>';
        return;
    }
    let html = '<table class="table table-bordered"><thead><tr><th>Date</th><th>Class</th><th>Student</th><th>Status</th><th>Remarks</th><th>Actions</th></tr></thead><tbody>';
    records.forEach(r => {
        html += `<tr>
            <td>${r.date}</td>
            <td>${r.class ? r.class.name : ''}</td>
            <td>${r.student ? (r.student.full_name || r.student.name) : ''}</td>
            <td>${r.status}</td>
            <td>${r.remarks || ''}</td>
            <td>
                <button class="btn btn-sm btn-info me-1" onclick="openEditModal(${r.id}, '${r.status}', '${r.remarks || ''}')">Edit</button>
                <button class="btn btn-sm btn-secondary" onclick="openAuditModal(${r.id})">Audit Log</button>
            </td>
        </tr>`;
    });
    html += '</tbody></table>';
    list.innerHTML = html;
}

document.getElementById('filter-form').addEventListener('submit', function(e) {
    e.preventDefault();
    fetchRecords();
});

document.addEventListener('DOMContentLoaded', fetchRecords);

function openEditModal(id, status, remarks) {
    document.getElementById('edit-id').value = id;
    document.getElementById('edit-status').value = status;
    document.getElementById('edit-remarks').value = remarks;
    new bootstrap.Modal(document.getElementById('editModal')).show();
}

document.getElementById('edit-form').addEventListener('submit', function(e) {
    e.preventDefault();
    const id = document.getElementById('edit-id').value;
    const data = {
        status: document.getElementById('edit-status').value,
        remarks: document.getElementById('edit-remarks').value
    };
    fetch(`/api/attendance/past-records/${id}`, {
        method: 'PUT',
        headers: { 'Content-Type': 'application/json', 'Accept': 'application/json' },
        body: JSON.stringify(data)
    })
    .then(res => res.json())
    .then(() => {
        fetchRecords();
        bootstrap.Modal.getInstance(document.getElementById('editModal')).hide();
    });
});

function openAuditModal(id) {
    fetch(`/api/attendance/past-records/${id}/audit-log`)
        .then(res => res.json())
        .then(data => renderAuditLog(data));
    new bootstrap.Modal(document.getElementById('auditModal')).show();
}

function renderAuditLog(logs) {
    const list = document.getElementById('audit-log-list');
    if (!logs.length) {
        list.innerHTML = '<div class="alert alert-info">No audit log found.</div>';
        return;
    }
    let html = '<ul class="list-group">';
    logs.forEach(log => {
        html += `<li class="list-group-item">
            <strong>${log.user ? log.user.name : 'User'}:</strong> ${log.action} <br>
            <small>${log.created_at}</small><br>
            <span class="text-muted">Before:</span> <code>${JSON.stringify(log.before)}</code><br>
            <span class="text-muted">After:</span> <code>${JSON.stringify(log.after)}</code>
        </li>`;
    });
    html += '</ul>';
    list.innerHTML = html;
}
</script>
@endpush 