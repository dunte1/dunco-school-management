@extends('layouts.app')

@section('title', 'Proctor Dashboard')

@section('content')
<div class="container-xl py-4">
    <h2 class="fw-bold mb-4" style="color:#1a237e;">Proctor Dashboard</h2>
    <div class="row mb-4">
        <div class="col-md-6">
            <div class="card glass-card mb-4">
                <div class="card-header bg-white border-0 rounded-xl">
                    <h5 class="mb-0" style="color:#1a237e;">Ongoing Online Exams</h5>
                </div>
                <div class="card-body">
                    @forelse($exams as $exam)
                        <div class="mb-3">
                            <div class="fw-bold" style="color:#2196f3;">{{ $exam->name }} ({{ $exam->code }})</div>
                            <div class="small text-muted mb-2">{{ $exam->examType->name }} | {{ $exam->academic_year }} - {{ $exam->term }}</div>
                            <ul class="list-group mb-2">
                                @foreach($exam->attempts as $attempt)
                                    <li class="list-group-item d-flex justify-content-between align-items-center">
                                        <span>{{ $attempt->student->name ?? 'Unknown Student' }}</span>
                                        <span class="badge bg-primary">{{ ucfirst($attempt->status) }}</span>
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                    @empty
                        <div class="text-muted">No ongoing online exams with proctoring enabled.</div>
                    @endforelse
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card glass-card mb-4">
                <div class="card-header bg-white border-0 rounded-xl">
                    <h5 class="mb-0" style="color:#1a237e;">Recent Proctoring Alerts</h5>
                </div>
                <div class="card-body" id="proctoring-alerts">
                    @include('examination::proctoring.partials.alerts', ['recentLogs' => $recentLogs])
                </div>
            </div>
        </div>
    </div>
</div>
@push('scripts')
<script>
function fetchProctoringAlerts() {
    fetch("{{ route('examination.proctoring.dashboard') }}?ajax=1")
        .then(response => response.text())
        .then(html => {
            document.getElementById('proctoring-alerts').innerHTML = html;
        });
}
setInterval(fetchProctoringAlerts, 10000); // Poll every 10 seconds
</script>
@endpush
@endsection 