<?php

namespace Modules\Library\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Models\Modules\Library\Models\Author;

class AuthorController extends Controller
{
    public function index() {
        $authors = Author::all();
        return Inertia::render('Library/Authors/Index', ['authors' => $authors]);
    }
    public function store(Request $request) {
        $request->validate(['name' => 'required|string|max:255']);
        Author::create(['name' => $request->name]);
        return redirect()->route('library.authors.index')->with('success', 'Author created!');
    }
    public function update(Request $request, $id) {
        $request->validate(['name' => 'required|string|max:255']);
        $author = Author::findOrFail($id);
        $author->update(['name' => $request->name]);
        return redirect()->route('library.authors.index')->with('success', 'Author updated!');
    }
    public function destroy($id) {
        $author = Author::findOrFail($id);
        $author->delete();
        return redirect()->route('library.authors.index')->with('success', 'Author deleted!');
    }
} 