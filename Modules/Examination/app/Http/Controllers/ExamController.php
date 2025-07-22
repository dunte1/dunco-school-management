<?php

namespace Modules\Examination\app\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\View\View;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Modules\Examination\app\Models\Exam;
use Modules\Examination\app\Models\ExamType;
use Modules\Examination\app\Models\Question;
use Modules\Examination\app\Models\QuestionCategory;
use Modules\Examination\app\Models\ExamSchedule;
use Modules\Examination\app\Models\ExamResult;

class ExamController extends Controller
{
    public function index(): View
    {
        $exams = Exam::with(['examType', 'schedules'])
                    ->active()
                    ->orderBy('start_date', 'desc')
                    ->paginate(15);

        $examTypes = ExamType::active()->get();
        $upcomingExams = Exam::with('examType')
                            ->published()
                            ->upcoming()
                            ->take(5)
                            ->get();

        return view('examination::exams.index', compact('exams', 'examTypes', 'upcomingExams'));
    }

    public function create(): View
    {
        $examTypes = ExamType::active()->get();
        $categories = QuestionCategory::active()->get();
        
        return view('examination::exams.create', compact('examTypes', 'categories'));
    }

    public function createOnline(): View
    {
        $examTypes = ExamType::active()->get();
        $categories = QuestionCategory::active()->get();
        $classes = \Modules\Academic\Models\AcademicClass::where('is_active', true)->get();
        $subjects = \Modules\Academic\Models\Subject::where('is_active', true)->get();
        return view('examination::exams.online-create', compact('examTypes', 'categories', 'classes', 'subjects'));
    }

    public function store(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'code' => 'required|string|max:50|unique:exams,code',
            'exam_type_id' => 'required|exists:exam_types,id',
            'academic_year' => 'required|string|max:20',
            'term' => 'required|string|max:20',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'start_time' => 'required|date_format:H:i',
            'end_time' => 'required|date_format:H:i|after:start_time',
            'duration_minutes' => 'nullable|integer|min:1',
            'total_marks' => 'required|numeric|min:0',
            'passing_marks' => 'required|numeric|min:0|lte:total_marks',
            'is_online' => 'boolean',
            'enable_proctoring' => 'boolean',
            'shuffle_questions' => 'boolean',
            'shuffle_options' => 'boolean',
            'show_results_immediately' => 'boolean',
            'allow_review' => 'boolean',
            'description' => 'nullable|string',
            'negative_marking' => 'nullable|numeric|min:0|max:100',
            'proctor_webcam' => 'boolean',
            'proctor_tab_switch' => 'boolean',
            'proctor_face_detection' => 'boolean',
            'proctor_idle_timeout' => 'nullable|integer|min:30|max:600',
            'allow_retake' => 'boolean',
            'max_attempts' => 'nullable|integer|min:1|max:10',
            'retake_reason' => 'nullable|string|max:255',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            DB::beginTransaction();

            $exam = Exam::create(array_merge(
                $request->validated(),
                [
                    'shuffle_questions' => $request->has('shuffle_questions'),
                    'shuffle_options' => $request->has('shuffle_options'),
                    'negative_marking' => $request->negative_marking,
                    'proctor_webcam' => $request->has('proctor_webcam'),
                    'proctor_tab_switch' => $request->has('proctor_tab_switch'),
                    'proctor_face_detection' => $request->has('proctor_face_detection'),
                    'proctor_idle_timeout' => $request->proctor_idle_timeout,
                    'allow_retake' => $request->has('allow_retake'),
                    'max_attempts' => $request->max_attempts,
                    'retake_reason' => $request->retake_reason,
                ]
            ));

            // Create default schedule if provided
            if ($request->has('class_name')) {
                ExamSchedule::create([
                    'exam_id' => $exam->id,
                    'class_name' => $request->class_name,
                    'section' => $request->section,
                    'subject' => $request->subject,
                    'stream' => $request->stream,
                    'exam_date' => $request->start_date,
                    'start_time' => $request->start_time,
                    'end_time' => $request->end_time,
                    'room_number' => $request->room_number,
                    'max_students' => $request->max_students,
                    'invigilators' => $request->invigilators,
                    'instructions' => $request->instructions
                ]);
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Exam created successfully',
                'exam' => $exam->load('examType')
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Failed to create exam: ' . $e->getMessage()
            ], 500);
        }
    }

    public function show(Exam $exam): View
    {
        $exam->load(['examType', 'schedules', 'questions.category', 'attempts.student', 'results.student']);
        
        $statistics = [
            'total_students' => $exam->attempts()->count(),
            'completed_attempts' => $exam->attempts()->completed()->count(),
            'average_score' => $exam->results()->avg('percentage') ?? 0,
            'pass_rate' => $exam->results()->passed()->count() / max($exam->results()->count(), 1) * 100
        ];

        return view('examination::exams.show', compact('exam', 'statistics'));
    }

    public function edit(Exam $exam): View
    {
        $exam->load(['examType', 'schedules', 'questions']);
        $examTypes = ExamType::active()->get();
        $categories = QuestionCategory::active()->get();
        
        return view('examination::exams.edit', compact('exam', 'examTypes', 'categories'));
    }

    public function update(Request $request, Exam $exam): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'code' => 'required|string|max:50|unique:exams,code,' . $exam->id,
            'exam_type_id' => 'required|exists:exam_types,id',
            'academic_year' => 'required|string|max:20',
            'term' => 'required|string|max:20',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'start_time' => 'nullable|date_format:H:i',
            'end_time' => 'nullable|date_format:H:i|after:start_time',
            'duration_minutes' => 'nullable|integer|min:1',
            'total_marks' => 'required|numeric|min:0',
            'passing_marks' => 'required|numeric|min:0|lte:total_marks',
            'is_online' => 'boolean',
            'enable_proctoring' => 'boolean',
            'shuffle_questions' => 'boolean',
            'shuffle_options' => 'boolean',
            'show_results_immediately' => 'boolean',
            'allow_review' => 'boolean',
            'description' => 'nullable|string'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $exam->update($request->validated());

            return response()->json([
                'success' => true,
                'message' => 'Exam updated successfully',
                'exam' => $exam->load('examType')
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to update exam: ' . $e->getMessage()
            ], 500);
        }
    }

    public function destroy(Exam $exam): JsonResponse
    {
        try {
            // Check if exam has attempts
            if ($exam->attempts()->exists()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Cannot delete exam with existing attempts'
                ], 400);
            }

            $exam->delete();

            return response()->json([
                'success' => true,
                'message' => 'Exam deleted successfully'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete exam: ' . $e->getMessage()
            ], 500);
        }
    }

    public function publish(Exam $exam): JsonResponse
    {
        try {
            $exam->update(['status' => 'published']);

            return response()->json([
                'success' => true,
                'message' => 'Exam published successfully'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to publish exam: ' . $e->getMessage()
            ], 500);
        }
    }

    public function start(Exam $exam): JsonResponse
    {
        try {
            $exam->update(['status' => 'ongoing']);

            return response()->json([
                'success' => true,
                'message' => 'Exam started successfully'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to start exam: ' . $e->getMessage()
            ], 500);
        }
    }

    public function complete(Exam $exam): JsonResponse
    {
        try {
            $exam->update(['status' => 'completed']);

            return response()->json([
                'success' => true,
                'message' => 'Exam completed successfully'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to complete exam: ' . $e->getMessage()
            ], 500);
        }
    }

    public function addQuestions(Request $request, Exam $exam): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'question_ids' => 'required|array',
            'question_ids.*' => 'exists:questions,id'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $questions = collect($request->question_ids)->mapWithKeys(function ($id, $index) {
                return [$id => ['order' => $index + 1]];
            });

            $exam->questions()->attach($questions->toArray());

            return response()->json([
                'success' => true,
                'message' => 'Questions added successfully'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to add questions: ' . $e->getMessage()
            ], 500);
        }
    }

    public function removeQuestion(Exam $exam, Question $question): JsonResponse
    {
        try {
            $exam->questions()->detach($question->id);

            return response()->json([
                'success' => true,
                'message' => 'Question removed successfully'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to remove question: ' . $e->getMessage()
            ], 500);
        }
    }

    public function generateRandomQuestions(Request $request, Exam $exam): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'category_ids' => 'required|array',
            'category_ids.*' => 'exists:question_categories,id',
            'question_counts' => 'required|array',
            'question_counts.*' => 'integer|min:1'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $questions = collect();
            $order = 1;

            foreach ($request->category_ids as $index => $categoryId) {
                $count = $request->question_counts[$index] ?? 1;
                
                $categoryQuestions = Question::active()
                    ->byCategory($categoryId)
                    ->inRandomOrder()
                    ->limit($count)
                    ->get();

                foreach ($categoryQuestions as $question) {
                    $questions->put($question->id, ['order' => $order++]);
                }
            }

            $exam->questions()->attach($questions->toArray());

            return response()->json([
                'success' => true,
                'message' => 'Random questions generated successfully',
                'count' => $questions->count()
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to generate questions: ' . $e->getMessage()
            ], 500);
        }
    }

    public function results(Exam $exam): View
    {
        $results = $exam->results()
                       ->with(['student', 'attempt'])
                       ->orderBy('obtained_marks', 'desc')
                       ->paginate(20);

        $statistics = [
            'total_students' => $exam->results()->count(),
            'passed' => $exam->results()->passed()->count(),
            'failed' => $exam->results()->failed()->count(),
            'average_score' => $exam->results()->avg('percentage') ?? 0,
            'highest_score' => $exam->results()->max('obtained_marks') ?? 0,
            'lowest_score' => $exam->results()->min('obtained_marks') ?? 0
        ];

        return view('examination::exams.results', compact('exam', 'results', 'statistics'));
    }

    public function exportResults(Exam $exam): JsonResponse
    {
        try {
            $results = $exam->results()
                           ->with(['student', 'attempt'])
                           ->orderBy('obtained_marks', 'desc')
                           ->get();

            $filename = "exam_results_{$exam->code}_" . now()->format('Y-m-d_H-i-s') . '.csv';
            
            // Generate CSV content
            $csvContent = "Student Name,Student ID,Total Marks,Obtained Marks,Percentage,Grade,Status,Position\n";
            
            foreach ($results as $result) {
                $csvContent .= "{$result->student->name},{$result->student->id},{$result->total_marks},{$result->obtained_marks},{$result->percentage},{$result->grade},{$result->result_status},{$result->class_position}\n";
            }

            return response()->json([
                'success' => true,
                'filename' => $filename,
                'content' => $csvContent
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to export results: ' . $e->getMessage()
            ], 500);
        }
    }
} 