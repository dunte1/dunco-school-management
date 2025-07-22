<?php

namespace Modules\Examination\app\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\View\View;
use Modules\Examination\app\Models\QuestionCategory;

class QuestionCategoryController extends Controller
{
    public function index(): View
    {
        $categories = QuestionCategory::with('children')
                                    ->whereNull('parent_id')
                                    ->active()
                                    ->orderBy('name')
                                    ->paginate(15);

        return view('examination::categories.index', compact('categories'));
    }

    public function create(): View
    {
        $parentCategories = QuestionCategory::active()->get();
        return view('examination::categories.create', compact('parentCategories'));
    }

    public function store(Request $request): JsonResponse
    {
        $validator = \Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'code' => 'required|string|max:50|unique:question_categories,code',
            'parent_id' => 'nullable|exists:question_categories,id',
            'subject' => 'nullable|string|max:100',
            'topic' => 'nullable|string|max:100',
            'difficulty' => 'required|in:easy,medium,hard',
            'description' => 'nullable|string'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $category = QuestionCategory::create($request->validated());

            return response()->json([
                'success' => true,
                'message' => 'Category created successfully',
                'category' => $category
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to create category: ' . $e->getMessage()
            ], 500);
        }
    }

    public function show(QuestionCategory $category): View
    {
        $category->load(['children', 'questions']);
        return view('examination::categories.show', compact('category'));
    }

    public function edit(QuestionCategory $category): View
    {
        $parentCategories = QuestionCategory::where('id', '!=', $category->id)->active()->get();
        return view('examination::categories.edit', compact('category', 'parentCategories'));
    }

    public function update(Request $request, QuestionCategory $category): JsonResponse
    {
        $validator = \Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'code' => 'required|string|max:50|unique:question_categories,code,' . $category->id,
            'parent_id' => 'nullable|exists:question_categories,id',
            'subject' => 'nullable|string|max:100',
            'topic' => 'nullable|string|max:100',
            'difficulty' => 'required|in:easy,medium,hard',
            'description' => 'nullable|string'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $category->update($request->validated());

            return response()->json([
                'success' => true,
                'message' => 'Category updated successfully',
                'category' => $category
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to update category: ' . $e->getMessage()
            ], 500);
        }
    }

    public function destroy(QuestionCategory $category): JsonResponse
    {
        try {
            // Check if category has questions
            if ($category->questions()->exists()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Cannot delete category with existing questions'
                ], 400);
            }

            $category->delete();

            return response()->json([
                'success' => true,
                'message' => 'Category deleted successfully'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete category: ' . $e->getMessage()
            ], 500);
        }
    }
} 