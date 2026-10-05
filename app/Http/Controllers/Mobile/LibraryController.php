<?php

namespace App\Http\Controllers\Mobile;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Modules\Library\Models\Book;
use Modules\Library\Models\BorrowRecord;
use Modules\Library\Models\Member;
use Modules\Academic\Models\Student;

class LibraryController extends Controller
{
    public function books(Request $request)
    {
        $user = $request->user();
        $search = $request->get('search');
        $category = $request->get('category');

        $query = Book::where('school_id', $user->school_id)
            ->where('available_copies', '>', 0);

        if ($search) {
            $query->where(function($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('author', 'like', "%{$search}%")
                  ->orWhere('isbn', 'like', "%{$search}%");
            });
        }

        if ($category) {
            $query->where('category', $category);
        }

        $books = $query->orderBy('title')
            ->get()
            ->map(function($book) {
                return [
                    'id' => $book->id,
                    'title' => $book->title,
                    'author' => $book->author,
                    'isbn' => $book->isbn,
                    'category' => $book->category,
                    'copies' => $book->copies,
                    'available_copies' => $book->available_copies,
                    'publisher' => $book->publisher,
                    'publication_year' => $book->publication_year,
                    'description' => $book->description,
                    'cover_image' => $book->cover_image,
                ];
            });

        return response()->json(['books' => $books]);
    }

    public function borrowed(Request $request)
    {
        $user = $request->user();
        $student = Student::where('user_id', $user->id)->first();
        
        if (!$student) {
            // Android compatibility: return empty borrowed list instead of 404
            return response()->json(['borrowed' => []]);
        }

        $member = Member::where('student_id', $student->id)->first();
        
        if (!$member) {
            // Android compatibility: return empty borrowed list instead of 404
            return response()->json(['borrowed' => []]);
        }

        $borrowed = BorrowRecord::where('member_id', $member->id)
            ->where('returned_at', null)
            ->with('book')
            ->orderBy('borrowed_at', 'desc')
            ->get()
            ->map(function($record) {
                $isOverdue = $record->due_date < now();
                return [
                    'id' => $record->id,
                    'book_title' => $record->book->title,
                    'book_author' => $record->book->author,
                    'borrowed_at' => $record->borrowed_at,
                    'due_date' => $record->due_date,
                    'is_overdue' => $isOverdue,
                    'days_overdue' => $isOverdue ? now()->diffInDays($record->due_date) : 0,
                    'fine_amount' => $isOverdue ? $this->calculateFine($record->due_date) : 0,
                ];
            });

        return response()->json(['borrowed' => $borrowed]);
    }

    public function borrow(Request $request)
    {
        $request->validate([
            'book_id' => 'required|exists:books,id',
        ]);

        $user = $request->user();
        $student = Student::where('user_id', $user->id)->first();
        
        if (!$student) {
            return response()->json(['message' => 'Student not found'], 404);
        }

        $member = Member::where('student_id', $student->id)->first();
        
        if (!$member) {
            return response()->json(['message' => 'Not a library member'], 404);
        }

        $book = Book::findOrFail($request->book_id);
        
        if ($book->available_copies <= 0) {
            return response()->json(['message' => 'Book not available'], 422);
        }

        // Check if student has overdue books
        $overdueBooks = BorrowRecord::where('member_id', $member->id)
            ->where('returned_at', null)
            ->where('due_date', '<', now())
            ->count();

        if ($overdueBooks > 0) {
            return response()->json(['message' => 'Cannot borrow: You have overdue books'], 422);
        }

        // Check borrowing limit
        $currentBorrowed = BorrowRecord::where('member_id', $member->id)
            ->where('returned_at', null)
            ->count();

        if ($currentBorrowed >= 3) { // Assuming limit is 3 books
            return response()->json(['message' => 'Borrowing limit reached'], 422);
        }

        // Create borrow record
        $borrowRecord = BorrowRecord::create([
            'member_id' => $member->id,
            'book_id' => $book->id,
            'borrowed_at' => now(),
            'due_date' => now()->addDays(14), // 2 weeks loan period
            'returned_at' => null,
        ]);

        // Update book availability
        $book->decrement('available_copies');

        return response()->json([
            'message' => 'Book borrowed successfully',
            'borrow_record' => [
                'id' => $borrowRecord->id,
                'book_title' => $book->title,
                'due_date' => $borrowRecord->due_date->format('Y-m-d'),
                'return_by' => $borrowRecord->due_date->diffForHumans(),
            ]
        ]);
    }

    public function return(Request $request)
    {
        $request->validate([
            'borrow_id' => 'required|exists:borrow_records,id',
        ]);

        $user = $request->user();
        $student = Student::where('user_id', $user->id)->first();
        
        if (!$student) {
            return response()->json(['message' => 'Student not found'], 404);
        }

        $member = Member::where('student_id', $student->id)->first();
        
        if (!$member) {
            return response()->json(['message' => 'Not a library member'], 404);
        }

        $borrowRecord = BorrowRecord::where('id', $request->borrow_id)
            ->where('member_id', $member->id)
            ->where('returned_at', null)
            ->first();

        if (!$borrowRecord) {
            return response()->json(['message' => 'Borrow record not found'], 404);
        }

        // Update borrow record
        $borrowRecord->update([
            'returned_at' => now(),
        ]);

        // Update book availability
        $borrowRecord->book->increment('available_copies');

        $fine = 0;
        if ($borrowRecord->due_date < now()) {
            $fine = $this->calculateFine($borrowRecord->due_date);
        }

        return response()->json([
            'message' => 'Book returned successfully',
            'fine' => $fine,
            'return_date' => now()->format('Y-m-d H:i:s'),
        ]);
    }

    private function calculateFine($dueDate)
    {
        $daysOverdue = now()->diffInDays($dueDate);
        return $daysOverdue * 0.50; // $0.50 per day
    }
}
