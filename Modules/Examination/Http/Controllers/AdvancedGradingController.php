<?php

namespace Modules\Examination\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Examination\Models\ExamAttempt;
use Modules\Examination\Models\QuestionGrading;
use Modules\Examination\Models\GradingRubric;
use Modules\Examination\Models\GradeDispute;
use Modules\Examination\Models\Question;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class AdvancedGradingController extends Controller
{
    public function index()
    {
        $pendingGradings = QuestionGrading::with(['examAttempt', 'question', 'grader'])
            ->pending()
            ->paginate(20);

        $gradingStats = [
            'pending' => QuestionGrading::pending()->count(),
            'graded' => QuestionGrading::graded()->count(),
            'moderated' => QuestionGrading::moderated()->count(),
            'disputed' => QuestionGrading::disputed()->count(),
        ];

        return view('examination::grading.index', compact('pendingGradings', 'gradingStats'));
    }

    public function rubricIndex()
    {
        $rubrics = GradingRubric::where('is_active', true)->paginate(20);
        return view('examination::grading.rubrics.index', compact('rubrics'));
    }

    public function createRubric()
    {
        return view('examination::grading.rubrics.create');
    }

    public function storeRubric(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'criteria' => 'required|array|min:1',
            'criteria.*.name' => 'required|string|max:255',
            'criteria.*.description' => 'required|string',
            'criteria.*.max_points' => 'required|numeric|min:0',
            'criteria.*.weight' => 'nullable|numeric|min:0',
            'grade_scale' => 'required|array',
        ]);

        $totalPoints = collect($request->criteria)->sum('max_points');
        
        $rubric = GradingRubric::create([
            'name' => $request->name,
            'description' => $request->description,
            'criteria' => $request->criteria,
            'total_points' => $totalPoints,
            'grade_scale' => json_encode($request->grade_scale),
        ]);

        return redirect()->route('examination.grading.rubrics.index')
            ->with('success', 'Grading rubric created successfully.');
    }

    public function editRubric(GradingRubric $rubric)
    {
        return view('examination::grading.rubrics.edit', compact('rubric'));
    }

    public function updateRubric(Request $request, GradingRubric $rubric)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'criteria' => 'required|array|min:1',
            'criteria.*.name' => 'required|string|max:255',
            'criteria.*.description' => 'required|string',
            'criteria.*.max_points' => 'required|numeric|min:0',
            'criteria.*.weight' => 'nullable|numeric|min:0',
            'grade_scale' => 'required|array',
        ]);

        $totalPoints = collect($request->criteria)->sum('max_points');
        
        $rubric->update([
            'name' => $request->name,
            'description' => $request->description,
            'criteria' => $request->criteria,
            'total_points' => $totalPoints,
            'grade_scale' => json_encode($request->grade_scale),
        ]);

        return redirect()->route('examination.grading.rubrics.index')
            ->with('success', 'Grading rubric updated successfully.');
    }

    public function gradeQuestion(Request $request, $gradingId)
    {
        $grading = QuestionGrading::findOrFail($gradingId);
        
        $request->validate([
            'marks_obtained' => 'required|numeric|min:0|max:' . $grading->total_marks,
            'rubric_scores' => 'nullable|array',
            'feedback' => 'nullable|string',
            'comments' => 'nullable|string',
        ]);

        $grading->update([
            'marks_obtained' => $request->marks_obtained,
            'rubric_scores' => $request->rubric_scores,
            'feedback' => $request->feedback,
            'comments' => $request->comments,
            'status' => 'graded',
            'graded_at' => now(),
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Question graded successfully.',
            'grading' => $grading->fresh()
        ]);
    }

    public function moderateGrading(Request $request, $gradingId)
    {
        $grading = QuestionGrading::findOrFail($gradingId);
        
        $request->validate([
            'marks_obtained' => 'required|numeric|min:0|max:' . $grading->total_marks,
            'moderation_notes' => 'nullable|string',
        ]);

        $grading->update([
            'marks_obtained' => $request->marks_obtained,
            'comments' => $request->moderation_notes,
            'status' => 'moderated',
            'moderated_at' => now(),
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Grading moderated successfully.',
        ]);
    }

    public function disputeIndex()
    {
        $disputes = GradeDispute::with(['questionGrading.question', 'student', 'reviewer'])
            ->latest()
            ->paginate(20);

        return view('examination::grading.disputes.index', compact('disputes'));
    }

    public function createDispute(Request $request, $gradingId)
    {
        $grading = QuestionGrading::findOrFail($gradingId);
        
        $request->validate([
            'dispute_reason' => 'required|string|max:1000',
            'supporting_evidence' => 'nullable|array',
        ]);

        $dispute = GradeDispute::create([
            'question_grading_id' => $gradingId,
            'student_id' => Auth::id(),
            'dispute_reason' => $request->dispute_reason,
            'supporting_evidence' => $request->supporting_evidence,
        ]);

        $grading->update(['status' => 'disputed']);

        return response()->json([
            'success' => true,
            'message' => 'Grade dispute submitted successfully.',
        ]);
    }

    public function resolveDispute(Request $request, $disputeId)
    {
        $dispute = GradeDispute::findOrFail($disputeId);
        
        $request->validate([
            'status' => 'required|in:resolved,rejected',
            'resolution_notes' => 'required|string',
            'adjusted_marks' => 'nullable|numeric|min:0',
        ]);

        $dispute->update([
            'status' => $request->status,
            'reviewed_by' => Auth::id(),
            'resolution_notes' => $request->resolution_notes,
            'adjusted_marks' => $request->adjusted_marks,
            'resolved_at' => now(),
        ]);

        if ($request->status === 'resolved' && $request->adjusted_marks) {
            $dispute->questionGrading->update([
                'marks_obtained' => $request->adjusted_marks,
                'status' => 'graded',
            ]);
        }

        return response()->json([
            'success' => true,
            'message' => 'Dispute resolved successfully.',
        ]);
    }

    public function batchGrade(Request $request)
    {
        $request->validate([
            'grading_ids' => 'required|array',
            'grading_ids.*' => 'exists:question_gradings,id',
            'marks_obtained' => 'required|numeric|min:0',
            'feedback' => 'nullable|string',
        ]);

        $gradedCount = 0;
        
        foreach ($request->grading_ids as $gradingId) {
            $grading = QuestionGrading::findOrFail($gradingId);
            
            if ($grading->status === 'pending') {
                $grading->update([
                    'marks_obtained' => $request->marks_obtained,
                    'feedback' => $request->feedback,
                    'status' => 'graded',
                    'graded_at' => now(),
                ]);
                $gradedCount++;
            }
        }

        return response()->json([
            'success' => true,
            'message' => "Successfully graded {$gradedCount} questions.",
        ]);
    }

    public function gradingDashboard()
    {
        $stats = [
            'total_pending' => QuestionGrading::pending()->count(),
            'total_graded' => QuestionGrading::graded()->count(),
            'total_moderated' => QuestionGrading::moderated()->count(),
            'total_disputed' => QuestionGrading::disputed()->count(),
            'pending_disputes' => GradeDispute::pending()->count(),
            'avg_grading_time' => $this->getAverageGradingTime(),
        ];

        $recentGradings = QuestionGrading::with(['examAttempt', 'question', 'grader'])
            ->latest()
            ->limit(10)
            ->get();

        $gradeDistribution = $this->getGradeDistribution();

        return view('examination::grading.dashboard', compact('stats', 'recentGradings', 'gradeDistribution'));
    }

    private function getAverageGradingTime()
    {
        $gradings = QuestionGrading::whereNotNull('graded_at')
            ->whereNotNull('created_at')
            ->get();

        if ($gradings->isEmpty()) {
            return 0;
        }

        $totalMinutes = $gradings->sum(function($grading) {
            return $grading->created_at->diffInMinutes($grading->graded_at);
        });

        return round($totalMinutes / $gradings->count(), 2);
    }

    private function getGradeDistribution()
    {
        return QuestionGrading::selectRaw('
                CASE 
                    WHEN (marks_obtained / total_marks) >= 0.9 THEN "A+"
                    WHEN (marks_obtained / total_marks) >= 0.8 THEN "A"
                    WHEN (marks_obtained / total_marks) >= 0.7 THEN "B+"
                    WHEN (marks_obtained / total_marks) >= 0.6 THEN "B"
                    WHEN (marks_obtained / total_marks) >= 0.5 THEN "C+"
                    WHEN (marks_obtained / total_marks) >= 0.4 THEN "C"
                    WHEN (marks_obtained / total_marks) >= 0.3 THEN "D"
                    ELSE "F"
                END as grade,
                COUNT(*) as count
            ')
            ->where('status', 'graded')
            ->groupBy('grade')
            ->orderBy('count', 'desc')
            ->get();
    }

    public function exportGrades(Request $request)
    {
        $request->validate([
            'exam_id' => 'nullable|exists:exams,id',
            'status' => 'nullable|in:pending,graded,moderated,disputed',
            'format' => 'required|in:csv,excel',
        ]);

        $query = QuestionGrading::with(['examAttempt.exam', 'question', 'grader', 'student']);

        if ($request->exam_id) {
            $query->whereHas('examAttempt', function($q) use ($request) {
                $q->where('exam_id', $request->exam_id);
            });
        }

        if ($request->status) {
            $query->where('status', $request->status);
        }

        $gradings = $query->get();

        if ($request->format === 'csv') {
            return $this->exportToCsv($gradings);
        } else {
            return $this->exportToExcel($gradings);
        }
    }

    private function exportToCsv($gradings)
    {
        $filename = 'gradings_' . now()->format('Y-m-d_H-i-s') . '.csv';
        
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ];

        $callback = function() use ($gradings) {
            $file = fopen('php://output', 'w');
            
            // Headers
            fputcsv($file, [
                'Student Name', 'Exam Name', 'Question', 'Marks Obtained', 
                'Total Marks', 'Percentage', 'Grade', 'Status', 'Grader', 
                'Graded At', 'Feedback'
            ]);

            // Data
            foreach ($gradings as $grading) {
                fputcsv($file, [
                    $grading->examAttempt->student->name ?? 'N/A',
                    $grading->examAttempt->exam->name ?? 'N/A',
                    $grading->question->question_text ?? 'N/A',
                    $grading->marks_obtained,
                    $grading->total_marks,
                    $grading->percentage . '%',
                    $grading->grade ?? 'N/A',
                    $grading->status,
                    $grading->grader->name ?? 'N/A',
                    $grading->graded_at ? $grading->graded_at->format('Y-m-d H:i:s') : 'N/A',
                    $grading->feedback ?? 'N/A'
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    private function exportToExcel($gradings)
    {
        // This would require Laravel Excel package
        // For now, return CSV
        return $this->exportToCsv($gradings);
    }
}
