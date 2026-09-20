<?php

namespace Modules\Library\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Modules\Library\Models\BorrowRecord;
use App\Models\Modules\Library\Models\Book;
use App\Models\Modules\Library\Models\Member;
use Illuminate\Http\Request;

class BorrowController extends Controller
{
    public function index(Request $request)
    {
        $query = BorrowRecord::with(['book', 'member']);

        if ($request->filled('search')) {
            $search = $request->search;
            $query->whereHas('book', function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('isbn', 'like', "%{$search}%");
            })->orWhereHas('member', function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('membership_number', 'like', "%{$search}%");
            });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $borrows = $query->orderByDesc('created_at')->paginate(25)->withQueryString();

        return view('library::borrows.index', compact('borrows'));
    }

    public function create()
    {
        $books = Book::where('status', 'available')->orderBy('title')->get();
        $members = Member::where('status', 'active')->orderBy('name')->get();

        return view('library::borrows.create', compact('books', 'members'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'book_id' => 'required|exists:books,id',
            'member_id' => 'required|exists:members,id',
            'borrowed_at' => 'required|date',
            'due_at' => 'required|date|after_or_equal:borrowed_at',
            'notes' => 'nullable|string',
        ]);

        $book = Book::findOrFail($data['book_id']);
        if ($book->status !== 'available') {
            return redirect()->back()->withInput()
                ->with('error', 'This book is not available for borrowing.');
        }

        $activeBorrows = BorrowRecord::where('member_id', $data['member_id'])
            ->whereNull('returned_at')
            ->count();
        if ($activeBorrows >= 5) {
            return redirect()->back()->withInput()
                ->with('error', 'Member has reached the maximum number of active borrows (5).');
        }

        $data['status'] = 'borrowed';
        BorrowRecord::create($data);

        $book->update(['status' => 'borrowed']);

        return redirect()->route('library.borrows.index')->with('success', 'Borrow record created successfully.');
    }

    public function show(BorrowRecord $borrow)
    {
        $borrow->load(['book.author', 'book.category', 'member']);

        return view('library::borrows.show', compact('borrow'));
    }

    public function edit(BorrowRecord $borrow)
    {
        $borrow->load('book', 'member');
        $books = Book::orderBy('title')->get();
        $members = Member::where('status', 'active')->orderBy('name')->get();

        return view('library::borrows.edit', compact('borrow', 'books', 'members'));
    }

    public function update(Request $request, BorrowRecord $borrow)
    {
        $data = $request->validate([
            'status' => 'required|in:borrowed,returned,overdue',
            'returned_at' => 'nullable|date',
            'notes' => 'nullable|string',
        ]);

        if ($data['status'] === 'returned' && empty($data['returned_at'])) {
            $data['returned_at'] = now();
        }

        $borrow->update($data);

        if ($data['status'] === 'returned') {
            $book = $borrow->book;
            if ($book) {
                $activeBorrows = BorrowRecord::where('book_id', $book->id)
                    ->whereNull('returned_at')
                    ->count();
                if ($activeBorrows === 0) {
                    $book->update(['status' => 'available']);
                }
            }
        }

        return redirect()->route('library.borrows.index')->with('success', 'Borrow record updated successfully.');
    }

    public function destroy(BorrowRecord $borrow)
    {
        if ($borrow->status === 'borrowed' && is_null($borrow->returned_at)) {
            return redirect()->route('library.borrows.index')
                ->with('error', 'Cannot delete an active borrow record. Return the book first.');
        }

        $borrow->delete();

        return redirect()->route('library.borrows.index')->with('success', 'Borrow record deleted successfully.');
    }
}
