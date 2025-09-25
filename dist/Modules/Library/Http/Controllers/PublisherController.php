<?php

namespace Modules\Library\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Models\Modules\Library\Models\Publisher;

class PublisherController extends Controller
{
    public function index() {
        $publishers = Publisher::all();
        return Inertia::render('Library/Publishers/Index', ['publishers' => $publishers]);
    }
    public function store(Request $request) {
        $request->validate(['name' => 'required|string|max:255']);
        Publisher::create(['name' => $request->name]);
        return redirect()->route('library.publishers.index')->with('success', 'Publisher created!');
    }
    public function update(Request $request, $id) {
        $request->validate(['name' => 'required|string|max:255']);
        $publisher = Publisher::findOrFail($id);
        $publisher->update(['name' => $request->name]);
        return redirect()->route('library.publishers.index')->with('success', 'Publisher updated!');
    }
    public function destroy($id) {
        $publisher = Publisher::findOrFail($id);
        $publisher->delete();
        return redirect()->route('library.publishers.index')->with('success', 'Publisher deleted!');
    }
} 