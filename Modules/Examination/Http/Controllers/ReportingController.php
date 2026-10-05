<?php

namespace Modules\Examination\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Examination\Services\ReportingService;
use Modules\Examination\Models\Exam;
use App\Models\User;
use Carbon\Carbon;

class ReportingController extends Controller
{
    private $reportingService;

    public function __construct(ReportingService $reportingService)
    {
        $this->reportingService = $reportingService;
    }

    public function dashboard()
    {
        $dateRange = [
            now()->subDays(30)->startOfDay(),
            now()->endOfDay()
        ];

        // Get basic statistics
        $stats = [
            'total_exams' => Exam::count(),
            'total_students' => \Modules\Examination\Models\ExamAttempt::distinct('student_id')->count(),
            'avg_score' => \Modules\Examination\Models\ExamAttempt::where('status', 'submitted')
                ->avg('obtained_marks') ?? 0,
            'completion_rate' => \Modules\Examination\Models\ExamAttempt::where('status', 'submitted')
                ->count() / max(\Modules\Examination\Models\ExamAttempt::count(), 1) * 100
        ];

        $recentExams = Exam::with(['attempts', 'questions'])
            ->where('created_at', '>=', now()->subDays(7))
            ->latest()
            ->limit(5)
            ->get();

        return view('examination::reports.dashboard', compact('stats', 'recentExams'));
    }

    public function examAnalytics($examId)
    {
        $exam = Exam::findOrFail($examId);
        
        // Get basic exam statistics
        $stats = [
            'total_attempts' => \Modules\Examination\Models\ExamAttempt::where('exam_id', $examId)->count(),
            'avg_score' => \Modules\Examination\Models\ExamAttempt::where('exam_id', $examId)
                ->where('status', 'submitted')
                ->avg('obtained_marks') ?? 0,
            'pass_rate' => \Modules\Examination\Models\ExamAttempt::where('exam_id', $examId)
                ->where('status', 'submitted')
                ->where('obtained_marks', '>=', $exam->passing_marks)
                ->count() / max(\Modules\Examination\Models\ExamAttempt::where('exam_id', $examId)->where('status', 'submitted')->count(), 1) * 100,
            'completion_rate' => \Modules\Examination\Models\ExamAttempt::where('exam_id', $examId)
                ->where('status', 'submitted')
                ->count() / max(\Modules\Examination\Models\ExamAttempt::where('exam_id', $examId)->count(), 1) * 100
        ];
        
        return view('examination::reports.exam-analytics', compact('exam', 'stats'));
    }

    public function studentPerformance($studentId = null)
    {
        if (!$studentId) {
            $studentId = auth()->id();
        }

        $student = User::findOrFail($studentId);
        
        // Get basic performance statistics
        $stats = [
            'total_students' => \Modules\Examination\Models\ExamAttempt::distinct('student_id')->count(),
            'avg_performance' => \Modules\Examination\Models\ExamAttempt::where('student_id', $studentId)
                ->where('status', 'submitted')
                ->avg('obtained_marks') ?? 0,
            'improvement_rate' => 5, // Placeholder - would need historical data
            'active_students' => \Modules\Examination\Models\ExamAttempt::where('created_at', '>=', now()->subDays(30))
                ->distinct('student_id')
                ->count()
        ];
        
        return view('examination::reports.student-performance', compact('student', 'stats'));
    }

    public function proctoringReport($examId = null)
    {
        $dateRange = request('date_range') ? [
            Carbon::parse(request('date_range')[0])->startOfDay(),
            Carbon::parse(request('date_range')[1])->endOfDay()
        ] : null;

        $report = $this->reportingService->getProctoringReport($examId, $dateRange);
        
        return view('examination::reports.proctoring', compact('report'));
    }

    public function exportReport(Request $request)
    {
        $request->validate([
            'report_type' => 'required|in:exam_analytics,student_performance,proctoring,system_analytics',
            'format' => 'required|in:csv,excel,pdf',
            'exam_id' => 'nullable|exists:exams,id',
            'student_id' => 'nullable|integer',
            'date_range' => 'nullable|array|size:2',
        ]);

        $dateRange = $request->date_range ? [
            Carbon::parse($request->date_range[0])->startOfDay(),
            Carbon::parse($request->date_range[1])->endOfDay()
        ] : null;

        switch ($request->report_type) {
            case 'exam_analytics':
                $data = $this->reportingService->getExamAnalytics($request->exam_id, $dateRange);
                $filename = 'exam_analytics_' . now()->format('Y-m-d_H-i-s');
                break;
            
            case 'student_performance':
                $data = $this->getStudentPerformanceData($request->student_id, $dateRange);
                $filename = 'student_performance_' . now()->format('Y-m-d_H-i-s');
                break;
            
            case 'proctoring':
                $data = $this->reportingService->getProctoringReport($request->exam_id, $dateRange);
                $filename = 'proctoring_report_' . now()->format('Y-m-d_H-i-s');
                break;
            
            case 'system_analytics':
                $data = $this->reportingService->getSystemAnalytics($dateRange);
                $filename = 'system_analytics_' . now()->format('Y-m-d_H-i-s');
                break;
        }

        if ($request->format === 'csv') {
            return $this->exportToCsv($data, $filename);
        } elseif ($request->format === 'excel') {
            return $this->exportToExcel($data, $filename);
        } else {
            return $this->exportToPdf($data, $filename, $request->report_type);
        }
    }

    public function getChartData(Request $request)
    {
        $request->validate([
            'chart_type' => 'required|in:grade_distribution,performance_trend,question_analysis,usage_trends',
            'exam_id' => 'nullable|exists:exams,id',
            'student_id' => 'nullable|integer',
            'date_range' => 'nullable|array|size:2',
        ]);

        $dateRange = $request->date_range ? [
            Carbon::parse($request->date_range[0])->startOfDay(),
            Carbon::parse($request->date_range[1])->endOfDay()
        ] : null;

        switch ($request->chart_type) {
            case 'grade_distribution':
                $data = $this->getGradeDistributionData($request->exam_id, $dateRange);
                break;
            
            case 'performance_trend':
                $data = $this->getPerformanceTrendData($request->student_id, $dateRange);
                break;
            
            case 'question_analysis':
                $data = $this->getQuestionAnalysisData($request->exam_id, $dateRange);
                break;
            
            case 'usage_trends':
                $data = $this->getUsageTrendsData($dateRange);
                break;
        }

        return response()->json([
            'success' => true,
            'data' => $data
        ]);
    }

    private function getGradeDistributionData($examId, $dateRange)
    {
        $query = \Modules\Examination\Models\ExamAttempt::query();
        
        if ($examId) {
            $query->where('exam_id', $examId);
        }
        
        if ($dateRange) {
            $query->whereBetween('created_at', $dateRange);
        }

        $attempts = $query->where('status', 'submitted')->get();

        $distribution = [
            'A+' => 0, 'A' => 0, 'B+' => 0, 'B' => 0,
            'C+' => 0, 'C' => 0, 'D' => 0, 'F' => 0
        ];

        foreach ($attempts as $attempt) {
            $percentage = $attempt->total_marks > 0 
                ? ($attempt->obtained_marks / $attempt->total_marks) * 100 
                : 0;

            if ($percentage >= 90) $distribution['A+']++;
            elseif ($percentage >= 80) $distribution['A']++;
            elseif ($percentage >= 70) $distribution['B+']++;
            elseif ($percentage >= 60) $distribution['B']++;
            elseif ($percentage >= 50) $distribution['C+']++;
            elseif ($percentage >= 40) $distribution['C']++;
            elseif ($percentage >= 30) $distribution['D']++;
            else $distribution['F']++;
        }

        return [
            'labels' => array_keys($distribution),
            'data' => array_values($distribution),
            'colors' => [
                '#28a745', '#20c997', '#17a2b8', '#007bff',
                '#6f42c1', '#fd7e14', '#dc3545', '#6c757d'
            ]
        ];
    }

    private function getPerformanceTrendData($studentId, $dateRange)
    {
        $query = \Modules\Examination\Models\ExamAttempt::where('student_id', $studentId);
        
        if ($dateRange) {
            $query->whereBetween('created_at', $dateRange);
        }

        $attempts = $query->where('status', 'submitted')
            ->orderBy('created_at')
            ->get();

        $data = [
            'labels' => [],
            'scores' => [],
            'dates' => []
        ];

        foreach ($attempts as $attempt) {
            $percentage = $attempt->total_marks > 0 
                ? round(($attempt->obtained_marks / $attempt->total_marks) * 100, 2) 
                : 0;

            $data['labels'][] = $attempt->exam->name ?? 'Exam ' . $attempt->id;
            $data['scores'][] = $percentage;
            $data['dates'][] = $attempt->created_at->format('Y-m-d');
        }

        return $data;
    }

    private function getQuestionAnalysisData($examId, $dateRange)
    {
        $analytics = $this->reportingService->getExamAnalytics($examId, $dateRange);
        $questionAnalysis = $analytics['question_analysis'] ?? [];

        $data = [
            'labels' => [],
            'accuracy' => [],
            'difficulty' => []
        ];

        foreach ($questionAnalysis as $question) {
            $data['labels'][] = 'Q' . $question['question_id'];
            $data['accuracy'][] = $question['accuracy_rate'];
            $data['difficulty'][] = $question['difficulty_level'];
        }

        return $data;
    }

    private function getUsageTrendsData($dateRange)
    {
        $query = \Modules\Examination\Models\ExamAttempt::query();
        
        if ($dateRange) {
            $query->whereBetween('created_at', $dateRange);
        }

        $attempts = $query->get();

        $dailyUsage = $attempts->groupBy(function($attempt) {
            return $attempt->created_at->format('Y-m-d');
        })->map(function($group) {
            return $group->count();
        });

        return [
            'labels' => $dailyUsage->keys()->toArray(),
            'data' => $dailyUsage->values()->toArray()
        ];
    }

    private function exportToCsv($data, $filename)
    {
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $filename . '.csv"',
        ];

        $callback = function() use ($data) {
            $file = fopen('php://output', 'w');
            
            // This is a simplified export - in practice, you'd want to flatten the data structure
            fputcsv($file, ['Report Data']);
            fputcsv($file, ['Generated At', now()->format('Y-m-d H:i:s')]);
            fputcsv($file, []);
            
            // Add your data export logic here based on the report type
            fputcsv($file, ['Data', json_encode($data)]);
            
            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    private function exportToExcel($data, $filename)
    {
        // This would require Laravel Excel package
        // For now, return CSV
        return $this->exportToCsv($data, $filename);
    }

    private function exportToPdf($data, $filename, $reportType)
    {
        // This would require a PDF generation package like DomPDF or TCPDF
        // For now, return a simple HTML response
        return response()->view('examination::reports.pdf-template', [
            'data' => $data,
            'reportType' => $reportType,
            'generatedAt' => now()
        ])->header('Content-Type', 'application/pdf')
          ->header('Content-Disposition', 'attachment; filename="' . $filename . '.pdf"');
    }

    private function getStudentPerformanceData($studentId, $dateRange)
    {
        $query = \Modules\Examination\Models\ExamAttempt::where('student_id', $studentId);
        
        if ($dateRange) {
            $query->whereBetween('created_at', $dateRange);
        }

        $attempts = $query->where('status', 'submitted')
            ->with(['exam'])
            ->orderBy('created_at')
            ->get();

        return [
            'student_id' => $studentId,
            'attempts' => $attempts,
            'total_attempts' => $attempts->count(),
            'average_score' => $attempts->avg('obtained_marks') ?? 0,
            'total_marks' => $attempts->sum('total_marks') ?? 0,
            'obtained_marks' => $attempts->sum('obtained_marks') ?? 0,
        ];
    }
}
