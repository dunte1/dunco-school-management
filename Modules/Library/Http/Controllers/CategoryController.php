<?php

namespace Modules\Library\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function index() {
        $categories = collect([
            (object)[
                'id' => 1,
                'name' => 'Fiction',
                'description' => 'Fictional literature and novels',
                'book_count' => 150,
                'created_at' => '2024-01-15'
            ],
            (object)[
                'id' => 2,
                'name' => 'Non-Fiction',
                'description' => 'Non-fictional books and reference materials',
                'book_count' => 200,
                'created_at' => '2024-02-20'
            ],
            (object)[
                'id' => 3,
                'name' => 'Science Fiction',
                'description' => 'Science fiction and fantasy books',
                'book_count' => 75,
                'created_at' => '2024-03-10'
            ]
        ]);

        return view('library::categories.index', compact('categories'));
    }

    public function create() {
        return view('library::categories.create');
    }

    public function store(Request $request) {
        $request->validate(['name' => 'required|string|max:255']);
        // Storage logic would go here
        return redirect()->route('library.categories.index')->with('success', 'Category created successfully!');
    }

    public function show($id) {
        $category = (object)[
            'id' => $id,
            'name' => 'Fiction',
            'description' => 'Fictional literature and novels',
            'book_count' => 150,
            'created_at' => '2024-01-15'
        ];

        return view('library::categories.show', compact('category'));
    }

    public function edit($id) {
        $category = (object)[
            'id' => $id,
            'name' => 'Fiction',
            'description' => 'Fictional literature and novels',
            'book_count' => 150,
            'created_at' => '2024-01-15'
        ];

        return view('library::categories.edit', compact('category'));
    }

    public function update(Request $request, $id) {
        $request->validate(['name' => 'required|string|max:255']);
        // Update logic would go here
        return redirect()->route('library.categories.index')->with('success', 'Category updated successfully!');
    }

    public function destroy($id) {
        // Delete logic would go here
        return redirect()->route('library.categories.index')->with('success', 'Category deleted successfully!');
    }
} 