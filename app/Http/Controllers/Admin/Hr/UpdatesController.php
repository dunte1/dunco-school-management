<?php

namespace App\Http\Controllers\Admin\Hr;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;

class UpdatesController extends Controller
{
    public function index(Request $request)
    {
        $items = collect([
            ['staff' => 'Emily Stone', 'type' => 'New Hire', 'details' => 'Joined as Math Teacher', 'date' => now()->toDateString()],
            ['staff' => 'George King', 'type' => 'Promotion', 'details' => 'Promoted to Head of Science', 'date' => now()->subDay()->toDateString()],
            ['staff' => 'Hannah Lee', 'type' => 'Transfer', 'details' => 'Transferred to Branch B', 'date' => now()->subDays(2)->toDateString()],
        ]);

        $perPage = 10; $page = LengthAwarePaginator::resolveCurrentPage();
        $paginator = new LengthAwarePaginator(
            $items->forPage($page, $perPage)->values(),
            $items->count(),
            $perPage,
            $page,
            ['path' => $request->url(), 'query' => $request->query()]
        );

        return view('admin.hr.updates.index', ['updates' => $paginator]);
    }
}
