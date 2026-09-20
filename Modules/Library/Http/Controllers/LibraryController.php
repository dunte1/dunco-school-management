<?php

namespace Modules\Library\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class LibraryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('library::test');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('library::create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'title' => 'required|string|max:255',
                'isbn' => 'nullable|string|max:255|unique:books,isbn',
                'author' => 'nullable|string|max:255',
                'category' => 'nullable|string|max:255',
                'quantity' => 'nullable|integer|min:0',
                'status' => 'nullable|in:available,borrowed,reserved,lost',
            ]);

            \DB::table('books')->insert($validated);

            return redirect()->route('library.index')
                ->with('success', 'Book added successfully.');
        } catch (\Exception $e) {
            Log::error('LibraryController: Failed to store book - ' . $e->getMessage());
            return redirect()->back()->withInput()
                ->with('error', 'Failed to add book: ' . $e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        try {
            $book = \DB::table('books')->where('id', $id)->first();
            if (!$book) {
                return redirect()->route('library.index')
                    ->with('error', 'Book not found.');
            }
            return view('library::show', compact('book'));
        } catch (\Exception $e) {
            Log::error('LibraryController: Failed to show book - ' . $e->getMessage());
            return redirect()->route('library.index')
                ->with('error', 'Failed to load book details.');
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        try {
            $book = \DB::table('books')->where('id', $id)->first();
            if (!$book) {
                return redirect()->route('library.index')
                    ->with('error', 'Book not found.');
            }
            return view('library::edit', compact('book'));
        } catch (\Exception $e) {
            Log::error('LibraryController: Failed to edit book - ' . $e->getMessage());
            return redirect()->route('library.index')
                ->with('error', 'Failed to load book for editing.');
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        try {
            $book = \DB::table('books')->where('id', $id)->first();
            if (!$book) {
                return redirect()->route('library.index')
                    ->with('error', 'Book not found.');
            }

            $validated = $request->validate([
                'title' => 'required|string|max:255',
                'isbn' => 'nullable|string|max:255|unique:books,isbn,' . $id,
                'author' => 'nullable|string|max:255',
                'category' => 'nullable|string|max:255',
                'quantity' => 'nullable|integer|min:0',
                'status' => 'nullable|in:available,borrowed,reserved,lost',
            ]);

            \DB::table('books')->where('id', $id)->update($validated);

            return redirect()->route('library.index')
                ->with('success', 'Book updated successfully.');
        } catch (\Exception $e) {
            Log::error('LibraryController: Failed to update book - ' . $e->getMessage());
            return redirect()->back()->withInput()
                ->with('error', 'Failed to update book: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        try {
            $book = \DB::table('books')->where('id', $id)->first();
            if (!$book) {
                return redirect()->route('library.index')
                    ->with('error', 'Book not found.');
            }

            \DB::table('books')->where('id', $id)->delete();

            return redirect()->route('library.index')
                ->with('success', 'Book deleted successfully.');
        } catch (\Exception $e) {
            Log::error('LibraryController: Failed to delete book - ' . $e->getMessage());
            return redirect()->back()
                ->with('error', 'Failed to delete book: ' . $e->getMessage());
        }
    }
} 