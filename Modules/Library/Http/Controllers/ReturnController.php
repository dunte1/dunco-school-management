<?php

namespace Modules\Library\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Library\Models\Borrow;

class ReturnController extends Controller
{
    public function index(Request $request)
    {
        $query = Borrow::with(['book', 'member'])->where('status', 'returned');

        // Filter by member
        if ($request->has('member_id') && $request->member_id) {
            $query->where('member_id', $request->member_id);
        }

        // Filter by return date range
        if ($request->has('return_date_from') && $request->return_date_from) {
            $query->whereDate('return_date', '>=', $request->return_date_from);
        }

        if ($request->has('return_date_to') && $request->return_date_to) {
            $query->whereDate('return_date', '<=', $request->return_date_to);
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

        $returns = $query->orderBy('return_date', 'desc')->paginate(15);
        $members = \Modules\Library\Models\Member::all();

        return view('library::returns.index', compact('returns', 'members'));
    }

    public function create()
    {
        $borrowedBooks = Borrow::with(['book', 'member'])
            ->where('status', 'borrowed')
            ->where('due_at', '<=', now()->addDays(30))
            ->get();
        
        return view('library::returns.create', compact('borrowedBooks'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'borrow_id' => 'required|exists:borrows,id',
            'return_date' => 'required|date',
            'condition' => 'required|in:good,fair,poor,damaged',
            'notes' => 'nullable|string',
            'fine_amount' => 'nullable|numeric|min:0'
        ]);

        try {
            $borrow = Borrow::findOrFail($request->borrow_id);
            
            // Check if book is overdue
            $isOverdue = $borrow->due_at < $request->return_date;
            
            $borrow->update([
                'status' => 'returned',
                'return_date' => $request->return_date,
                'condition' => $request->condition,
                'notes' => $request->notes,
                'fine_amount' => $isOverdue ? ($request->fine_amount ?? 0) : 0,
                'returned_by' => auth()->id()
            ]);

            // Update book available copies
            $book = $borrow->book;
            $book->increment('available_copies');

            return redirect()->route('library.returns.index')
                           ->with('success', 'Book returned successfully.');

        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'Failed to return book: ' . $e->getMessage()]);
        }
    }

    public function show($id)
    {
        $return = Borrow::with(['book', 'member'])->findOrFail($id);
        return view('library::returns.show', compact('return'));
    }

    public function edit($id)
    {
        $return = Borrow::findOrFail($id);
        return view('library::returns.edit', compact('return'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'return_date' => 'required|date',
            'condition' => 'required|in:good,fair,poor,damaged',
            'notes' => 'nullable|string',
            'fine_amount' => 'nullable|numeric|min:0'
        ]);

        try {
            $return = Borrow::findOrFail($id);
            $return->update($request->all());

            return redirect()->route('library.returns.index')
                           ->with('success', 'Return record updated successfully.');

        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'Failed to update return record: ' . $e->getMessage()]);
        }
    }

    public function destroy($id)
    {
        try {
            $return = Borrow::findOrFail($id);
            $return->delete();

            return redirect()->route('library.returns.index')
                           ->with('success', 'Return record deleted successfully.');

        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'Failed to delete return record: ' . $e->getMessage()]);
        }
    }
}
