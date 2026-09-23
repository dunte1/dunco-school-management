<?php

namespace Modules\Examination\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Validator;
use Modules\Examination\Models\QuestionCategory;

class QuestionCategoryController extends Controller
{
    public function index(Request $request)
    {
        $query = QuestionCategory::withCount('questions');

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('code', 'like', "%{$search}%")
                  ->orWhere('subject', 'like', "%{$search}%");
            });
        }

        if ($request->filled('difficulty')) {
            $query->where('difficulty', $request->difficulty);
        }

        if ($request->filled('status')) {
            $query->where('is_active', $request->status === 'active');
        }

        $categories = $query->orderByDesc('created_at')->paginate(15);

        return view('examination::categories.index', compact('categories'));
    }

    public function create()
    {
        $parentCategories = QuestionCategory::whereNull('parent_id')
            ->orderBy('name')
            ->get();

        return view('examination::categories.create', compact('parentCategories'));
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'code' => 'required|string|max:50|unique:question_categories,code',
            'description' => 'nullable|string|max:1000',
            'parent_id' => 'nullable|exists:question_categories,id',
            'subject' => 'nullable|string|max:255',
            'topic' => 'nullable|string|max:255',
            'difficulty' => 'nullable|string|in:easy,medium,hard,expert',
            'is_active' => 'boolean',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $data = $validator->validated();
        $data['is_active'] = $request->boolean('is_active', true);

        QuestionCategory::create($data);

        return redirect()->route('examination.categories.index')
            ->with('success', 'Category created successfully!');
    }

    public function show($id)
    {
        $category = QuestionCategory::withCount(['questions', 'children'])
            ->findOrFail($id);

        return view('examination::categories.show', compact('category'));
    }

    public function edit($id)
    {
        $category = QuestionCategory::findOrFail($id);
        $parentCategories = QuestionCategory::whereNull('parent_id')
            ->where('id', '!=', $id)
            ->orderBy('name')
            ->get();

        return view('examination::categories.edit', compact('category', 'parentCategories'));
    }

    public function update(Request $request, $id)
    {
        $category = QuestionCategory::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'code' => 'required|string|max:50|unique:question_categories,code,' . $id,
            'description' => 'nullable|string|max:1000',
            'parent_id' => 'nullable|exists:question_categories,id',
            'subject' => 'nullable|string|max:255',
            'topic' => 'nullable|string|max:255',
            'difficulty' => 'nullable|string|in:easy,medium,hard,expert',
            'is_active' => 'boolean',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $data = $validator->validated();
        if ($request->has('is_active')) {
            $data['is_active'] = $request->boolean('is_active');
        }

        $category->update($data);

        return redirect()->route('examination.categories.index')
            ->with('success', 'Category updated successfully!');
    }

    public function destroy($id)
    {
        $category = QuestionCategory::findOrFail($id);

        if ($category->questions()->count() > 0) {
            return redirect()->back()
                ->with('error', 'Cannot delete category with existing questions. Reassign or remove questions first.');
        }

        if ($category->children()->count() > 0) {
            return redirect()->back()
                ->with('error', 'Cannot delete category with child categories. Remove or reassign children first.');
        }

        $category->delete();

        return redirect()->route('examination.categories.index')
            ->with('success', 'Category deleted successfully!');
    }
}
