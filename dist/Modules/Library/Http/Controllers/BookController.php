<?php

namespace Modules\Library\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Inertia\Inertia;

class BookController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = \App\Models\Modules\Library\Models\Book::with(['author', 'category', 'publisher']);
        // Search
        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('isbn', 'like', "%{$search}%")
                  ->orWhereHas('author', function($q2) use ($search) {
                      $q2->where('name', 'like', "%{$search}%");
                  });
            });
        }
        // Filters
        if ($request->filled('category')) {
            $query->where('category_id', $request->input('category'));
        }
        if ($request->filled('author')) {
            $query->where('author_id', $request->input('author'));
        }
        if ($request->filled('publisher')) {
            $query->where('publisher_id', $request->input('publisher'));
        }
        if ($request->filled('status')) {
            $query->where('status', $request->input('status'));
        }
        // Pagination
        $books = $query->orderBy('created_at', 'desc')->paginate(10)->withQueryString();
        $categories = \App\Models\Modules\Library\Models\Category::all();
        $authors = \App\Models\Modules\Library\Models\Author::all();
        $publishers = \App\Models\Modules\Library\Models\Publisher::all();
        return view('library::books.index', compact('books', 'categories', 'authors', 'publishers'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // You may want to fetch categories, authors, publishers for dropdowns
        $categories = \App\Models\Modules\Library\Models\Category::all();
        $authors = \App\Models\Modules\Library\Models\Author::all();
        $publishers = \App\Models\Modules\Library\Models\Publisher::all();
        return view('library::books.create', compact('categories', 'authors', 'publishers'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'isbn' => 'required|string|max:255|unique:books,isbn',
            'barcode' => 'nullable|string|max:255|unique:books,barcode',
            'edition' => 'nullable|string|max:255',
            'year' => 'nullable|integer',
            'category_id' => 'nullable|exists:categories,id',
            'author_id' => 'nullable|exists:authors,id',
            'publisher_id' => 'nullable|exists:publishers,id',
            'cover_image' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'status' => 'nullable|in:available,borrowed,reserved,lost',
            'ebook_file' => 'nullable|file|mimes:pdf,epub,mp3|max:20480', // 20MB max
        ]);

        if ($request->hasFile('ebook_file')) {
            $file = $request->file('ebook_file');
            $path = $file->store('ebooks', 'public');
            $validated['ebook_file_path'] = $path;
        }

        \App\Models\Modules\Library\Models\Book::create($validated);
        return redirect()->route('library.books.index')->with('success', 'Book added successfully!');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $book = \App\Models\Modules\Library\Models\Book::with(['author', 'category', 'publisher'])->findOrFail($id);
        return view('library::books.show', compact('book'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $book = \App\Models\Modules\Library\Models\Book::findOrFail($id);
        $categories = \App\Models\Modules\Library\Models\Category::all();
        $authors = \App\Models\Modules\Library\Models\Author::all();
        $publishers = \App\Models\Modules\Library\Models\Publisher::all();
        return view('library::books.edit', compact('book', 'categories', 'authors', 'publishers'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $book = \App\Models\Modules\Library\Models\Book::findOrFail($id);
        
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'isbn' => 'required|string|max:255|unique:books,isbn,' . $id,
            'barcode' => 'nullable|string|max:255|unique:books,barcode,' . $id,
            'edition' => 'nullable|string|max:255',
            'year' => 'nullable|integer',
            'category_id' => 'nullable|exists:categories,id',
            'author_id' => 'nullable|exists:authors,id',
            'publisher_id' => 'nullable|exists:publishers,id',
            'cover_image' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'status' => 'nullable|in:available,borrowed,reserved,lost',
            'ebook_file' => 'nullable|file|mimes:pdf,epub,mp3|max:20480', // 20MB max
        ]);

        if ($request->hasFile('ebook_file')) {
            $file = $request->file('ebook_file');
            $path = $file->store('ebooks', 'public');
            $validated['ebook_file_path'] = $path;
        }

        $book->update($validated);
        return redirect()->route('library.books.index')->with('success', 'Book updated successfully!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $book = \App\Models\Modules\Library\Models\Book::findOrFail($id);
        $book->delete();
        return redirect()->route('library.books.index')->with('success', 'Book deleted successfully!');
    }

    /**
     * Search for books by title, author, or ISBN (for smart search bar).
     */
    public function search(Request $request)
    {
        $query = $request->input('q');
        $books = \App\Models\Modules\Library\Models\Book::with(['author', 'category', 'publisher'])
            ->where('title', 'like', "%{$query}%")
            ->orWhereHas('author', function($q) use ($query) {
                $q->where('name', 'like', "%{$query}%");
            })
            ->orWhere('isbn', 'like', "%{$query}%")
            ->limit(10)
            ->get();
        return response()->json($books);
    }
} 