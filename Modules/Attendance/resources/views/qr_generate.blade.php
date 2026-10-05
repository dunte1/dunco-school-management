@extends('layouts.app')

@section('content')
<div class="container">
    <h1 class="mb-3">Generate Session QR</h1>
    <form class="mb-3" method="GET">
        <div class="row g-2">
            <div class="col-auto"><label class="form-label">Student ID</label><input type="number" name="student_id" class="form-control" value="{{ request('student_id') }}"/></div>
            <div class="col-auto"><label class="form-label">Session ID</label><input type="number" name="session_id" class="form-control" value="{{ request('session_id') }}"/></div>
            <div class="col-auto align-self-end"><button class="btn btn-primary">Generate</button></div>
        </div>
    </form>
    @php
        $payload = null;
        if(request('student_id') && request('session_id')) {
            $payload = json_encode(['student_id' => (int)request('student_id'), 'session_id' => (int)request('session_id')]);
        }
    @endphp
    @if($payload)
        <div>
            <img src="{{ route('attendance.qr.image', ['text' => base64_encode($payload)]) }}" alt="QR Code" />
            <div class="text-muted small mt-2">Show this QR to scanner</div>
        </div>
    @endif
</div>
@endsection


