<?php

namespace Modules\Library\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Modules\Library\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function index(Request $request)
    {
        $query = Category::query();

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where('name', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
        }

        $categories = $query->withCount('books')->orderBy('name')->paginate(15)->withQueryString();

        return view('library::categories.index', compact('categories'));
    }

    public function create()
    {
        return view('library::categories.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:categories,name',
            'description' => 'nullable|string',
        ]);

        Category::create($validated);

        return redirect()->route('library.categories.index')->with('success', 'Category created successfully!');
    }

    public function show(Category $category)
    {
        $category->load('books.author', 'books.publisher');
        $category->loadCount('books');

        return view('library::categories.show', compact('category'));
    }

    public function edit(Category $category)
    {
        return view('library::categories.edit', compact('category'));
    }

    public function update(Request $request, Category $category)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:categories,name,' . $category->id,
            'description' => 'nullable|string',
        ]);

        $category->update($validated);

        return redirect()->route('library.categories.index')->with('success', 'Category updated successfully!');
    }

    public function destroy(Category $category)
    {
        if ($category->books()->count() > 0) {
            return redirect()->route('library.categories.index')
                ->with('error', 'Cannot delete category with associated books. Remove books first.');
        }

        $category->delete();

        return redirect()->route('library.categories.index')->with('success', 'Category deleted successfully!');
    }
}
