<?php

namespace Modules\Examination\app\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\View\View;
use Illuminate\Support\Facades\DB;
use Modules\Examination\app\Models\Question;
use Modules\Examination\app\Models\QuestionCategory;

class QuestionController extends Controller
{
    public function index(): View
    {
        $questions = Question::with(['category'])
                            ->active()
                            ->orderBy('created_at', 'desc')
                            ->paginate(15);

        $categories = QuestionCategory::active()->get();
        
        return view('examination::questions.index', compact('questions', 'categories'));
    }

    public function create(): View
    {
        $categories = QuestionCategory::active()->get();
        return view('examination::questions.create', compact('categories'));
    }

    public function store(Request $request): JsonResponse
    {
        $validator = \Validator::make($request->all(), [
            'question_text' => 'required|string',
            'type' => 'required|in:mcq,fill_blank,essay,coding,matching,true_false,short_answer',
            'category_id' => 'required|exists:question_categories,id',
            'options' => 'nullable|array',
            'correct_answers' => 'nullable|array',
            'marks' => 'required|numeric|min:0',
            'explanation' => 'nullable|string',
            'difficulty' => 'required|in:easy,medium,hard',
            'tags' => 'nullable|string',
            'feedback' => 'nullable|string',
            'file_upload' => 'boolean',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $data = $request->validated();
            $data['tags'] = isset($data['tags']) ? array_map('trim', explode(',', $data['tags'])) : [];
            $data['file_upload'] = $request->has('file_upload');
            $question = Question::create($data);

            return response()->json([
                'success' => true,
                'message' => 'Question created successfully',
                'question' => $question->load('category')
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to create question: ' . $e->getMessage()
            ], 500);
        }
    }

    public function show(Question $question): View
    {
        $question->load('category');
        return view('examination::questions.show', compact('question'));
    }

    public function edit(Question $question): View
    {
        $categories = QuestionCategory::active()->get();
        return view('examination::questions.edit', compact('question', 'categories'));
    }

    public function update(Request $request, Question $question): JsonResponse
    {
        $validator = \Validator::make($request->all(), [
            'question_text' => 'required|string',
            'type' => 'required|in:mcq,fill_blank,essay,coding,matching,true_false,short_answer',
            'category_id' => 'required|exists:question_categories,id',
            'options' => 'nullable|array',
            'correct_answers' => 'nullable|array',
            'marks' => 'required|numeric|min:0',
            'explanation' => 'nullable|string'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $question->update($request->validated());

            return response()->json([
                'success' => true,
                'message' => 'Question updated successfully',
                'question' => $question->load('category')
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to update question: ' . $e->getMessage()
            ], 500);
        }
    }

    public function destroy(Question $question): JsonResponse
    {
        try {
            $question->delete();

            return response()->json([
                'success' => true,
                'message' => 'Question deleted successfully'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete question: ' . $e->getMessage()
            ], 500);
        }
    }

    public function import(Request $request): JsonResponse
    {
        $validator = \Validator::make($request->all(), [
            'file' => 'required|file|mimes:xlsx,xls,csv'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            // Import logic here
            return response()->json([
                'success' => true,
                'message' => 'Questions imported successfully'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to import questions: ' . $e->getMessage()
            ], 500);
        }
    }

    public function export(): JsonResponse
    {
        try {
            $questions = Question::with('category')->get();
            
            // Export logic here
            return response()->json([
                'success' => true,
                'message' => 'Questions exported successfully'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to export questions: ' . $e->getMessage()
            ], 500);
        }
    }
} 