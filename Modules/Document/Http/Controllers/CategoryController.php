<?php

namespace Modules\Document\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Document\Models\Category;

class CategoryController extends Controller
{
    public function index(Request $request)
    {
        $query = Category::withCount('documents');

        // Filter by status
        if ($request->has('status') && $request->status) {
            $query->where('is_active', $request->status === 'active');
        }

        // Search by name
        if ($request->has('search') && $request->search) {
            $query->where('name', 'like', '%' . $request->search . '%')
                  ->orWhere('description', 'like', '%' . $request->search . '%');
        }

        $categories = $query->orderBy('name', 'asc')->paginate(15);

        return view('document::categories.index', compact('categories'));
    }

    public function create()
    {
        return view('document::categories.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:document_categories,name',
            'description' => 'nullable|string',
            'color' => 'nullable|string|max:7',
            'is_active' => 'boolean',
            'parent_id' => 'nullable|exists:document_categories,id'
        ]);

        try {
            $data = $request->all();
            $data['created_by'] = auth()->id();

            Category::create($data);

            return redirect()->route('document.categories.index')
                           ->with('success', 'Category created successfully.');

        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'Failed to create category: ' . $e->getMessage()]);
        }
    }

    public function show($id)
    {
        $category = Category::with(['documents', 'parent'])->findOrFail($id);
        return view('document::categories.show', compact('category'));
    }

    public function edit($id)
    {
        $category = Category::findOrFail($id);
        $parentCategories = Category::where('id', '!=', $id)->get();
        return view('document::categories.edit', compact('category', 'parentCategories'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:document_categories,name,' . $id,
            'description' => 'nullable|string',
            'color' => 'nullable|string|max:7',
            'is_active' => 'boolean',
            'parent_id' => 'nullable|exists:document_categories,id'
        ]);

        try {
            $category = Category::findOrFail($id);
            $category->update($request->all());

            return redirect()->route('document.categories.index')
                           ->with('success', 'Category updated successfully.');

        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'Failed to update category: ' . $e->getMessage()]);
        }
    }

    public function destroy($id)
    {
        try {
            $category = Category::findOrFail($id);
            
            // Check if category has documents
            if ($category->documents()->count() > 0) {
                return back()->withErrors(['error' => 'Cannot delete category with existing documents.']);
            }
            
            // Check if category has subcategories
            if ($category->children()->count() > 0) {
                return back()->withErrors(['error' => 'Cannot delete category with subcategories.']);
            }
            
            $category->delete();

            return redirect()->route('document.categories.index')
                           ->with('success', 'Category deleted successfully.');

        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'Failed to delete category: ' . $e->getMessage()]);
        }
    }
}
