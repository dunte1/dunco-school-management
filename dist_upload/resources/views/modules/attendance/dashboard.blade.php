@extends('layouts.app')

@section('content')
<div class="container py-4">
    <h2 class="mb-4">Attendance Dashboard</h2>
    <div class="row mb-3">
        <div class="col-md-3">
            <label>Date</label>
            <input type="date" id="filter-date" class="form-control" value="{{ date('Y-m-d') }}">
        </div>
        <div class="col-md-3">
            <label>Class</label>
            <input type="text" id="filter-class" class="form-control" placeholder="Class ID">
        </div>
        <div class="col-md-3">
            <label>Department</label>
            <input type="text" id="filter-department" class="form-control" placeholder="Department ID">
        </div>
        <div class="col-md-3">
            <label>Teacher</label>
            <input type="text" id="filter-teacher" class="form-control" placeholder="Teacher ID">
        </div>
    </div>
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card text-center shadow">
                <div class="card-body">
                    <h5 class="card-title">Total Students</h5>
                    <h2 id="stat-total-students">-</h2>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-center shadow">
                <div class="card-body">
                    <h5 class="card-title">Present Today</h5>
                    <h2 id="stat-present-today">-</h2>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-center shadow">
                <div class="card-body">
                    <h5 class="card-title">Absent Today</h5>
                    <h2 id="stat-absent-today">-</h2>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-center shadow">
                <div class="card-body">
                    <h5 class="card-title">Late Arrivals</h5>
                    <h2 id="stat-late-arrivals">-</h2>
                </div>
            </div>
        </div>
    </div>
    <div class="row mb-4" id="staff-summary-row">
        <div class="col-md-12"><h5>Staff Attendance Summary (Today)</h5></div>
        <div class="col-md-2"><div class="card text-center shadow"><div class="card-body"><h6>Present</h6><h3 id="staff-present">-</h3></div></div></div>
        <div class="col-md-2"><div class="card text-center shadow"><div class="card-body"><h6>Absent</h6><h3 id="staff-absent">-</h3></div></div></div>
        <div class="col-md-2"><div class="card text-center shadow"><div class="card-body"><h6>Late</h6><h3 id="staff-late">-</h3></div></div></div>
        <div class="col-md-2"><div class="card text-center shadow"><div class="card-body"><h6>On Leave</h6><h3 id="staff-on-leave">-</h3></div></div></div>
        <div class="col-md-2"><div class="card text-center shadow"><div class="card-body"><h6>Sick</h6><h3 id="staff-sick">-</h3></div></div></div>
        <div class="col-md-2"><div class="card text-center shadow"><div class="card-body"><h6>Excused</h6><h3 id="staff-excused">-</h3></div></div></div>
        <div class="col-md-2 mt-2"><div class="card text-center shadow"><div class="card-body"><h6>Suspended</h6><h3 id="staff-suspended">-</h3></div></div></div>
    </div>
    <div class="mb-4">
        <button class="btn btn-primary me-2" id="btn-mark-attendance">Mark Attendance</button>
        <button class="btn btn-success me-2" id="btn-generate-report">Generate Report</button>
        <button class="btn btn-warning" id="btn-view-defaulters">View Defaulters</button>
    </div>
    <div class="card mt-4 mb-4">
        <div class="card-header">Attendance Trend</div>
        <div class="card-body">
            <canvas id="attendanceTrendChart" height="80"></canvas>
        </div>
    </div>
    <div class="card mt-4">
        <div class="card-header">Defaulters</div>
        <div class="card-body">
            <ul id="defaulters-list" class="list-group"></ul>
        </div>
    </div>
    <div class="card mt-4">
        <div class="card-header">Staff Attendance Trend</div>
        <div class="card-body">
            <canvas id="staffAttendanceTrendChart" height="80"></canvas>
        </div>
    </div>
    <div class="card mt-4">
        <div class="card-header">Student Attendance Analytics (Monthly)</div>
        <div class="card-body">
            <canvas id="studentAttendanceAnalyticsChart" height="80"></canvas>
        </div>
    </div>
    <div class="card mt-4">
        <div class="card-header">Staff Attendance Analytics (Monthly)</div>
        <div class="card-body">
            <canvas id="staffAttendanceAnalyticsChart" height="80"></canvas>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
function fetchDashboardStats() {
    const date = document.getElementById('filter-date').value;
    const classId = document.getElementById('filter-class').value;
    const departmentId = document.getElementById('filter-department').value;
    const teacherId = document.getElementById('filter-teacher').value;
    fetch(`/api/v1/attendance/dashboard/stats?date=${date}&class_id=${classId}&department_id=${departmentId}&teacher_id=${teacherId}`)
        .then(res => res.json())
        .then(data => {
            document.getElementById('stat-total-students').textContent = data.total_students;
            document.getElementById('stat-present-today').textContent = data.present_today;
            document.getElementById('stat-absent-today').textContent = data.absent_today;
            document.getElementById('stat-late-arrivals').textContent = data.late_arrivals;
            // Defaulters
            const defaultersList = document.getElementById('defaulters-list');
            defaultersList.innerHTML = '';
            if (data.defaulters && data.defaulters.length > 0) {
                data.defaulters.forEach(d => {
                    const li = document.createElement('li');
                    li.className = 'list-group-item';
                    li.textContent = d.name || d.student_id || JSON.stringify(d);
                    defaultersList.appendChild(li);
                });
            } else {
                const li = document.createElement('li');
                li.className = 'list-group-item text-muted';
                li.textContent = 'No defaulters.';
                defaultersList.appendChild(li);
            }
        });
}

// Attendance Trend Chart (fetch real data)
const ctx = document.getElementById('attendanceTrendChart').getContext('2d');
let attendanceTrendChart;
function renderTrendChart(data) {
    if (attendanceTrendChart) attendanceTrendChart.destroy();
    attendanceTrendChart = new Chart(ctx, {
        type: 'line',
        data: {
            labels: data.labels,
            datasets: data.datasets
        },
        options: {
            responsive: true,
            plugins: {
                legend: { position: 'top' },
                title: { display: true, text: 'Attendance Trend (Last 7 Days)' }
            }
        }
    });
}
function fetchTrendData() {
    fetch('/api/v1/attendance/dashboard/trend')
        .then(res => res.json())
        .then(data => renderTrendChart(data));
}

function fetchStaffSummary() {
    fetch('/api/v1/attendance/dashboard/staff-summary')
        .then(res => res.json())
        .then(data => {
            document.getElementById('staff-present').textContent = data.present;
            document.getElementById('staff-absent').textContent = data.absent;
            document.getElementById('staff-late').textContent = data.late;
            document.getElementById('staff-on-leave').textContent = data.on_leave;
            document.getElementById('staff-sick').textContent = data.sick;
            document.getElementById('staff-excused').textContent = data.excused;
            document.getElementById('staff-suspended').textContent = data.suspended;
        });
}
let staffAttendanceTrendChart;
function renderStaffTrendChart(data) {
    const ctxStaff = document.getElementById('staffAttendanceTrendChart').getContext('2d');
    if (staffAttendanceTrendChart) staffAttendanceTrendChart.destroy();
    staffAttendanceTrendChart = new Chart(ctxStaff, {
        type: 'line',
        data: {
            labels: data.labels,
            datasets: data.datasets
        },
        options: {
            responsive: true,
            plugins: {
                legend: { position: 'top' },
                title: { display: true, text: 'Staff Attendance Trend (Last 7 Days)' }
            }
        }
    });
}
function fetchStaffTrendData() {
    fetch('/api/v1/attendance/dashboard/staff-trend')
        .then(res => res.json())
        .then(data => renderStaffTrendChart(data));
}

let studentAttendanceAnalyticsChart;
function renderStudentAnalyticsChart(data) {
    const ctx = document.getElementById('studentAttendanceAnalyticsChart').getContext('2d');
    if (studentAttendanceAnalyticsChart) studentAttendanceAnalyticsChart.destroy();
    studentAttendanceAnalyticsChart = new Chart(ctx, {
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
function fetchStudentAnalytics() {
    const classId = document.getElementById('filter-class').value;
    fetch(`/api/attendance/analytics/student?class_id=${classId}`)
        .then(res => res.json())
        .then(data => renderStudentAnalyticsChart(data));
}
let staffAttendanceAnalyticsChart;
function renderStaffAnalyticsChart(data) {
    const ctx = document.getElementById('staffAttendanceAnalyticsChart').getContext('2d');
    if (staffAttendanceAnalyticsChart) staffAttendanceAnalyticsChart.destroy();
    staffAttendanceAnalyticsChart = new Chart(ctx, {
        type: 'bar',
        data: {
            labels: data.labels,
            datasets: data.datasets
        },
        options: {
            responsive: true,
            plugins: {
                legend: { position: 'top' },
                title: { display: true, text: 'Staff Attendance Analytics (Monthly)' }
            }
        }
    });
}
function fetchStaffAnalytics() {
    const departmentId = document.getElementById('filter-department').value;
    fetch(`/api/attendance/analytics/staff?department_id=${departmentId}`)
        .then(res => res.json())
        .then(data => renderStaffAnalyticsChart(data));
}

document.addEventListener('DOMContentLoaded', function() {
    fetchDashboardStats();
    fetchTrendData();
    fetchStaffSummary();
    fetchStaffTrendData();
    fetchStudentAnalytics();
    fetchStaffAnalytics();
    document.getElementById('filter-date').addEventListener('change', fetchDashboardStats);
    document.getElementById('filter-class').addEventListener('change', fetchDashboardStats);
    document.getElementById('filter-department').addEventListener('change', fetchDashboardStats);
    document.getElementById('filter-teacher').addEventListener('change', fetchDashboardStats);
    document.getElementById('filter-class').addEventListener('change', fetchStudentAnalytics);
    document.getElementById('filter-department').addEventListener('change', fetchStaffAnalytics);
});
</script>
@endpush 