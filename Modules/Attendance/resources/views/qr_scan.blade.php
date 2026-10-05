@extends('layouts.app')

@section('content')
<div class="container">
    <h1 class="mb-3">QR Attendance Scan</h1>
    <div id="reader" style="width: 100%; max-width: 480px"></div>
    <div class="mt-3">
        <label class="form-label">Session ID</label>
        <input type="number" id="session_id" class="form-control" placeholder="Enter session ID shown on teacher screen">
    </div>
    <div class="mt-2">
        <div id="scan-status" class="text-muted small"></div>
    </div>
</div>

<script src="https://unpkg.com/html5-qrcode" defer></script>
<script>
document.addEventListener('DOMContentLoaded', function () {
  const readerEl = document.getElementById('reader');
  const statusEl = document.getElementById('scan-status');
  const sessionInput = document.getElementById('session_id');

  function postScan(studentId) {
    const sessionId = sessionInput.value;
    if (!sessionId) {
      statusEl.textContent = 'Enter session ID first.';
      return;
    }
    fetch('/api/attendance/qr', {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
      },
      body: JSON.stringify({ student_id: Number(studentId), session_id: Number(sessionId), scanned_at: new Date().toISOString(), status: 'present' })
    }).then(r => r.json()).then(res => {
      statusEl.textContent = res.success ? 'Marked present.' : 'Failed to mark.';
    }).catch(err => {
      statusEl.textContent = 'Error: ' + err.message;
    });
  }

  function onScanSuccess(decodedText, decodedResult) {
    // Expect QR payload: {"student_id":123}
    try {
      const data = JSON.parse(decodedText);
      if (data.student_id) {
        postScan(data.student_id);
      } else {
        statusEl.textContent = 'Invalid QR payload';
      }
    } catch (e) {
      // Fallback if QR is plain student_id
      const id = parseInt(decodedText, 10);
      if (!isNaN(id)) { postScan(id); } else { statusEl.textContent = 'Invalid QR'; }
    }
  }

  function onScanFailure(error) {
    // no-op; frequent expected
  }

  const html5QrCode = new Html5Qrcode("reader");
  Html5Qrcode.getCameras().then(cameras => {
    const camId = cameras[0]?.id;
    if (!camId) {
      statusEl.textContent = 'No camera found.';
      return;
    }
    html5QrCode.start(
      camId,
      { fps: 10, qrbox: 250 },
      onScanSuccess,
      onScanFailure
    );
  }).catch(err => { statusEl.textContent = 'Camera error: ' + err; });
});
</script>
@endsection


