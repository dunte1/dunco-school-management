<?php

namespace Modules\API\Http\Controllers\Mobile;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use App\Models\Modules\Library\Models\Book;
use App\Models\Modules\Library\Models\BorrowRecord;
use App\Models\Modules\Library\Models\Author;
use App\Models\Modules\Library\Models\Category;

class LibraryController extends Controller
{
    public function getBooks(Request $request): JsonResponse
    {
        try {
            $query = Book::with(['author', 'category', 'publisher']);

            if ($request->filled('search')) {
                $search = $request->search;
                $query->where(function ($q) use ($search) {
                    $q->where('title', 'like', "%{$search}%")
                      ->orWhere('isbn', 'like', "%{$search}%")
                      ->orWhere('isbn_13', 'like', "%{$search}%");
                });
            }

            if ($request->filled('category_id')) {
                $query->where('category_id', $request->category_id);
            }

            if ($request->filled('author_id')) {
                $query->where('author_id', $request->author_id);
            }

            if ($request->filled('status')) {
                $query->where('status', $request->status);
            }

            $books = $query->orderBy('title')->paginate($request->get('per_page', 25));

            return response()->json([
                'success' => true,
                'message' => 'Books retrieved successfully',
                'data' => $books
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve books: ' . $e->getMessage()
            ], 500);
        }
    }

    public function getBook($id): JsonResponse
    {
        try {
            $book = Book::with(['author', 'category', 'publisher', 'borrowRecords.member'])->find($id);

            if (!$book) {
                return response()->json([
                    'success' => false,
                    'message' => 'Book not found'
                ], 404);
            }

            return response()->json([
                'success' => true,
                'message' => 'Book retrieved successfully',
                'data' => $book
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve book: ' . $e->getMessage()
            ], 500);
        }
    }

    public function searchBooks(Request $request): JsonResponse
    {
        try {
            $query = $request->input('q', '');

            if (empty($query)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Search query is required'
                ], 422);
            }

            $books = Book::with(['author', 'category'])
                ->where('title', 'like', "%{$query}%")
                ->orWhere('isbn', 'like', "%{$query}%")
                ->orWhere('isbn_13', 'like', "%{$query}%")
                ->orWhereHas('author', function ($q) use ($query) {
                    $q->where('name', 'like', "%{$query}%");
                })
                ->orderBy('title')
                ->paginate(25);

            return response()->json([
                'success' => true,
                'message' => 'Search results retrieved successfully',
                'data' => $books
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to search books: ' . $e->getMessage()
            ], 500);
        }
    }

    public function getBorrowings(Request $request): JsonResponse
    {
        try {
            $userId = Auth::id();

            $query = BorrowRecord::with(['book', 'book.author'])
                ->where('member_id', $userId);

            if ($request->filled('status')) {
                $query->where('status', $request->status);
            }

            $borrowings = $query->orderByDesc('borrowed_at')->paginate(25);

            // Calculate summary
            $activeCount = BorrowRecord::where('member_id', $userId)
                ->where('status', 'borrowed')
                ->count();
            $overdueCount = BorrowRecord::where('member_id', $userId)
                ->where('status', 'borrowed')
                ->where('due_at', '<', now())
                ->count();

            return response()->json([
                'success' => true,
                'message' => 'Borrowings retrieved successfully',
                'data' => [
                    'borrowings' => $borrowings,
                    'summary' => [
                        'active_count' => $activeCount,
                        'overdue_count' => $overdueCount
                    ]
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve borrowings: ' . $e->getMessage()
            ], 500);
        }
    }

    public function getCategories(): JsonResponse
    {
        try {
            $categories = Category::orderBy('name')->get();

            return response()->json([
                'success' => true,
                'message' => 'Categories retrieved successfully',
                'data' => $categories
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve categories: ' . $e->getMessage()
            ], 500);
        }
    }

    public function getAuthors(): JsonResponse
    {
        try {
            $authors = Author::orderBy('name')->get();

            return response()->json([
                'success' => true,
                'message' => 'Authors retrieved successfully',
                'data' => $authors
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve authors: ' . $e->getMessage()
            ], 500);
        }
    }
}
