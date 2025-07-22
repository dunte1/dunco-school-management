<?php

namespace Modules\Examination\app\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\View\View;
use Illuminate\Support\Facades\DB;
use Modules\Examination\app\Models\Exam;
use Modules\Examination\app\Models\ExamResult;
use Modules\Examination\app\Models\ExamAttempt;

class ResultController extends Controller
{
    public function index(): View
    {
        $results = ExamResult::with(['exam', 'student'])
                            ->orderBy('created_at', 'desc')
                            ->paginate(15);

        return view('examination::results.index', compact('results'));
    }

    public function show(ExamResult $result): View
    {
        $result->load(['exam', 'student', 'attempt.answers.question']);
        return view('examination::results.show', compact('result'));
    }

    public function publishResults(Request $request, Exam $exam): JsonResponse
    {
        try {
            DB::beginTransaction();

            // Calculate rankings
            $results = $exam->results()->orderBy('obtained_marks', 'desc')->get();
            
            foreach ($results as $index => $result) {
                $result->update([
                    'class_position' => $index + 1,
                    'is_published' => true,
                    'published_at' => now()
                ]);
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Results published successfully'
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Failed to publish results: ' . $e->getMessage()
            ], 500);
        }
    }

    public function transcript($studentId): View
    {
        $student = \App\Models\User::findOrFail($studentId);
        $results = ExamResult::where('student_id', $studentId)
                            ->with(['exam'])
                            ->published()
                            ->orderBy('created_at', 'desc')
                            ->get();

        return view('examination::results.transcript', compact('student', 'results'));
    }

    public function rankings(Exam $exam): View
    {
        $rankings = $exam->results()
                        ->with(['student'])
                        ->published()
                        ->orderBy('obtained_marks', 'desc')
                        ->paginate(20);

        return view('examination::results.rankings', compact('exam', 'rankings'));
    }

    public function analytics(): View
    {
        $stats = [
            'total_results' => ExamResult::count(),
            'published_results' => ExamResult::published()->count(),
            'pass_rate' => ExamResult::published()->passed()->count() / max(ExamResult::published()->count(), 1) * 100,
            'average_score' => ExamResult::published()->avg('percentage') ?? 0
        ];

        $examPerformance = ExamResult::with('exam')
                                    ->published()
                                    ->selectRaw('exam_id, AVG(percentage) as avg_percentage, COUNT(*) as total_students')
                                    ->groupBy('exam_id')
                                    ->orderBy('avg_percentage', 'desc')
                                    ->take(10)
                                    ->get();

        return view('examination::results.analytics', compact('stats', 'examPerformance'));
    }

    public function studentResults(): View
    {
        $results = ExamResult::where('student_id', auth()->id())
                            ->with(['exam'])
                            ->published()
                            ->orderBy('created_at', 'desc')
                            ->paginate(15);

        return view('examination::results.student', compact('results'));
    }
} 