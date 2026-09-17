<?php

namespace Modules\Library\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Modules\Library\Models\BorrowRecord;
use App\Models\Modules\Library\Models\Book;
use App\Models\Modules\Library\Models\Member;
use Illuminate\Http\Request;

class BorrowController extends Controller
{
    public function index()
    {
        $borrows = BorrowRecord::with(['book', 'member'])->orderByDesc('borrowed_at')->paginate(25);
        return view('library::borrows.index', compact('borrows'));
    }

    public function create()
    {
        $books = Book::orderBy('title')->get();
        $members = Member::orderBy('name')->get();
        return view('library::borrows.create', compact('books', 'members'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'book_id' => 'required|exists:books,id',
            'member_id' => 'required|exists:members,id',
            'borrowed_at' => 'required|date',
            'due_at' => 'required|date|after:borrowed_at',
        ]);
        $data['status'] = 'borrowed';
        BorrowRecord::create($data);
        return redirect()->route('library.borrows.index')->with('success', 'Borrow record created.');
    }

    public function show($id)
    {
        $borrow = BorrowRecord::with(['book', 'member'])->findOrFail($id);
        return view('library::borrows.show', compact('borrow'));
    }

    public function edit($id)
    {
        $borrow = BorrowRecord::findOrFail($id);
        $books = Book::orderBy('title')->get();
        $members = Member::orderBy('name')->get();
        return view('library::borrows.edit', compact('borrow', 'books', 'members'));
    }

    public function update(Request $request, $id)
    {
        $borrow = BorrowRecord::findOrFail($id);
        $data = $request->validate([
            'status' => 'required|in:borrowed,returned,overdue',
            'returned_at' => 'nullable|date',
        ]);
        if ($data['status'] === 'returned' && empty($data['returned_at'])) {
            $data['returned_at'] = now();
        }
        $borrow->update($data);
        return redirect()->route('library.borrows.index')->with('success', 'Borrow record updated.');
    }

    public function destroy($id)
    {
        BorrowRecord::findOrFail($id)->delete();
        return redirect()->route('library.borrows.index')->with('success', 'Borrow record deleted.');
    }
}
