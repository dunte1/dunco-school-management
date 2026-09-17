<?php

namespace Modules\Library\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Modules\Library\Models\BorrowRecord;
use App\Models\Modules\Library\Models\Book;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    public function index()
    {
        $stats = [
            'totalBooks' => Book::count(),
            'activeBorrows' => BorrowRecord::where('status', 'borrowed')->count(),
            'overdueBorrows' => BorrowRecord::whereNull('returned_at')->where('due_at', '<', now())->count(),
        ];
        return view('library::reports.index', compact('stats'));
    }

    public function borrowed()
    {
        $borrows = BorrowRecord::with(['book','member'])->where('status', 'borrowed')->orderBy('due_at')->paginate(25);
        return view('library::reports.borrowed', compact('borrows'));
    }

    public function overdue()
    {
        $borrows = BorrowRecord::with(['book','member'])->whereNull('returned_at')->where('due_at', '<', now())->orderBy('due_at')->paginate(25);
        return view('library::reports.overdue', compact('borrows'));
    }

    public function mostBorrowed()
    {
        $books = Book::withCount('borrowRecords')->orderByDesc('borrow_records_count')->paginate(25);
        return view('library::reports.most-borrowed', compact('books'));
    }
}
