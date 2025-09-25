<?php

namespace Modules\Library\Http\Controllers;

use Illuminate\Http\Request;
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

        // Recent books
        $recentBooks = Book::with(['author', 'category', 'publisher'])
            ->orderByDesc('created_at')
            ->limit(5)
            ->get();

        // Books by status
        $booksByStatus = Book::selectRaw('status, COUNT(*) as count')
            ->groupBy('status')
            ->get();

        // Recent activity (last 5 borrows/returns)
        $recentActivity = BorrowRecord::with(['book', 'member'])
            ->orderByDesc('created_at')
            ->limit(5)
            ->get();

        return view('library::dashboard', compact(
            'totalBooks',
            'activeMembers', 
            'booksBorrowed',
            'overdueBooks',
            'recentBooks',
            'booksByStatus',
            'recentActivity'
        ));
    }
} 