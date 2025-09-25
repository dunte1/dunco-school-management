@extends('layouts.app')

@section('content')
<div class="container py-4">
    <h2>Timetable Analytics</h2>
    <div class="mb-4">
        <h5>Schedule Trends (per week)</h5>
        <canvas id="trendsChart" height="80"></canvas>
    </div>
    <div class="mb-4">
        <h5>Teacher Utilization Heatmap</h5>
        @foreach($teacherHeatmap as $teacher => $heatmap)
        <h6>{{ $teacher }}</h6>
        <table class="table table-bordered table-sm">
            <thead><tr><th>Day/Hour</th>@foreach($hours as $h)<th>{{ $h }}:00</th>@endforeach</tr></thead>
            <tbody>
                @foreach($days as $d)
                <tr><td>{{ $d }}</td>@foreach($hours as $h)<td>{{ $heatmap[$d][$h] ?? 0 }}</td>@endforeach</tr>
                @endforeach
            </tbody>
        </table>
        @endforeach
    </div>
    <div class="mb-4">
        <h5>Room Utilization Heatmap</h5>
        @foreach($roomHeatmap as $room => $heatmap)
        <h6>{{ $room }}</h6>
        <table class="table table-bordered table-sm">
            <thead><tr><th>Day/Hour</th>@foreach($hours as $h)<th>{{ $h }}:00</th>@endforeach</tr></thead>
            <tbody>
                @foreach($days as $d)
                <tr><td>{{ $d }}</td>@foreach($hours as $h)<td>{{ $heatmap[$d][$h] ?? 0 }}</td>@endforeach</tr>
                @endforeach
            </tbody>
        </table>
        @endforeach
    </div>
    <div class="mb-4">
        <h5>Teacher Free/Busy Slots</h5>
        @foreach($teacherFreeBusy as $teacher => $fb)
        <h6>{{ $teacher }}</h6>
        <table class="table table-bordered table-sm">
            <thead><tr><th>Day/Hour</th>@foreach($hours as $h)<th>{{ $h }}:00</th>@endforeach</tr></thead>
            <tbody>
                @foreach($days as $d)
                <tr><td>{{ $d }}</td>@foreach($hours as $h)<td class="{{ $fb[$d][$h]=='Busy'?'table-danger':'table-success' }}">{{ $fb[$d][$h] }}</td>@endforeach</tr>
                @endforeach
            </tbody>
        </table>
        @endforeach
    </div>
    <div class="mb-4">
        <h5>Room Free/Busy Slots</h5>
        @foreach($roomFreeBusy as $room => $fb)
        <h6>{{ $room }}</h6>
        <table class="table table-bordered table-sm">
            <thead><tr><th>Day/Hour</th>@foreach($hours as $h)<th>{{ $h }}:00</th>@endforeach</tr></thead>
            <tbody>
                @foreach($days as $d)
                <tr><td>{{ $d }}</td>@foreach($hours as $h)<td class="{{ $fb[$d][$h]=='Busy'?'table-danger':'table-success' }}">{{ $fb[$d][$h] }}</td>@endforeach</tr>
                @endforeach
            </tbody>
        </table>
        @endforeach
    </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
const ctx = document.getElementById('trendsChart').getContext('2d');
const trendsData = {
    labels: {!! json_encode(array_keys($trends->toArray())) !!},
    datasets: [{
        label: 'Schedules per Week',
        data: {!! json_encode(array_values($trends->toArray())) !!},
        borderColor: 'rgba(54, 162, 235, 1)',
        backgroundColor: 'rgba(54, 162, 235, 0.2)',
        fill: true,
        tension: 0.3
    }]
};
new Chart(ctx, {
    type: 'line',
    data: trendsData,
    options: {
        responsive: true,
        plugins: { legend: { display: false } },
        scales: { y: { beginAtZero: true } }
    }
});
</script>
@endsection 