<?php

namespace Modules\Library\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Http\Controllers\Controller;
use App\Models\Modules\Library\Models\Book;
use App\Models\Modules\Library\Models\Member;
use App\Models\Modules\Library\Models\BorrowRecord;
use Carbon\Carbon;

class LibraryDashboardController extends Controller
{
    public function index(Request $request)
    {
        $totalBooks = Book::count();
        $activeMembers = Member::where('status', 'active')->count();
        $booksBorrowed = BorrowRecord::whereNull('returned_at')->count();
        $overdueBooks = BorrowRecord::whereNull('returned_at')
            ->where('due_date', '<', Carbon::now())
            ->count();

        // Borrowing trends for the last 30 days
        $borrowTrends = BorrowRecord::selectRaw('DATE(borrowed_at) as date, COUNT(*) as count')
            ->where('borrowed_at', '>=', Carbon::now()->subDays(30))
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        // Recent activity (last 5 borrows/returns)
        $recentActivity = BorrowRecord::with(['book', 'member'])
            ->orderByDesc('created_at')
            ->limit(5)
            ->get();

        return Inertia::render('Library/Dashboard', [
            'totalBooks' => $totalBooks,
            'activeMembers' => $activeMembers,
            'booksBorrowed' => $booksBorrowed,
            'overdueBooks' => $overdueBooks,
            'borrowTrends' => $borrowTrends,
            'recentActivity' => $recentActivity,
        ]);
    }
} 