<?php

namespace App\Http\Controllers\Library;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;

class TransactionsController extends Controller
{
    public function index(Request $request)
    {
        $items = collect([
            ['student' => 'Alice Adams', 'book' => 'Intro to Biology', 'action' => 'Borrowed', 'date' => now()->toDateString()],
            ['student' => 'Bob Brown', 'book' => 'World History', 'action' => 'Returned', 'date' => now()->subDay()->toDateString()],
            ['student' => 'Cara Cox', 'book' => 'Algebra II', 'action' => 'Overdue', 'date' => now()->subDays(5)->toDateString()],
        ]);

        $perPage = 10; $page = LengthAwarePaginator::resolveCurrentPage();
        $paginator = new LengthAwarePaginator(
            $items->forPage($page, $perPage)->values(),
            $items->count(),
            $perPage,
            $page,
            ['path' => $request->url(), 'query' => $request->query()]
        );

        return view('library.transactions.index', ['transactions' => $paginator]);
    }
}
