<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Carbon;

class CalendarController extends Controller
{
    public function day(Request $request)
    {
        $date = $request->query('date', now()->toDateString());

        // Placeholder events list; replace with real query later
        $events = collect([
            ['title' => 'Staff Meeting', 'time' => '09:00', 'location' => 'Conference Room'],
            ['title' => 'Parent-Teacher Conference', 'time' => '11:00', 'location' => 'Hall A'],
            ['title' => 'Math Exam - Grade 8', 'time' => '14:00', 'location' => 'Room 204'],
        ]);

        $perPage = 10; $page = LengthAwarePaginator::resolveCurrentPage();
        $paginator = new LengthAwarePaginator(
            $events->forPage($page, $perPage)->values(),
            $events->count(),
            $perPage,
            $page,
            ['path' => $request->url(), 'query' => $request->query()]
        );

        return view('calendar.day', [
            'date' => $date,
            'events' => $paginator,
        ]);
    }
}
