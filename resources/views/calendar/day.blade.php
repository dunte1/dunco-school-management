@extends('layouts.app')

@section('content')
<div class="container py-4">
    <h1 class="mb-3"><i class="fas fa-calendar-day me-2"></i>Day View</h1>
    <p class="text-muted">Selected date: <strong>{{ $date }}</strong></p>

    <div class="d-flex gap-2 mb-4">
        <a href="{{ url('/calendar/day?date=' . \Carbon\Carbon::parse($date)->subDay()->toDateString()) }}" class="btn btn-outline-secondary">
            <i class="fas fa-chevron-left"></i> Previous Day
        </a>
        <a href="{{ url('/calendar/day?date=' . \Carbon\Carbon::parse($date)->addDay()->toDateString()) }}" class="btn btn-outline-secondary">
            Next Day <i class="fas fa-chevron-right"></i>
        </a>
        <a href="{{ url('/calendar/day?date=' . now()->toDateString()) }}" class="btn btn-outline-primary">
            Today
        </a>
        <a href="{{ url('/calendar/events/create?date=' . $date) }}" class="btn btn-primary">
            <i class="fas fa-plus me-2"></i>Add Event
        </a>
    </div>

    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <span>Events on {{ $date }}</span>
        </div>
        <div class="card-body">
            @if(($events ?? null) && $events->count())
                <div id="eventsList" class="list-group">
                    @foreach($events as $event)
                        @php
                            $title = is_array($event) ? ($event['title'] ?? 'Untitled') : ($event->title ?? 'Untitled');
                            $location = is_array($event) ? ($event['location'] ?? null) : ($event->location ?? null);
                            $startsRaw = is_array($event) ? ($event['starts_at'] ?? null) : ($event->starts_at ?? null);
                            $endsRaw = is_array($event) ? ($event['ends_at'] ?? null) : ($event->ends_at ?? null);
                            try { $startsAt = $startsRaw ? \Carbon\Carbon::parse($startsRaw) : null; } catch (\Throwable $e) { $startsAt = null; }
                            try { $endsAt = $endsRaw ? \Carbon\Carbon::parse($endsRaw) : null; } catch (\Throwable $e) { $endsAt = null; }
                        @endphp
                        <div class="list-group-item d-flex justify-content-between align-items-center">
                            <div>
                                <div class="fw-semibold">{{ $title }}</div>
                                <small class="text-muted">
                                    {{ $startsAt ? $startsAt->format('H:i') : '-' }}
                                    @if($endsAt)
                                        – {{ $endsAt->format('H:i') }}
                                    @endif
                                    @if($location)
                                        | {{ $location }}
                                    @endif
                                </small>
                            </div>
                            <a href="#" class="btn btn-sm btn-outline-primary">View</a>
                        </div>
                    @endforeach
                </div>
                
            @else
                <p id="eventsEmpty" class="text-muted mb-0">No events to display.</p>
            @endif
        </div>
    </div>
</div>
<script>
// Simple polling to refresh events every 10 seconds
(function(){
  const date = @json($date);
  const list = document.getElementById('eventsList');
  const empty = document.getElementById('eventsEmpty');
  async function refresh(){
    try {
      const res = await fetch(`/api/calendar/events?date=${encodeURIComponent(date)}`, { credentials: 'same-origin' });
      const data = await res.json();
      if (!data || !data.ok) return;
      const evs = data.events || [];
      if (list) list.innerHTML = '';
      if (!evs.length) {
        if (empty) empty.style.display = 'block';
        if (list) list.style.display = 'none';
        return;
      }
      if (empty) empty.style.display = 'none';
      if (list) list.style.display = '';
      evs.forEach(e => {
        const starts = e.starts_at ? new Date(e.starts_at) : null;
        const ends = e.ends_at ? new Date(e.ends_at) : null;
        const time = `${starts ? starts.toLocaleTimeString([], {hour:'2-digit', minute:'2-digit'}) : '-'}` + (ends ? ` – ${ends.toLocaleTimeString([], {hour:'2-digit', minute:'2-digit'})}` : '');
        const item = document.createElement('div');
        item.className = 'list-group-item d-flex justify-content-between align-items-center';
        item.innerHTML = `
          <div>
            <div class="fw-semibold">${(e.title||'Untitled')}</div>
            <small class="text-muted">${time}${e.location ? ' | ' + e.location : ''}</small>
          </div>
          <a href="#" class="btn btn-sm btn-outline-primary">View</a>
        `;
        list.appendChild(item);
      });
    } catch (err) { /* silent */ }
  }
  setInterval(refresh, 10000);
})();
</script>
@endsection
