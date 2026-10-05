<?php

namespace Modules\Library\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Library\Models\Borrow;

class BorrowingController extends Controller
{
    public function index(Request $request)
    {
        $query = Borrow::with(['book', 'member']);

        // Filter by status
        if ($request->has('status') && $request->status) {
            $query->where('status', $request->status);
        }

        // Filter by member
        if ($request->has('member_id') && $request->member_id) {
            $query->where('member_id', $request->member_id);
        }

        // Search by book title or member name
        if ($request->has('search') && $request->search) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->whereHas('book', function($bookQuery) use ($search) {
                    $bookQuery->where('title', 'like', "%{$search}%");
                })->orWhereHas('member', function($memberQuery) use ($search) {
                    $memberQuery->where('name', 'like', "%{$search}%");
                });
            });
        }

        $borrowings = $query->orderBy('created_at', 'desc')->paginate(15);
        $members = \Modules\Library\Models\Member::all();

        return view('library::borrowings.index', compact('borrowings', 'members'));
    }

    public function create()
    {
        $books = \Modules\Library\Models\Book::where('available_copies', '>', 0)->get();
        $members = \Modules\Library\Models\Member::all();
        return view('library::borrowings.create', compact('books', 'members'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'book_id' => 'required|exists:library_books,id',
            'member_id' => 'required|exists:library_members,id',
            'borrow_date' => 'required|date',
            'due_at' => 'required|date|after:borrow_date',
            'notes' => 'nullable|string'
        ]);

        try {
            $data = $request->all();
            $data['status'] = 'borrowed';
            $data['borrowed_by'] = auth()->id();

            Borrow::create($data);

            // Update book available copies
            $book = \Modules\Library\Models\Book::find($request->book_id);
            $book->decrement('available_copies');

            return redirect()->route('library.borrowings.index')
                           ->with('success', 'Book borrowed successfully.');

        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'Failed to borrow book: ' . $e->getMessage()]);
        }
    }

    public function show($id)
    {
        $borrowing = Borrow::with(['book', 'member'])->findOrFail($id);
        return view('library::borrowings.show', compact('borrowing'));
    }

    public function edit($id)
    {
        $borrowing = Borrow::findOrFail($id);
        $books = \Modules\Library\Models\Book::all();
        $members = \Modules\Library\Models\Member::all();
        return view('library::borrowings.edit', compact('borrowing', 'books', 'members'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'book_id' => 'required|exists:library_books,id',
            'member_id' => 'required|exists:library_members,id',
            'borrow_date' => 'required|date',
            'due_at' => 'required|date|after:borrow_date',
            'notes' => 'nullable|string'
        ]);

        try {
            $borrowing = Borrow::findOrFail($id);
            $borrowing->update($request->all());

            return redirect()->route('library.borrowings.index')
                           ->with('success', 'Borrowing updated successfully.');

        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'Failed to update borrowing: ' . $e->getMessage()]);
        }
    }

    public function destroy($id)
    {
        try {
            $borrowing = Borrow::findOrFail($id);
            $borrowing->delete();

            return redirect()->route('library.borrowings.index')
                           ->with('success', 'Borrowing deleted successfully.');

        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'Failed to delete borrowing: ' . $e->getMessage()]);
        }
    }
}
