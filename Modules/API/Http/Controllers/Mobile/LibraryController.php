<?php

namespace Modules\API\Http\Controllers\Mobile;

use Illuminate\Http\JsonResponse;
use Illuminate\Routing\Controller;

class LibraryController extends Controller
{
    public function getBooks(): JsonResponse
    {
        $books = \App\Models\Modules\Library\Models\Book::orderBy('title')->paginate(25);
        return response()->json(['success' => true, 'data' => $books]);
    }

    public function getBook($id): JsonResponse
    {
        $book = \App\Models\Modules\Library\Models\Book::find($id);
        if (!$book) return response()->json(['message' => 'Book not found'], 404);
        return response()->json(['success' => true, 'data' => $book]);
    }

    public function searchBooks(Request $request): JsonResponse
    {
        $query = $request->input('q', '');
        $books = \App\Models\Modules\Library\Models\Book::where('title', 'like', "%{$query}%")
            ->orWhere('author', 'like', "%{$query}%")
            ->paginate(25);
        return response()->json(['success' => true, 'data' => $books]);
    }

    public function getBorrowings(): JsonResponse
    {
        $borrowings = \App\Models\Modules\Library\Models\BorrowRecord::with('book')
            ->where('member_id', auth()->id())
            ->orderByDesc('borrowed_at')
            ->paginate(25);
        return response()->json(['success' => true, 'data' => $borrowings]);
    }
}
