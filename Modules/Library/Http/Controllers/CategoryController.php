<?php

namespace Modules\Library\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Models\Modules\Library\Models\Category;

class CategoryController extends Controller
{
    public function index() {
        $categories = Category::all();
        return Inertia::render('Library/Categories/Index', ['categories' => $categories]);
    }
    public function create() {
        return Inertia::render('Library/Categories/Create');
    }
    public function store(Request $request) {
        $request->validate(['name' => 'required|string|max:255']);
        Category::create(['name' => $request->name]);
        return redirect()->route('library.categories.index')->with('success', 'Category created!');
    }
    public function edit($id) {
        $category = Category::findOrFail($id);
        return Inertia::render('Library/Categories/Edit', ['category' => $category]);
    }
    public function update(Request $request, $id) {
        $request->validate(['name' => 'required|string|max:255']);
        $category = Category::findOrFail($id);
        $category->update(['name' => $request->name]);
        return redirect()->route('library.categories.index')->with('success', 'Category updated!');
    }
    public function destroy($id) {
        $category = Category::findOrFail($id);
        $category->delete();
        return redirect()->route('library.categories.index')->with('success', 'Category deleted!');
    }
} 