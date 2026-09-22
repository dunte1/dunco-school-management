<?php

namespace Modules\Nursing\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Inertia\Inertia;
use Modules\Nursing\Models\ReferenceArticle;
use Modules\Nursing\Models\ReferenceCategory;

class ReferenceController extends Controller
{
    public function index(Request $request)
    {
        $query = ReferenceArticle::published()->with('category');

        if ($request->filled('category_id')) {
            $query->where('category_id', $request->category_id);
        }
        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('title', 'like', '%' . $request->search . '%')
                  ->orWhere('content', 'like', '%' . $request->search . '%');
            });
        }

        $articles = $query->latest()->paginate(15);
        $categories = ReferenceCategory::where('is_active', true)->whereNull('parent_id')->orderBy('sort_order')->get();

        return Inertia::render('Nursing/Reference/Index', compact('articles', 'categories'));
    }

    public function show(ReferenceArticle $article)
    {
        $article->load('category');
        $article->incrementViewCount();

        return Inertia::render('Nursing/Reference/Show', compact('article'));
    }

    public function create()
    {
        $categories = ReferenceCategory::where('is_active', true)->orderBy('sort_order')->get();

        return Inertia::render('Nursing/Reference/Create', compact('categories'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'category_id' => 'required|exists:nursing_reference_categories,id',
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'author' => 'nullable|string|max:255',
            'source' => 'nullable|string|max:255',
            'tags' => 'nullable|array',
        ]);

        $validated['slug'] = Str::slug($validated['title']);
        $validated['author_id'] = Auth::id();
        $validated['status'] = 'draft';

        $article = ReferenceArticle::create($validated);

        return redirect()->route('nursing.reference.show', $article)
            ->with('success', 'Article created successfully.');
    }

    public function edit(ReferenceArticle $article)
    {
        $categories = ReferenceCategory::where('is_active', true)->orderBy('sort_order')->get();

        return Inertia::render('Nursing/Reference/Edit', compact('article', 'categories'));
    }

    public function update(Request $request, ReferenceArticle $article)
    {
        $validated = $request->validate([
            'category_id' => 'required|exists:nursing_reference_categories,id',
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'author' => 'nullable|string|max:255',
            'source' => 'nullable|string|max:255',
            'tags' => 'nullable|array',
        ]);

        $validated['slug'] = Str::slug($validated['title']);

        $article->update($validated);

        return redirect()->route('nursing.reference.show', $article)
            ->with('success', 'Article updated successfully.');
    }

    public function approve(ReferenceArticle $article)
    {
        $article->update([
            'status' => 'published',
            'reviewer_id' => Auth::id(),
            'reviewer' => Auth::user()->name,
            'review_date' => now(),
        ]);

        return back()->with('success', 'Article approved and published.');
    }
}
