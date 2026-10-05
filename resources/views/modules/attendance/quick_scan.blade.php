@extends('layouts.app')

@section('content')
<div class="container py-4">
    <h2 class="mb-3">Quick Scan Attendance</h2>
    <div class="row g-3 mb-3">
        <div class="col-md-3">
            <label class="form-label">Session ID</label>
            <input type="number" id="session_id" class="form-control" placeholder="e.g. 123" required>
        </div>
        <div class="col-md-3">
            <label class="form-label">Status</label>
            <select id="status" class="form-control">
                <option value="present">Present</option>
                <option value="late">Late</option>
                <option value="absent">Absent</option>
            </select>
        </div>
        <div class="col-md-6 d-flex align-items-end">
            <div id="scan-result" class="text-muted small"></div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-6">
            <div id="qr-reader" style="width:100%"></div>
        </div>
        <div class="col-md-6">
            <label class="form-label">Manual Student ID</label>
            <div class="input-group mb-2">
                <input type="number" id="manual_student_id" class="form-control" placeholder="Enter student ID">
                <button class="btn btn-primary" id="btn-submit-manual">Mark</button>
            </div>
            <div class="card">
                <div class="card-header">Recent Scans</div>
                <ul id="recent-scans" class="list-group list-group-flush" style="max-height:280px; overflow:auto"></ul>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://unpkg.com/html5-qrcode" defer></script>
<script>
document.addEventListener('DOMContentLoaded', function(){
    let scanner;
    function initScanner(){
        if (!window.Html5QrcodeScanner) { setTimeout(initScanner, 300); return; }
        scanner = new Html5QrcodeScanner('qr-reader', { fps: 10, qrbox: 250 });
        scanner.render(onScanSuccess, function(){});
    }
    initScanner();

    async function submitAttendance(studentId){
        const sessionId = document.getElementById('session_id').value;
        const status = document.getElementById('status').value;
        if (!sessionId) { alert('Enter Session ID'); return; }
        try {
            const resp = await fetch('/api/attendance/qr', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json', 'Accept': 'application/json' },
                body: JSON.stringify({ student_id: Number(studentId), session_id: Number(sessionId), scanned_at: new Date().toISOString(), status })
            });
            const data = await resp.json();
            const result = document.getElementById('scan-result');
            result.textContent = 'Marked student ' + studentId + ' as ' + status + ' at session ' + sessionId;
            prependRecentScan(studentId, status);
        } catch (e) {
            console.error(e);
            alert('Failed to mark attendance');
        }
    }

    function prependRecentScan(studentId, status){
        const ul = document.getElementById('recent-scans');
        const li = document.createElement('li');
        li.className = 'list-group-item';
        li.textContent = new Date().toLocaleTimeString() + ' - ID ' + studentId + ' → ' + status;
        ul.prepend(li);
        while (ul.children.length > 20) ul.removeChild(ul.lastChild);
    }

    function onScanSuccess(decodedText){
        // Expect QR to contain either a plain student ID or JSON {student_id, session_id}
        try {
            const obj = JSON.parse(decodedText);
            if (obj.student_id) {
                if (obj.session_id && !document.getElementById('session_id').value) {
                    document.getElementById('session_id').value = obj.session_id;
                }
                submitAttendance(obj.student_id);
                return;
            }
        } catch(_){}
        if (/^\d+$/.test(decodedText)) {
            submitAttendance(decodedText);
        }
    }

    document.getElementById('btn-submit-manual').addEventListener('click', function(){
        const id = document.getElementById('manual_student_id').value;
        if (id) submitAttendance(id);
    });
});
</script>
@endpush




