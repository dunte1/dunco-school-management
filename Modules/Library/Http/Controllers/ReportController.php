<?php

namespace Modules\Library\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Modules\Library\Models\BorrowRecord;
use App\Models\Modules\Library\Models\Book;
use App\Models\Modules\Library\Models\Member;
use App\Models\Modules\Library\Models\Category;
use App\Models\Modules\Library\Models\Publisher;
use App\Models\Modules\Library\Models\Author;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    public function index()
    {
        $stats = [
            'totalBooks' => Book::count(),
            'availableBooks' => Book::where('status', 'available')->count(),
            'borrowedBooks' => Book::where('status', 'borrowed')->count(),
            'activeBorrows' => BorrowRecord::where('status', 'borrowed')->count(),
            'overdueBorrows' => BorrowRecord::whereNull('returned_at')->where('due_at', '<', now())->count(),
            'totalMembers' => Member::where('status', 'active')->count(),
            'totalAuthors' => Author::count(),
            'totalCategories' => Category::count(),
            'totalPublishers' => Publisher::count(),
        ];

        $booksByStatus = Book::selectRaw('status, COUNT(*) as count')
            ->groupBy('status')
            ->pluck('count', 'status');

        $recentBorrows = BorrowRecord::with(['book', 'member'])
            ->latest()
            ->take(10)
            ->get();

        return view('library::reports.index', compact('stats', 'booksByStatus', 'recentBorrows'));
    }

    public function borrowed(Request $request)
    {
        $query = BorrowRecord::with(['book', 'member'])
            ->whereNull('returned_at');

        if ($request->filled('member_id')) {
            $query->where('member_id', $request->member_id);
        }
        if ($request->filled('book_id')) {
            $query->where('book_id', $request->book_id);
        }

        $borrows = $query->orderBy('due_at')->paginate(25)->withQueryString();

        $members = Member::where('status', 'active')->orderBy('name')->get();
        $books = Book::orderBy('title')->get();

        return view('library::reports.borrowed', compact('borrows', 'members', 'books'));
    }

    public function overdue(Request $request)
    {
        $query = BorrowRecord::with(['book', 'member'])
            ->whereNull('returned_at')
            ->where('due_at', '<', now());

        if ($request->filled('days_overdue')) {
            $query->where('due_at', '<', now()->subDays($request->days_overdue));
        }

        $borrows = $query->orderBy('due_at')->paginate(25)->withQueryString();

        return view('library::reports.overdue', compact('borrows'));
    }

    public function mostBorrowed(Request $request)
    {
        $query = Book::withCount(['borrowRecords' => function ($q) {
            if (request('period') === 'month') {
                $q->whereMonth('created_at', now()->month)
                  ->whereYear('created_at', now()->year);
            } elseif (request('period') === 'year') {
                $q->whereYear('created_at', now()->year);
            }
        }])->orderByDesc('borrow_records_count');

        if ($request->filled('category_id')) {
            $query->where('category_id', $request->category_id);
        }

        $books = $query->paginate(25)->withQueryString();

        $categories = Category::orderBy('name')->get();

        return view('library::reports.most-borrowed', compact('books', 'categories'));
    }
}
