<?php

namespace Modules\Library\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Modules\Library\Models\Publisher;
use Illuminate\Http\Request;

class PublisherController extends Controller
{
    public function index(Request $request)
    {
        $query = Publisher::query();

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('phone', 'like', "%{$search}%");
            });
        }

        $publishers = $query->withCount('books')->orderBy('name')->paginate(15)->withQueryString();

        return view('library::publishers.index', compact('publishers'));
    }

    public function create()
    {
        return view('library::publishers.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'nullable|email|max:255',
            'phone' => 'nullable|string|max:20',
            'website' => 'nullable|url|max:255',
            'address' => 'nullable|string',
            'description' => 'nullable|string',
        ]);

        Publisher::create($validated);

        return redirect()->route('library.publishers.index')->with('success', 'Publisher created successfully!');
    }

    public function show(Publisher $publisher)
    {
        $publisher->load('books.author', 'books.category');
        $publisher->loadCount('books');

        return view('library::publishers.show', compact('publisher'));
    }

    public function edit(Publisher $publisher)
    {
        return view('library::publishers.edit', compact('publisher'));
    }

    public function update(Request $request, Publisher $publisher)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'nullable|email|max:255',
            'phone' => 'nullable|string|max:20',
            'website' => 'nullable|url|max:255',
            'address' => 'nullable|string',
            'description' => 'nullable|string',
        ]);

        $publisher->update($validated);

        return redirect()->route('library.publishers.index')->with('success', 'Publisher updated successfully!');
    }

    public function destroy(Publisher $publisher)
    {
        if ($publisher->books()->count() > 0) {
            return redirect()->route('library.publishers.index')
                ->with('error', 'Cannot delete publisher with associated books. Remove books first.');
        }

        $publisher->delete();

        return redirect()->route('library.publishers.index')->with('success', 'Publisher deleted successfully!');
    }
}
