<?php

namespace Modules\Library\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Library\Models\Book;
use Modules\Library\Models\Member;
use Modules\Library\Models\Borrow;
use Barryvdh\DomPDF\Facade\Pdf;

class StatisticsController extends Controller
{
    public function index(Request $request)
    {
        $totalBooks = Book::count();
        $activeMembers = Member::where('is_active', true)->count();
        $totalBorrowings = Borrow::count();
        $overdueBooks = Borrow::where('status', 'borrowed')
                              ->where('due_at', '<', now())->count();
        
        $borrowingsByMonth = Borrow::selectRaw('MONTH(created_at) as month, COUNT(*) as count')
                                  ->whereYear('created_at', date('Y'))
                                  ->groupBy('month')
                                  ->get();
        
        $overdueAnalysis = [
            '1_week' => Borrow::where('status', 'borrowed')
                             ->where('due_at', '>=', now()->subDays(7))
                             ->where('due_at', '<', now())->count(),
            '1_month' => Borrow::where('status', 'borrowed')
                              ->where('due_at', '>=', now()->subDays(30))
                              ->where('due_at', '<', now()->subDays(7))->count(),
            '3_months' => Borrow::where('status', 'borrowed')
                               ->where('due_at', '<', now()->subDays(30))->count(),
        ];
        
        $averageBorrowingDuration = Borrow::where('status', 'returned')
                                         ->whereNotNull('returned_at')
                                         ->whereNotNull('borrowed_at')
                                         ->avg(\DB::raw('DATEDIFF(returned_at, borrowed_at)'));
        
        $totalFines = Borrow::where('status', 'borrowed')
                           ->where('due_at', '<', now())
                           ->sum('fine_amount') ?? 0;

        if ($request->has('export') && $request->export === 'pdf') {
            $pdf = Pdf::loadView('library::statistics.index', compact(
                'totalBooks', 'activeMembers', 'totalBorrowings', 'overdueBooks',
                'borrowingsByMonth', 'overdueAnalysis', 'averageBorrowingDuration', 'totalFines'
            ));
            return $pdf->download('library-statistics-' . date('Y-m-d') . '.pdf');
        }

        return view('library::statistics.index', compact(
            'totalBooks', 'activeMembers', 'totalBorrowings', 'overdueBooks',
            'borrowingsByMonth', 'overdueAnalysis', 'averageBorrowingDuration', 'totalFines'
        ));
    }

    public function books(Request $request)
    {
        $totalBooks = Book::count();
        $availableBooks = Book::where('available_copies', '>', 0)->count();
        $borrowedBooks = Book::where('available_copies', '<', 'copies')->count();
        $totalCategories = \DB::table('categories')->count();
        
        $booksByCategory = Book::with('category')
                              ->get()
                              ->groupBy('category.name')
                              ->map->count();
        
        $topAuthors = Book::with('author')
                         ->get()
                         ->groupBy('author.name')
                         ->map->count()
                         ->sortDesc()
                         ->take(10);

        if ($request->has('export') && $request->export === 'pdf') {
            $pdf = Pdf::loadView('library::statistics.books', compact(
                'totalBooks', 'availableBooks', 'borrowedBooks', 'totalCategories',
                'booksByCategory', 'topAuthors'
            ));
            return $pdf->download('library-books-statistics-' . date('Y-m-d') . '.pdf');
        }

        return view('library::statistics.books', compact(
            'totalBooks', 'availableBooks', 'borrowedBooks', 'totalCategories',
            'booksByCategory', 'topAuthors'
        ));
    }

    public function members(Request $request)
    {
        $totalMembers = Member::count();
        $activeMembers = Member::where('is_active', true)->count();
        $inactiveMembers = Member::where('is_active', false)->count();
        $totalMemberships = \DB::table('memberships')->count();
        
        $membersByType = [
            'student' => Member::whereNotNull('student_id')->count(),
            'staff' => Member::whereNotNull('staff_id')->count(),
        ];
        
        $recentMembers = Member::orderBy('created_at', 'desc')->take(10)->get();

        if ($request->has('export') && $request->export === 'pdf') {
            $pdf = Pdf::loadView('library::statistics.members', compact(
                'totalMembers', 'activeMembers', 'inactiveMembers', 'totalMemberships',
                'membersByType', 'recentMembers'
            ));
            return $pdf->download('library-members-statistics-' . date('Y-m-d') . '.pdf');
        }

        return view('library::statistics.members', compact(
            'totalMembers', 'activeMembers', 'inactiveMembers', 'totalMemberships',
            'membersByType', 'recentMembers'
        ));
    }

    public function borrowings(Request $request)
    {
        $totalBorrowings = Borrow::count();
        $activeBorrowings = Borrow::where('status', 'borrowed')->count();
        $returnedBooks = Borrow::where('status', 'returned')->count();
        $overdueBooks = Borrow::where('status', 'borrowed')
                             ->where('due_at', '<', now())->count();
        
        $borrowingsByMonth = Borrow::selectRaw('MONTH(created_at) as month, COUNT(*) as count')
                                  ->whereYear('created_at', date('Y'))
                                  ->groupBy('month')
                                  ->get();
        
        $averageBorrowingDuration = Borrow::where('status', 'returned')
                                         ->whereNotNull('returned_at')
                                         ->whereNotNull('borrowed_at')
                                         ->avg(\DB::raw('DATEDIFF(returned_at, borrowed_at)')) ?? 0;

        if ($request->has('export') && $request->export === 'pdf') {
            $pdf = Pdf::loadView('library::statistics.borrowings', compact(
                'totalBorrowings', 'activeBorrowings', 'returnedBooks', 'overdueBooks',
                'borrowingsByMonth', 'averageBorrowingDuration'
            ));
            return $pdf->download('library-borrowings-statistics-' . date('Y-m-d') . '.pdf');
        }

        return view('library::statistics.borrowings', compact(
            'totalBorrowings', 'activeBorrowings', 'returnedBooks', 'overdueBooks',
            'borrowingsByMonth', 'averageBorrowingDuration'
        ));
    }

    public function overdue(Request $request)
    {
        $totalOverdue = Borrow::where('status', 'borrowed')
                             ->where('due_at', '<', now())->count();
        
        $overdue1Week = Borrow::where('status', 'borrowed')
                             ->where('due_at', '>=', now()->subDays(7))
                             ->where('due_at', '<', now())->count();
        
        $overdue1Month = Borrow::where('status', 'borrowed')
                              ->where('due_at', '>=', now()->subDays(30))
                              ->where('due_at', '<', now()->subDays(7))->count();
        
        $overdue3Months = Borrow::where('status', 'borrowed')
                               ->where('due_at', '<', now()->subDays(30))->count();
        
        $topOverdueMembers = Member::withCount(['borrows' => function($query) {
            $query->where('status', 'borrowed')
                  ->where('due_at', '<', now());
        }])->having('borrows_count', '>', 0)
           ->orderBy('borrows_count', 'desc')
           ->take(10)
           ->get();
        
        $mostOverdueBooks = Borrow::with('book')
                                 ->where('status', 'borrowed')
                                 ->where('due_at', '<', now())
                                 ->get()
                                 ->map(function($borrow) {
                                     $borrow->days_overdue = now()->diffInDays($borrow->due_at);
                                     return $borrow;
                                 })
                                 ->sortByDesc('days_overdue')
                                 ->take(10);

        if ($request->has('export') && $request->export === 'pdf') {
            $pdf = Pdf::loadView('library::statistics.overdue', compact(
                'totalOverdue', 'overdue1Week', 'overdue1Month', 'overdue3Months',
                'topOverdueMembers', 'mostOverdueBooks'
            ));
            return $pdf->download('library-overdue-statistics-' . date('Y-m-d') . '.pdf');
        }

        return view('library::statistics.overdue', compact(
            'totalOverdue', 'overdue1Week', 'overdue1Month', 'overdue3Months',
            'topOverdueMembers', 'mostOverdueBooks'
        ));
    }
}
