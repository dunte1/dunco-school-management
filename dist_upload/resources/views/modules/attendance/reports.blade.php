@extends('layouts.app')

@section('content')
<div class="container py-4">
    <h2 class="mb-4">Attendance Reports</h2>
    <form id="report-filter-form" class="row g-3 mb-4">
        <div class="col-md-2">
            <label>Start Date</label>
            <input type="date" class="form-control" name="start_date" id="start_date">
        </div>
        <div class="col-md-2">
            <label>End Date</label>
            <input type="date" class="form-control" name="end_date" id="end_date">
        </div>
        <div class="col-md-2">
            <label>Class</label>
            <select class="form-control" name="class_id" id="report_class_id">
                <option value="">All Classes</option>
            </select>
        </div>
        <div class="col-md-2">
            <label>Student</label>
            <select class="form-control" name="student_id" id="report_student_id">
                <option value="">All Students</option>
            </select>
        </div>
        <div class="col-md-2">
            <label>Subject</label>
            <select class="form-control" name="subject_id" id="report_subject_id">
                <option value="">All Subjects</option>
            </select>
        </div>
        <div class="col-md-2 d-flex align-items-end">
            <button type="submit" class="btn btn-primary w-100">Get Report</button>
        </div>
    </form>
    <div class="d-flex mb-3">
        <button class="btn btn-outline-success me-2" id="export-excel">Export to Excel</button>
        <button class="btn btn-outline-danger" id="export-pdf">Export to PDF</button>
    </div>
    <div id="report-summary" class="mb-4" style="display:none;">
        <h5>Summary</h5>
        <ul class="list-group">
            <li class="list-group-item">Total Records: <span id="summary-total">-</span></li>
            <li class="list-group-item">Present: <span id="summary-present">-</span></li>
            <li class="list-group-item">Absent: <span id="summary-absent">-</span></li>
            <li class="list-group-item">Late: <span id="summary-late">-</span></li>
            <li class="list-group-item">Excused: <span id="summary-excused">-</span></li>
        </ul>
    </div>
    <div id="report-percentages" class="mb-4" style="display:none;">
        <h5>Attendance Percentages</h5>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Student</th>
                    <th>Attendance %</th>
                </tr>
            </thead>
            <tbody id="percentages-body"></tbody>
        </table>
    </div>
    <div id="report-defaulters" class="mb-4" style="display:none;">
        <h5>Defaulters (&lt; 75%)</h5>
        <ul class="list-group" id="defaulters-list"></ul>
    </div>
    <div class="card mt-4">
        <div class="card-header">Bulk Notifications</div>
        <div class="card-body">
            <form id="bulk-notify-form" class="row g-2 align-items-end">
                <div class="col-md-2">
                    <label>Criteria</label>
                    <select class="form-control" id="bulk-criteria">
                        <option value="all">All</option>
                        <option value="absent">Absent</option>
                        <option value="late">Late</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <label>Date</label>
                    <input type="date" class="form-control" id="bulk-date" value="{{ date('Y-m-d') }}">
                </div>
                <div class="col-md-3">
                    <label>Message</label>
                    <input type="text" class="form-control" id="bulk-message" placeholder="Notification message">
                </div>
                <div class="col-md-2">
                    <label>Channel</label>
                    <select class="form-control" id="bulk-channel">
                        <option value="email">Email</option>
                        <option value="sms">SMS</option>
                        <option value="both">Both</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <button type="submit" class="btn btn-primary w-100">Send Bulk Notification</button>
                </div>
            </form>
            <form id="x-days-absent-form" class="row g-2 align-items-end mt-3">
                <div class="col-md-2">
                    <label>Absent Days</label>
                    <input type="number" class="form-control" id="x-days" min="2" value="3">
                </div>
                <div class="col-md-4">
                    <label>Message</label>
                    <input type="text" class="form-control" id="x-days-message" placeholder="Alert message">
                </div>
                <div class="col-md-2">
                    <label>Channel</label>
                    <select class="form-control" id="x-days-channel">
                        <option value="email">Email</option>
                        <option value="sms">SMS</option>
                        <option value="both">Both</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <button type="submit" class="btn btn-warning w-100">Send X-days-absent Alerts</button>
                </div>
            </form>
        </div>
    </div>
    <div class="card mt-4">
        <div class="card-header">Student Attendance Analytics (Monthly)</div>
        <div class="card-body">
            <canvas id="reportStudentAttendanceAnalyticsChart" height="80"></canvas>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
// Load classes and students for filters
fetch('/api/v1/attendance/classes')
    .then(res => res.json())
    .then(classes => {
        const classSelect = document.getElementById('report_class_id');
        classes.forEach(cls => {
            const opt = document.createElement('option');
            opt.value = cls.id;
            opt.textContent = cls.name;
            classSelect.appendChild(opt);
        });
    });

function loadStudentsForClass(classId) {
    fetch(`/api/v1/attendance/students?class_id=${classId}`)
        .then(res => res.json())
        .then(students => {
            const studentSelect = document.getElementById('report_student_id');
            studentSelect.innerHTML = '<option value="">All Students</option>';
            students.forEach(stu => {
                const opt = document.createElement('option');
                opt.value = stu.id;
                opt.textContent = stu.first_name + ' ' + stu.last_name;
                studentSelect.appendChild(opt);
            });
        });
}

document.getElementById('report_class_id').addEventListener('change', function() {
    loadStudentsForClass(this.value);
});

// Load subjects for filter
fetch('/api/v1/attendance/subjects')
    .then(res => res.json())
    .then(subjects => {
        const subjectSelect = document.getElementById('report_subject_id');
        subjects.forEach(sub => {
            const opt = document.createElement('option');
            opt.value = sub.id;
            opt.textContent = sub.name;
            subjectSelect.appendChild(opt);
        });
    });

document.getElementById('report-filter-form').addEventListener('submit', function(e) {
    e.preventDefault();
    const form = e.target;
    const params = new URLSearchParams(new FormData(form)).toString();
    fetch(`/api/v1/attendance/report?${params}`)
        .then(res => res.json())
        .then(data => {
            // Summary
            document.getElementById('report-summary').style.display = '';
            document.getElementById('summary-total').textContent = data.summary.total;
            document.getElementById('summary-present').textContent = data.summary.present;
            document.getElementById('summary-absent').textContent = data.summary.absent;
            document.getElementById('summary-late').textContent = data.summary.late;
            document.getElementById('summary-excused').textContent = data.summary.excused;
            // Percentages
            document.getElementById('report-percentages').style.display = '';
            const percentagesBody = document.getElementById('percentages-body');
            percentagesBody.innerHTML = '';
            data.percentages.forEach(row => {
                const tr = document.createElement('tr');
                tr.innerHTML = `<td>${row.name}</td><td>${row.attendance_percent}%</td>`;
                percentagesBody.appendChild(tr);
            });
            // Defaulters
            document.getElementById('report-defaulters').style.display = '';
            const defaultersList = document.getElementById('defaulters-list');
            defaultersList.innerHTML = '';
            if (data.defaulters.length > 0) {
                data.defaulters.forEach(d => {
                    const li = document.createElement('li');
                    li.className = 'list-group-item';
                    li.textContent = `${d.name} (${d.attendance_percent}%)`;
                    defaultersList.appendChild(li);
                });
            } else {
                const li = document.createElement('li');
                li.className = 'list-group-item text-muted';
                li.textContent = 'No defaulters.';
                defaultersList.appendChild(li);
            }
        });
});

document.getElementById('export-excel').addEventListener('click', function() {
    const params = new URLSearchParams(new FormData(document.getElementById('report-filter-form'))).toString();
    window.open(`/api/v1/attendance/report/export/excel?${params}`, '_blank');
});
document.getElementById('export-pdf').addEventListener('click', function() {
    const params = new URLSearchParams(new FormData(document.getElementById('report-filter-form'))).toString();
    window.open(`/api/v1/attendance/report/export/pdf?${params}`, '_blank');
});

document.getElementById('bulk-notify-form').addEventListener('submit', function(e) {
    e.preventDefault();
    fetch('/api/attendance/notifications/bulk', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json', 'Accept': 'application/json' },
        body: JSON.stringify({
            criteria: document.getElementById('bulk-criteria').value,
            date: document.getElementById('bulk-date').value,
            message: document.getElementById('bulk-message').value,
            channel: document.getElementById('bulk-channel').value
        })
    })
    .then(res => res.json())
    .then(data => alert(data.message || 'Bulk notifications sent.'));
});
document.getElementById('x-days-absent-form').addEventListener('submit', function(e) {
    e.preventDefault();
    fetch('/api/attendance/notifications/x-days-absent', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json', 'Accept': 'application/json' },
        body: JSON.stringify({
            days: document.getElementById('x-days').value,
            message: document.getElementById('x-days-message').value,
            channel: document.getElementById('x-days-channel').value
        })
    })
    .then(res => res.json())
    .then(data => alert(data.message || 'X-days-absent alerts sent.'));
});

let reportStudentAttendanceAnalyticsChart;
function renderReportStudentAnalyticsChart(data) {
    const ctx = document.getElementById('reportStudentAttendanceAnalyticsChart').getContext('2d');
    if (reportStudentAttendanceAnalyticsChart) reportStudentAttendanceAnalyticsChart.destroy();
    reportStudentAttendanceAnalyticsChart = new Chart(ctx, {
        type: 'bar',
        data: {
            labels: data.labels,
            datasets: data.datasets
        },
        options: {
            responsive: true,
            plugins: {
                legend: { position: 'top' },
                title: { display: true, text: 'Student Attendance Analytics (Monthly)' }
            }
        }
    });
}
function fetchReportStudentAnalytics() {
    const classId = document.getElementById('report_class_id').value;
    fetch(`/api/attendance/analytics/student?class_id=${classId}`)
        .then(res => res.json())
        .then(data => renderReportStudentAnalyticsChart(data));
}
document.getElementById('report_class_id').addEventListener('change', fetchReportStudentAnalytics);
document.addEventListener('DOMContentLoaded', fetchReportStudentAnalytics);
</script>
@endpush 