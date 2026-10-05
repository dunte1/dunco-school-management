@extends('layouts.app')

@section('content')
<div class="container">
  <h1 class="mb-3">Attendance Analytics</h1>
  <form class="row g-2 mb-3" id="filters">
    <div class="col-auto">
      <label class="form-label">Class ID</label>
      <input type="number" class="form-control" name="class_id" />
    </div>
    <div class="col-auto">
      <label class="form-label">From</label>
      <input type="date" class="form-control" name="from" />
    </div>
    <div class="col-auto">
      <label class="form-label">To</label>
      <input type="date" class="form-control" name="to" />
    </div>
    <div class="col-auto align-self-end">
      <button class="btn btn-primary" type="submit">Apply</button>
    </div>
  </form>

  <div class="row g-3">
    <div class="col-md-6">
      <div class="card">
        <div class="card-header">Daily Attendance Rate</div>
        <div class="card-body">
          <canvas id="dailyChart" height="160"></canvas>
        </div>
      </div>
    </div>
    <div class="col-md-6">
      <div class="card">
        <div class="card-header">Weekly Attendance Rate</div>
        <div class="card-body">
          <canvas id="weeklyChart" height="160"></canvas>
        </div>
      </div>
    </div>
  </div>

  <div class="card mt-3">
    <div class="card-header">Heatmap</div>
    <div class="card-body">
      <div id="heatmap" style="display:grid; grid-template-columns: repeat(7, 1fr); gap: 6px;"></div>
    </div>
  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js" defer></script>
<script>
document.addEventListener('DOMContentLoaded', function () {
  const ctxDaily = document.getElementById('dailyChart').getContext('2d');
  const ctxWeekly = document.getElementById('weeklyChart').getContext('2d');
  const heatEl = document.getElementById('heatmap');
  const form = document.getElementById('filters');

  function fetchData() {
    const params = new URLSearchParams(new FormData(form));
    fetch('/api/attendance/heatmap?' + params.toString())
      .then(r => r.json())
      .then(data => {
        // Expect: { daily: [{date,rate}], weekly:[{week,rate}], heatmap:[{day,hour,rate}] }
        renderDaily(data.daily || []);
        renderWeekly(data.weekly || []);
        renderHeatmap(data.heatmap || []);
      });
  }

  let dailyChart, weeklyChart;
  function renderDaily(list) {
    const labels = list.map(i => i.date);
    const values = list.map(i => i.rate);
    if (dailyChart) dailyChart.destroy();
    dailyChart = new Chart(ctxDaily, { type: 'line', data: { labels, datasets: [{ label: 'Rate %', data: values, borderColor: '#1ea7ff' }]}});
  }
  function renderWeekly(list) {
    const labels = list.map(i => i.week);
    const values = list.map(i => i.rate);
    if (weeklyChart) weeklyChart.destroy();
    weeklyChart = new Chart(ctxWeekly, { type: 'bar', data: { labels, datasets: [{ label: 'Rate %', data: values, backgroundColor: '#3949ab' }]}});
  }
  function renderHeatmap(list) {
    heatEl.innerHTML = '';
    for (let d = 0; d < 7; d++) {
      const cell = document.createElement('div');
      const v = (list.find(item => item.day === d)?.rate) || 0;
      const color = `rgba(30,167,255,${v/100})`;
      cell.style.cssText = `height: 40px; background:${color}; border-radius:6px;`;
      cell.title = `Day ${d}: ${v}%`;
      heatEl.appendChild(cell);
    }
  }

  form.addEventListener('submit', function (e) { e.preventDefault(); fetchData(); });
  fetchData();
});
</script>
@endsection


