<?php

namespace Modules\Library\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    public function index() {
        return view('library::reports.index');
    }

    public function borrowed() {
        $borrowedBooks = collect([
            (object)[
                'id' => 1,
                'book_title' => 'The Great Gatsby',
                'member_name' => 'John Doe',
                'borrow_date' => '2024-12-01',
                'due_date' => '2024-12-15',
                'status' => 'borrowed'
            ],
            (object)[
                'id' => 2,
                'book_title' => 'To Kill a Mockingbird',
                'member_name' => 'Jane Smith',
                'borrow_date' => '2024-12-05',
                'due_date' => '2024-12-19',
                'status' => 'borrowed'
            ],
            (object)[
                'id' => 3,
                'book_title' => '1984',
                'member_name' => 'Mike Johnson',
                'borrow_date' => '2024-12-10',
                'due_date' => '2024-12-24',
                'status' => 'borrowed'
            ]
        ]);

        return view('library::reports.borrowed', compact('borrowedBooks'));
    }

    public function overdue() {
        $overdueBooks = collect([
            (object)[
                'id' => 1,
                'book_title' => 'Pride and Prejudice',
                'member_name' => 'Sarah Wilson',
                'borrow_date' => '2024-11-15',
                'due_date' => '2024-11-30',
                'days_overdue' => 15
            ],
            (object)[
                'id' => 2,
                'book_title' => 'The Catcher in the Rye',
                'member_name' => 'David Brown',
                'borrow_date' => '2024-11-20',
                'due_date' => '2024-12-05',
                'days_overdue' => 10
            ]
        ]);

        return view('library::reports.overdue', compact('overdueBooks'));
    }

    public function mostBorrowed() {
        $mostBorrowedBooks = collect([
            (object)[
                'id' => 1,
                'book_title' => 'The Great Gatsby',
                'author' => 'F. Scott Fitzgerald',
                'borrow_count' => 25,
                'total_copies' => 3
            ],
            (object)[
                'id' => 2,
                'book_title' => 'To Kill a Mockingbird',
                'author' => 'Harper Lee',
                'borrow_count' => 22,
                'total_copies' => 2
            ],
            (object)[
                'id' => 3,
                'book_title' => '1984',
                'author' => 'George Orwell',
                'borrow_count' => 18,
                'total_copies' => 4
            ]
        ]);

        return view('library::reports.most-borrowed', compact('mostBorrowedBooks'));
    }
} 