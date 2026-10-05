<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;

class AnnouncementsController extends Controller
{
    public function index(Request $request)
    {
        // Placeholder data; replace with Eloquent query e.g., Announcement::query()->latest()
        $items = collect([
            ['title' => 'Term Opening', 'priority' => 'High', 'date' => now()->toDateString()],
            ['title' => 'Sports Day', 'priority' => 'Medium', 'date' => now()->addDays(3)->toDateString()],
            ['title' => 'Library Week', 'priority' => 'Low', 'date' => now()->addWeek()->toDateString()],
        ]);

        $perPage = 10; $page = LengthAwarePaginator::resolveCurrentPage();
        $paginator = new LengthAwarePaginator(
            $items->forPage($page, $perPage)->values(),
            $items->count(),
            $perPage,
            $page,
            ['path' => $request->url(), 'query' => $request->query()]
        );

        return view('admin.announcements.index', ['announcements' => $paginator]);
    }
}
