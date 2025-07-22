@extends('layouts.app')

@section('content')
<div class="container py-4">
    <h2 class="mb-4">Mark Attendance</h2>
    <form id="attendance-form">
        <div class="row mb-3">
            <div class="col-md-3">
                <label>Class</label>
                <select class="form-control" name="class_id" id="class_id">
                    <option value="">Select Class</option>
                    <!-- TODO: Populate with classes -->
                </select>
            </div>
            <div class="col-md-3">
                <label>Subject</label>
                <select class="form-control" name="subject_id" id="subject_id">
                    <option value="">Select Subject</option>
                    <!-- TODO: Populate with subjects -->
                </select>
            </div>
            <div class="col-md-3">
                <label>Date</label>
                <input type="date" class="form-control" name="date" id="date" value="{{ date('Y-m-d') }}">
            </div>
            <div class="col-md-3">
                <label>Session/Period</label>
                <select class="form-control" name="session_id" id="session_id">
                    <option value="">Select Session</option>
                    <!-- TODO: Populate with sessions -->
                </select>
            </div>
        </div>
        <div class="mb-3">
            <button type="button" class="btn btn-success" id="mark-all-present">Mark All Present</button>
        </div>
        <div class="table-responsive mb-3">
            <table class="table table-bordered align-middle">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Student Name</th>
                        <th>Status</th>
                        <th>Remarks</th>
                    </tr>
                </thead>
                <tbody id="students-table-body">
                    <!-- TODO: Populate with students -->
                    <tr>
                        <td>1</td>
                        <td>John Doe</td>
                        <td>
                            <select name="status[1]" class="form-control status-select">
                                <option value="present">Present</option>
                                <option value="absent">Absent</option>
                                <option value="late">Late</option>
                                <option value="excused">Excused</option>
                            </select>
                        </td>
                        <td><input type="text" name="remarks[1]" class="form-control"></td>
                    </tr>
                    <tr>
                        <td>2</td>
                        <td>Jane Smith</td>
                        <td>
                            <select name="status[2]" class="form-control status-select">
                                <option value="present">Present</option>
                                <option value="absent">Absent</option>
                                <option value="late">Late</option>
                                <option value="excused">Excused</option>
                            </select>
                        </td>
                        <td><input type="text" name="remarks[2]" class="form-control"></td>
                    </tr>
                    <!-- Add more sample rows or load dynamically -->
                </tbody>
            </table>
        </div>
        <div class="mb-3">
            <button type="submit" class="btn btn-primary">Submit Attendance</button>
        </div>
    </form>
</div>
@endsection

@push('scripts')
<script>
// Load classes on page load
fetch('/api/v1/attendance/classes')
    .then(res => res.json())
    .then(classes => {
        const classSelect = document.getElementById('class_id');
        classes.forEach(cls => {
            const opt = document.createElement('option');
            opt.value = cls.id;
            opt.textContent = cls.name;
            classSelect.appendChild(opt);
        });
    });

// Load subjects and students when class changes
function loadSubjectsAndStudents() {
    const classId = document.getElementById('class_id').value;
    // Subjects
    fetch(`/api/v1/attendance/subjects?class_id=${classId}`)
        .then(res => res.json())
        .then(subjects => {
            const subjectSelect = document.getElementById('subject_id');
            subjectSelect.innerHTML = '<option value="">Select Subject</option>';
            subjects.forEach(sub => {
                const opt = document.createElement('option');
                opt.value = sub.id;
                opt.textContent = sub.name;
                subjectSelect.appendChild(opt);
            });
        });
    // Students
    fetch(`/api/v1/attendance/students?class_id=${classId}`)
        .then(res => res.json())
        .then(students => {
            const tbody = document.getElementById('students-table-body');
            tbody.innerHTML = '';
            students.forEach((student, idx) => {
                const tr = document.createElement('tr');
                tr.innerHTML = `
                    <td>${idx + 1}</td>
                    <td>${student.first_name} ${student.last_name}</td>
                    <td>
                        <select name="status[${student.id}]" class="form-control status-select">
                            <option value="present">Present</option>
                            <option value="absent">Absent</option>
                            <option value="late">Late</option>
                            <option value="excused">Excused</option>
                        </select>
                    </td>
                    <td><input type="text" name="remarks[${student.id}]" class="form-control"></td>
                `;
                tbody.appendChild(tr);
            });
        });
    // Sessions
    loadSessions();
}

document.getElementById('class_id').addEventListener('change', loadSubjectsAndStudents);

document.getElementById('date').addEventListener('change', loadSessions);

function loadSessions() {
    const classId = document.getElementById('class_id').value;
    const date = document.getElementById('date').value;
    fetch(`/api/v1/attendance/sessions?class_id=${classId}&date=${date}`)
        .then(res => res.json())
        .then(sessions => {
            const sessionSelect = document.getElementById('session_id');
            sessionSelect.innerHTML = '<option value="">Select Session</option>';
            sessions.forEach(sess => {
                const opt = document.createElement('option');
                opt.value = sess.id;
                opt.textContent = sess.session_name + (sess.start_time ? ` (${sess.start_time} - ${sess.end_time})` : '');
                sessionSelect.appendChild(opt);
            });
        });
}

document.getElementById('mark-all-present').addEventListener('click', function() {
    document.querySelectorAll('.status-select').forEach(function(select) {
        select.value = 'present';
    });
});

document.getElementById('attendance-form').addEventListener('submit', function(e) {
    e.preventDefault();
    const form = e.target;
    const formData = new FormData(form);
    fetch('/api/v1/attendance/save', {
        method: 'POST',
        headers: {
            'Accept': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            'Authorization': 'Bearer ' + (window.LaravelSanctumToken || '')
        },
        body: formData
    })
    .then(res => res.json())
    .then(data => {
        if (data.success) {
            alert('Attendance saved successfully!');
        } else {
            alert('Error: ' + (data.message || 'Could not save attendance.'));
        }
    })
    .catch(() => alert('An error occurred while saving attendance.'));
});
</script>
@endpush 