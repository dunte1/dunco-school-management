<?php

namespace App\Http\Controllers\Finance;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;

class PaymentsController extends Controller
{
    public function index(Request $request)
    {
        // Placeholder dataset to simulate payments; replace with Eloquent query later
        $items = collect([
            ['student' => 'Jane Doe', 'amount' => 12000, 'method' => 'Cash', 'date' => now()->toDateString()],
            ['student' => 'John Smith', 'amount' => 8500, 'method' => 'Card', 'date' => now()->subDay()->toDateString()],
            ['student' => 'Mary Johnson', 'amount' => 15000, 'method' => 'Bank Transfer', 'date' => now()->subDays(2)->toDateString()],
        ]);

        $perPage = 10; $page = LengthAwarePaginator::resolveCurrentPage();
        $paginator = new LengthAwarePaginator(
            $items->forPage($page, $perPage)->values(),
            $items->count(),
            $perPage,
            $page,
            ['path' => $request->url(), 'query' => $request->query()]
        );

        return view('finance.payments.index', ['payments' => $paginator]);
    }
}
