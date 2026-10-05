<?php

namespace App\Http\Controllers\Calendar;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Auth;
use App\Models\CalendarEvent;
use App\Models\User;

class EventsController extends Controller
{
    public function create(Request $request)
    {
        $date = $request->query('date');
        return view('calendar.events.create', compact('date'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'starts_at' => 'required|date',
            'ends_at' => 'nullable|date|after_or_equal:starts_at',
            'location' => 'nullable|string|max:255',
            'visibility' => 'nullable|in:all,staff,students',
        ]);

        // Normalize to app timezone
        $startsAt = \Carbon\Carbon::parse($data['starts_at'])->timezone(config('app.timezone'));
        $endsAt = !empty($data['ends_at']) ? \Carbon\Carbon::parse($data['ends_at'])->timezone(config('app.timezone')) : null;

        $event = CalendarEvent::create([
            'school_id' => Auth::user()->school_id ?? null,
            'created_by' => Auth::id(),
            'title' => $data['title'],
            'description' => $data['description'] ?? null,
            'starts_at' => $startsAt,
            'ends_at' => $endsAt,
            'location' => $data['location'] ?? null,
            'visibility' => $data['visibility'] ?? 'all',
        ]);

        // Broadcast notification to all users (simple database notification)
        try {
            $users = User::query();
            if ($event->visibility === 'staff') {
                $users = $users->whereHas('roles', function($q){ $q->where('name','teacher')->orWhere('name','admin')->orWhere('name','staff'); });
            } elseif ($event->visibility === 'students') {
                $users = $users->whereHas('roles', function($q){ $q->where('name','student'); });
            }
            $users = $users->get();

            $dayUrl = url('/calendar/day?date=' . $event->starts_at->format('Y-m-d'));
            foreach ($users as $u) {
                $u->notify(new \App\Notifications\GenericDatabaseNotification([
                    'type' => 'calendar_event_created',
                    'title' => 'New Calendar Event',
                    'message' => $event->title,
                    'event_id' => $event->id,
                    'starts_at' => $event->starts_at->toDateTimeString(),
                    'url' => $dayUrl,
                ]));
            }
        } catch (\Throwable $e) {
            // swallow; notifications table may not exist in all environments
        }

        // Redirect to day view so the user sees it immediately
        $dateParam = \Carbon\Carbon::parse($event->starts_at)->format('Y-m-d');
        return redirect()->route('calendar.day', ['date' => $dateParam, 'created' => 1])->with('status', 'Event created');
    }

    public function day(Request $request)
    {
        $date = $request->query('date') ?: now()->toDateString();
        $startOfDay = \Carbon\Carbon::parse($date)->startOfDay();
        $endOfDay = (clone $startOfDay)->endOfDay();

        $events = CalendarEvent::query()
            ->where(function($q) use ($startOfDay, $endOfDay) {
                // Starts within day
                $q->whereBetween('starts_at', [$startOfDay, $endOfDay])
                  // OR ends within day
                  ->orWhereBetween('ends_at', [$startOfDay, $endOfDay])
                  // OR spans across the day (starts before and ends after)
                  ->orWhere(function($qq) use ($startOfDay, $endOfDay) {
                      $qq->where('starts_at', '<=', $startOfDay)
                         ->where(function($qqq) use ($endOfDay) {
                             $qqq->whereNull('ends_at')->orWhere('ends_at', '>=', $endOfDay);
                         });
                  });
            })
            ->orderBy('starts_at')
            ->get();

        return view('calendar.day', compact('date', 'events'));
    }

    // Lightweight API for verifying events by date
    public function apiByDate(Request $request)
    {
        $date = $request->query('date') ?: now()->toDateString();
        $startOfDay = \Carbon\Carbon::parse($date)->startOfDay();
        $endOfDay = (clone $startOfDay)->endOfDay();
        $events = CalendarEvent::query()
            ->where(function($q) use ($startOfDay, $endOfDay) {
                $q->whereBetween('starts_at', [$startOfDay, $endOfDay])
                  ->orWhereBetween('ends_at', [$startOfDay, $endOfDay])
                  ->orWhere(function($qq) use ($startOfDay, $endOfDay) {
                      $qq->where('starts_at', '<=', $startOfDay)
                         ->where(function($qqq) use ($endOfDay) {
                             $qqq->whereNull('ends_at')->orWhere('ends_at', '>=', $endOfDay);
                         });
                  });
            })
            ->orderBy('starts_at')
            ->get();
        return response()->json(['ok' => true, 'date' => $date, 'count' => $events->count(), 'events' => $events]);
    }

    // Month heatmap: counts of events per date for a given month
    public function apiMonth(Request $request)
    {
        $year = (int)($request->query('year') ?: now()->year);
        $month = (int)($request->query('month') ?: now()->month);
        $start = \Carbon\Carbon::create($year, $month, 1)->startOfDay();
        $end = (clone $start)->endOfMonth()->endOfDay();

        // We'll build a map date => {count, sample_title, sample_type}
        $events = CalendarEvent::query()
            ->where(function($q) use ($start, $end) {
                $q->whereBetween('starts_at', [$start, $end])
                  ->orWhereBetween('ends_at', [$start, $end])
                  ->orWhere(function($qq) use ($start, $end) {
                      $qq->where('starts_at', '<=', $start)
                         ->where(function($qqq) use ($end) {
                             $qqq->whereNull('ends_at')->orWhere('ends_at', '>=', $end);
                         });
                  });
            })
            ->get(['starts_at','ends_at','title','type']);

        $counts = [];
        $samples = [];
        $cursor = (clone $start)->startOfDay();
        while ($cursor <= $end) {
            $key = $cursor->toDateString();
            $counts[$key] = 0;
            $samples[$key] = ['sample_title' => null, 'sample_type' => null];
            $cursor->addDay();
        }

        foreach ($events as $ev) {
            $s = $ev->starts_at ? $ev->starts_at->copy()->startOfDay() : $start->copy();
            $e = $ev->ends_at ? $ev->ends_at->copy()->startOfDay() : $ev->starts_at->copy()->startOfDay();
            if ($e < $s) { $e = $s->copy(); }
            if ($s < $start) $s = $start->copy();
            if ($e > $end) $e = $end->copy();
            for ($d = $s->copy(); $d <= $e; $d->addDay()) {
                $key = $d->toDateString();
                if (array_key_exists($key, $counts)) {
                    $counts[$key] += 1;
                    if (empty($samples[$key]['sample_title'])) {
                        $samples[$key]['sample_title'] = $ev->title ?? null;
                        $samples[$key]['sample_type'] = $ev->type ?? null;
                    }
                }
            }
        }

        return response()->json([
            'ok' => true,
            'year' => $year,
            'month' => $month,
            'counts' => $counts,
            'samples' => $samples,
        ]);
    }

    // Upcoming events from now forward
    public function apiUpcoming(Request $request)
    {
        $limit = (int)($request->query('limit') ?: 5);
        $now = now();
        $events = CalendarEvent::query()
            ->where(function($q) use ($now) {
                $q->where('starts_at', '>=', $now)
                  ->orWhere(function($qq) use ($now) {
                      // spanning events already in progress
                      $qq->where('starts_at', '<', $now)
                         ->where(function($qqq) use ($now) { $qqq->whereNull('ends_at')->orWhere('ends_at', '>=', $now); });
                  });
            })
            ->orderBy('starts_at')
            ->limit($limit)
            ->get();

        return response()->json(['ok' => true, 'events' => $events]);
    }
}
