<?php

namespace Modules\Examination\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Validator;
use Modules\Examination\Models\Question;
use Modules\Examination\Models\QuestionCategory;

class QuestionController extends Controller
{
    public function index(Request $request)
    {
        $query = Question::with('category');

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('question_text', 'like', "%{$search}%")
                  ->orWhere('tags', 'like', "%{$search}%");
            });
        }

        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }

        if ($request->filled('category_id')) {
            $query->where('category_id', $request->category_id);
        }

        if ($request->filled('difficulty')) {
            $query->where('difficulty', $request->difficulty);
        }

        if ($request->filled('status')) {
            $query->where('is_active', $request->status === 'active');
        }

        $questions = $query->orderByDesc('created_at')->paginate(20);
        $categories = QuestionCategory::orderBy('name')->get();

        if ($request->ajax()) {
            return response()->json($questions);
        }

        return view('examination::questions.index', compact('questions', 'categories'));
    }

    public function create()
    {
        $categories = QuestionCategory::orderBy('name')->get();
        return view('examination::questions.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'question_text' => 'required|string',
            'type' => 'required|string|in:mcq,true_false,short_answer,essay,fill_blank,matching,coding',
            'category_id' => 'nullable|exists:question_categories,id',
            'options' => 'nullable|array|min:2',
            'options.*' => 'required|string|max:1000',
            'correct_answers' => 'required|array|min:1',
            'correct_answers.*' => 'required|string',
            'explanation' => 'nullable|string|max:2000',
            'marks' => 'required|numeric|min:0.5|max:100',
            'time_limit_seconds' => 'nullable|integer|min:1|max:3600',
            'difficulty' => 'required|string|in:easy,medium,hard,expert',
            'tags' => 'nullable|array',
            'tags.*' => 'string|max:100',
            'feedback' => 'nullable|string|max:2000',
            'is_active' => 'boolean',
        ]);

        if ($validator->fails()) {
            if ($request->ajax()) {
                return response()->json(['success' => false, 'errors' => $validator->errors()], 422);
            }
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $data = $validator->validated();
        $data['is_active'] = $request->boolean('is_active', true);

        $question = Question::create($data);

        if ($request->ajax()) {
            return response()->json(['success' => true, 'question' => $question], 201);
        }

        return redirect()->route('examination.questions.index')
            ->with('success', 'Question created successfully!');
    }

    public function show($id)
    {
        $question = Question::with('category')->findOrFail($id);

        if (request()->ajax()) {
            return response()->json($question);
        }

        return view('examination::questions.show', compact('question'));
    }

    public function edit($id)
    {
        $question = Question::with('category')->findOrFail($id);
        $categories = QuestionCategory::orderBy('name')->get();

        if (request()->ajax()) {
            return response()->json(['question' => $question, 'categories' => $categories]);
        }

        return view('examination::questions.edit', compact('question', 'categories'));
    }

    public function update(Request $request, $id)
    {
        $question = Question::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'question_text' => 'required|string',
            'type' => 'required|string|in:mcq,true_false,short_answer,essay,fill_blank,matching,coding',
            'category_id' => 'nullable|exists:question_categories,id',
            'options' => 'nullable|array|min:2',
            'options.*' => 'required|string|max:1000',
            'correct_answers' => 'required|array|min:1',
            'correct_answers.*' => 'required|string',
            'explanation' => 'nullable|string|max:2000',
            'marks' => 'required|numeric|min:0.5|max:100',
            'time_limit_seconds' => 'nullable|integer|min:1|max:3600',
            'difficulty' => 'required|string|in:easy,medium,hard,expert',
            'tags' => 'nullable|array',
            'tags.*' => 'string|max:100',
            'feedback' => 'nullable|string|max:2000',
            'is_active' => 'boolean',
        ]);

        if ($validator->fails()) {
            if ($request->ajax()) {
                return response()->json(['success' => false, 'errors' => $validator->errors()], 422);
            }
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $data = $validator->validated();
        if ($request->has('is_active')) {
            $data['is_active'] = $request->boolean('is_active');
        }

        $question->update($data);

        if ($request->ajax()) {
            return response()->json(['success' => true, 'question' => $question]);
        }

        return redirect()->route('examination.questions.index')
            ->with('success', 'Question updated successfully!');
    }

    public function destroy($id)
    {
        $question = Question::findOrFail($id);

        if ($question->exams()->count() > 0) {
            $msg = 'Cannot delete question that is assigned to exams. Remove it from exams first.';
            if (request()->ajax()) {
                return response()->json(['success' => false, 'message' => $msg], 422);
            }
            return redirect()->back()->with('error', $msg);
        }

        $question->delete();

        if (request()->ajax()) {
            return response()->json(['success' => true, 'message' => 'Question deleted.']);
        }

        return redirect()->route('examination.questions.index')
            ->with('success', 'Question deleted successfully!');
    }

    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|file|mimes:csv,txt,json|max:10240',
            'category_id' => 'nullable|exists:question_categories,id',
        ]);

        $file = $request->file('file');
        $ext = strtolower($file->getClientOriginalExtension());

        $imported = 0;
        $skipped = 0;

        if ($ext === 'json') {
            $json = json_decode(file_get_contents($file), true);
            if (!is_array($json)) {
                return redirect()->back()->with('error', 'Invalid JSON format.');
            }
            foreach ($json as $item) {
                $validator = Validator::make($item, [
                    'question_text' => 'required|string',
                    'type' => 'required|string|in:mcq,true_false,short_answer,essay,fill_blank,matching,coding',
                    'options' => 'nullable|array',
                    'correct_answers' => 'required|array',
                    'marks' => 'required|numeric|min:0.5',
                ]);
                if ($validator->fails()) {
                    $skipped++;
                    continue;
                }
                Question::create([
                    'question_text' => $item['question_text'],
                    'type' => $item['type'],
                    'category_id' => $item['category_id'] ?? $request->category_id,
                    'options' => $item['options'] ?? null,
                    'correct_answers' => $item['correct_answers'],
                    'explanation' => $item['explanation'] ?? null,
                    'marks' => $item['marks'],
                    'difficulty' => $item['difficulty'] ?? 'medium',
                    'tags' => $item['tags'] ?? null,
                    'is_active' => true,
                ]);
                $imported++;
            }
        } else {
            $handle = fopen($file->getPathname(), 'r');
            $header = fgetcsv($handle);
            $headerMap = array_map('strtolower', array_map('trim', $header));

            while (($row = fgetcsv($handle)) !== false) {
                if (count($row) !== count($header)) {
                    $skipped++;
                    continue;
                }
                $record = array_combine($headerMap, $row);

                $data = [
                    'question_text' => $record['question_text'] ?? $record['question'] ?? '',
                    'type' => $record['type'] ?? 'multiple_choice',
                    'category_id' => $record['category_id'] ?? $request->category_id,
                    'options' => isset($record['options']) ? array_map('trim', explode('|', $record['options'])) : null,
                    'correct_answers' => isset($record['correct_answers']) ? array_map('trim', explode('|', $record['correct_answers'])) : [],
                    'explanation' => $record['explanation'] ?? null,
                    'marks' => (float)($record['marks'] ?? 1),
                    'difficulty' => $record['difficulty'] ?? 'medium',
                    'is_active' => true,
                ];

                if (empty($data['question_text']) || empty($data['correct_answers'])) {
                    $skipped++;
                    continue;
                }

                Question::create($data);
                $imported++;
            }
            fclose($handle);
        }

        return redirect()->back()->with('success', "Imported {$imported} questions. Skipped {$skipped} invalid rows.");
    }

    public function export()
    {
        $questions = Question::with('category')->orderBy('id')->get();

        $callback = function () use ($questions) {
            $out = fopen('php://output', 'w');
            fputcsv($out, ['ID', 'Question', 'Type', 'Category', 'Options', 'Correct Answers', 'Marks', 'Difficulty', 'Tags', 'Active']);
            foreach ($questions as $q) {
                fputcsv($out, [
                    $q->id,
                    $q->question_text,
                    $q->type,
                    optional($q->category)->name ?? '',
                    $q->options ? implode(' | ', $q->options) : '',
                    $q->correct_answers ? implode(' | ', $q->correct_answers) : '',
                    $q->marks,
                    $q->difficulty,
                    $q->tags ? implode(', ', $q->tags) : '',
                    $q->is_active ? 'Yes' : 'No',
                ]);
            }
            fclose($out);
        };

        return response()->streamDownload($callback, 'questions-export.csv', ['Content-Type' => 'text/csv']);
    }
}
