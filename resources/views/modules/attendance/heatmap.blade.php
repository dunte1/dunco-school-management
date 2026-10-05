@extends('layouts.app')

@section('content')
<div class="container py-4">
    <h2 class="mb-3">Attendance Heatmap</h2>
    <div class="row g-3 mb-3">
        <div class="col-md-3">
            <label class="form-label">Class ID</label>
            <input type="number" id="class_id" class="form-control" placeholder="optional">
        </div>
        <div class="col-md-3">
            <label class="form-label">Month</label>
            <input type="month" id="month" class="form-control" value="{{ date('Y-m') }}">
        </div>
        <div class="col-md-3 d-flex align-items-end">
            <button class="btn btn-primary" id="btn-load">Load</button>
        </div>
    </div>
    <div id="heatmap" class="d-grid" style="grid-template-columns: repeat(7, 1fr); gap:6px"></div>
    <div class="small text-muted mt-2">Color intensity shows absence rate (darker = more absences)</div>
</div>
@endsection

@push('scripts')
<script>
async function loadHeatmap(){
    const classId = document.getElementById('class_id').value;
    const month = document.getElementById('month').value;
    const resp = await fetch(`/api/attendance/heatmap?month=${month}&class_id=${classId||''}`);
    const data = await resp.json();
    const container = document.getElementById('heatmap');
    container.innerHTML = '';
    const daysInMonth = new Date(month.split('-')[0], month.split('-')[1], 0).getDate();
    const firstDay = new Date(month + '-01').getDay();
    for (let i=0;i<firstDay;i++){ const cell = document.createElement('div'); container.appendChild(cell); }
    for (let d=1; d<=daysInMonth; d++){
        const key = month + '-' + String(d).padStart(2,'0');
        const v = data[key] || { absent: 0, total: 0 };
        const rate = v.total ? (v.absent / v.total) : 0;
        const shade = Math.floor(255 - rate * 200);
        const cell = document.createElement('div');
        cell.style.backgroundColor = `rgb(255,${shade},${shade})`;
        cell.style.height = '42px';
        cell.style.border = '1px solid #eee';
        cell.title = `${key}: ${v.absent}/${v.total} absent`;
        cell.className = 'd-flex align-items-center justify-content-center small';
        cell.textContent = d;
        container.appendChild(cell);
    }
}
document.addEventListener('DOMContentLoaded', function(){
    document.getElementById('btn-load').addEventListener('click', loadHeatmap);
    loadHeatmap();
});
</script>
@endpush




