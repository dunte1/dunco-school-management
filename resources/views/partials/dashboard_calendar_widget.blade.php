<div class="sidebar-card mb-4">
  <div class="sidebar-card-header d-flex justify-content-between align-items-center">
    <h5 class="sidebar-card-title mb-0">
      <i class="fas fa-calendar me-2"></i>Calendar
    </h5>
    <button class="btn btn-sm btn-outline-secondary" onclick="location.href='/calendar/day?date='+new Date().toISOString().slice(0,10)">View Full</button>
  </div>
  <div class="sidebar-card-body">
    <style>
      .calendar-grid {display:grid;grid-template-columns:repeat(7,1fr);gap:.25rem}
      .cal-cell {padding:.4rem .2rem;text-align:center;border-radius:.4rem;cursor:default}
      .cal-cell.muted{color:#94a3b8}
      .cal-cell.has-events{background:rgba(59,130,246,.14);border:1px solid rgba(59,130,246,.35); box-shadow:0 0 0 1px rgba(59,130,246,.15) inset}
      .cal-cell.type-exam{background:rgba(99,102,241,.14);border-color:rgba(99,102,241,.35)}
      .cal-cell.type-meeting{background:rgba(16,185,129,.14);border-color:rgba(16,185,129,.35)}
      .cal-cell.type-sports{background:rgba(249,115,22,.14);border-color:rgba(249,115,22,.35)}
      .cal-cell.type-holiday{background:rgba(239,68,68,.14);border-color:rgba(239,68,68,.35)}
      .cal-head{font-weight:600;color:#475569}
      .event-chip{display:flex;gap:.5rem;align-items:center;padding:.25rem .5rem;border:1px solid #e2e8f0;border-radius:.5rem;margin-bottom:.35rem}
      .event-chip.type-exam{border-color:rgba(99,102,241,.35);background:rgba(99,102,241,.06)}
      .event-chip.type-meeting{border-color:rgba(16,185,129,.35);background:rgba(16,185,129,.06)}
      .event-chip.type-sports{border-color:rgba(249,115,22,.35);background:rgba(249,115,22,.06)}
      .event-chip.type-holiday{border-color:rgba(239,68,68,.35);background:rgba(239,68,68,.06)}
      .event-chip .when{font-size:.75rem;color:#64748b}
    </style>
    <div class="mini-calendar">
      <div class="calendar-header d-flex justify-content-between align-items-center mb-3">
        <button id="btnCalPrev" class="btn btn-sm btn-outline-secondary" type="button"><i class="fas fa-chevron-left"></i></button>
        <h6 id="miniCalMonth" class="mb-0">&nbsp;</h6>
        <button id="btnCalNext" class="btn btn-sm btn-outline-secondary" type="button"><i class="fas fa-chevron-right"></i></button>
      </div>
      <div class="calendar-grid mb-2 text-xs">
        <div class="cal-head">Sun</div><div class="cal-head">Mon</div><div class="cal-head">Tue</div><div class="cal-head">Wed</div><div class="cal-head">Thu</div><div class="cal-head">Fri</div><div class="cal-head">Sat</div>
      </div>
      <div id="miniCalendarGrid" class="calendar-grid"></div>
    </div>
    <div class="upcoming-events mt-3">
      <h6 class="mb-2">Upcoming Events</h6>
      <div id="upcomingEventsList"></div>
    </div>
  </div>
</div>

@push('scripts')
<script>
(function(){
  // State
  let view = new Date();
  view.setDate(1);
  const monthEl = document.getElementById('miniCalMonth');
  const grid = document.getElementById('miniCalendarGrid');
  const prevBtn = document.getElementById('btnCalPrev');
  const nextBtn = document.getElementById('btnCalNext');
  const upcomingEl = document.getElementById('upcomingEventsList');

  function ymd(d){ return d.toISOString().slice(0,10); }
  function monthName(d){ return d.toLocaleString(undefined,{month:'long', year:'numeric'}); }

  async function fetchMonth(y, m){
    const res = await fetch(`/api/calendar/month?year=${y}&month=${m}`, {credentials:'same-origin'});
    return await res.json();
  }
  async function fetchUpcoming(){
    const res = await fetch('/api/calendar/upcoming?limit=5', {credentials:'same-origin'});
    return await res.json();
  }

  function typeClass(type){
    if (!type) return '';
    const t = String(type).toLowerCase();
    if (t.includes('exam')) return 'type-exam';
    if (t.includes('meet')) return 'type-meeting';
    if (t.includes('sport')) return 'type-sports';
    if (t.includes('holiday') || t.includes('leave')) return 'type-holiday';
    return '';
  }

  function buildMonthGrid(year, month, counts, samples){
    if (!grid) return;
    grid.innerHTML = '';
    const first = new Date(year, month-1, 1);
    const startDow = first.getDay();
    const daysInMonth = new Date(year, month, 0).getDate();
    const prevDays = new Date(year, month-1, 0).getDate();

    // Leading muted days
    for (let i=0;i<startDow;i++){
      const day = prevDays - startDow + i + 1;
      const el = document.createElement('div');
      el.className='cal-cell muted'; el.textContent=day;
      grid.appendChild(el);
    }
    // Current month
    for (let d=1; d<=daysInMonth; d++){
      const dateStr = `${year}-${String(month).padStart(2,'0')}-${String(d).padStart(2,'0')}`;
      const el = document.createElement('div');
      el.className='cal-cell'; el.textContent=d;
      const c = counts && counts[dateStr] ? counts[dateStr] : 0;
      const sample = samples && samples[dateStr] ? samples[dateStr] : null;
      if (c>0){
        el.classList.add('has-events');
        const tClass = typeClass(sample && sample.sample_type);
        if (tClass) el.classList.add(tClass);
        const title = sample && sample.sample_title ? sample.sample_title : `${c} event(s)`;
        el.title = title;
      }
      el.addEventListener('click', ()=>{ window.location.href = `/calendar/day?date=${dateStr}`; });
      grid.appendChild(el);
    }
    // Trailing to complete rows
    const totalCells = startDow + daysInMonth;
    const trailing = (7 - (totalCells % 7)) % 7;
    for (let i=1; i<=trailing; i++){
      const el = document.createElement('div'); el.className='cal-cell muted'; el.textContent=i; grid.appendChild(el);
    }
  }

  async function renderMonth(){
    const y = view.getFullYear(); const m = view.getMonth()+1;
    if (monthEl) monthEl.textContent = monthName(view);
    try{
      const data = await fetchMonth(y,m);
      if (data && data.ok) buildMonthGrid(y,m,data.counts, data.samples);
    }catch(e){ /* ignore */ }
  }

  async function renderUpcoming(){
    if (!upcomingEl) return;
    try{
      const data = await fetchUpcoming();
      if (!data || !data.ok) return;
      const items = data.events || [];
      upcomingEl.innerHTML = '';
      if (!items.length){ upcomingEl.innerHTML = '<div class="text-muted small">No upcoming events</div>'; return; }
      items.forEach(e=>{
        const starts = e.starts_at ? new Date(e.starts_at) : null;
        const time = starts ? starts.toLocaleString([], {month:'short', day:'2-digit', hour:'2-digit', minute:'2-digit'}) : '';
        const wrap = document.createElement('div');
        const cls = 'event-chip ' + typeClass(e.type||'');
        wrap.className=cls;
        wrap.innerHTML = `<div class="title fw-semibold">${e.title||'Untitled'}</div><div class="when">${time}</div>`;
        wrap.addEventListener('click', ()=>{ if (starts) window.location.href=`/calendar/day?date=${starts.toISOString().slice(0,10)}`; });
        upcomingEl.appendChild(wrap);
      });
    }catch(e){ /* ignore */ }
  }

  if (prevBtn) prevBtn.addEventListener('click', ()=>{ view.setMonth(view.getMonth()-1); renderMonth(); });
  if (nextBtn) nextBtn.addEventListener('click', ()=>{ view.setMonth(view.getMonth()+1); renderMonth(); });

  // Broadcast channel to force-refresh calendars on all open dashboards immediately after creation
  let ch;
  try { ch = new BroadcastChannel('calendar-events'); } catch(e) { ch = null; }
  if (ch) {
    ch.onmessage = (evt) => {
      if (!evt || !evt.data) return;
      if (evt.data.type === 'event_created') { renderMonth(); renderUpcoming(); }
    };
  }

  // If we arrived with ?created=1 in URL (e.g. after creating on day view), broadcast to other tabs
  try {
    const q = new URLSearchParams(window.location.search);
    if (q.get('created') === '1' && ch) {
      ch.postMessage({ type: 'event_created' });
    }
  } catch(e) {}

  // Initial
  renderMonth();
  renderUpcoming();
  // Poll upcoming every 10s
  setInterval(renderUpcoming, 10000);
})();
</script>
@endpush
