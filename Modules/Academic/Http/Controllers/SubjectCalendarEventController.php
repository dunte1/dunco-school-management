<?php

namespace Modules\Academic\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Modules\Academic\Models\SubjectCalendarEvent;

class SubjectCalendarEventController extends Controller
{
    public function index($subjectId)
    {
        $events = SubjectCalendarEvent::where('subject_id', $subjectId)->get();
        return response()->json(['events' => $events]);
    }
    public function store(Request $request, $subjectId)
    {
        $request->validate([
            'event_id' => 'required|string',
            'provider' => 'required|string',
            'event_url' => 'nullable|url',
            'start_time' => 'nullable|date',
            'end_time' => 'nullable|date',
        ]);
        $event = SubjectCalendarEvent::create([
            'subject_id' => $subjectId,
            'event_id' => $request->event_id,
            'provider' => $request->provider,
            'event_url' => $request->event_url,
            'start_time' => $request->start_time,
            'end_time' => $request->end_time,
        ]);
        return response()->json(['success' => true, 'event' => $event]);
    }
    public function destroy($subjectId, $eventId)
    {
        $event = SubjectCalendarEvent::where('subject_id', $subjectId)->findOrFail($eventId);
        $event->delete();
        return response()->json(['success' => true]);
    }
} 