<?php

namespace Modules\Examination\app\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\View\View;
use Modules\Examination\app\Models\Exam;
use Modules\Examination\app\Models\ExamAttempt;
use Modules\Examination\app\Models\ProctoringLog;

class ProctoringController extends Controller
{
    public function index(): View
    {
        $activeExams = Exam::with(['attempts'])
                          ->where('enable_proctoring', true)
                          ->where('status', 'ongoing')
                          ->get();

        $logs = ProctoringLog::with(['attempt.student', 'attempt.exam'])
                                    ->whereIn('severity', ['high', 'critical'])
                                    ->where('is_resolved', false)
                                    ->orderBy('created_at', 'desc')
                                    ->take(10)
                                    ->get();

        return view('examination::proctoring.index', compact('activeExams', 'logs'));
    }

    public function liveMonitoring(Exam $exam): View
    {
        $attempts = $exam->attempts()
                        ->with(['student', 'proctoringLogs'])
                        ->whereIn('status', ['started', 'in_progress'])
                        ->get();

        return view('examination::proctoring.live', compact('exam', 'attempts'));
    }

    public function logs(ExamAttempt $attempt): View
    {
        $logs = $attempt->proctoringLogs()
                       ->orderBy('created_at', 'desc')
                       ->paginate(20);

        return view('examination::proctoring.logs', compact('attempt', 'logs'));
    }

    public function resolveLog(Request $request, ProctoringLog $log): JsonResponse
    {
        $validator = \Validator::make($request->all(), [
            'resolution_notes' => 'required|string'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $log->update([
                'is_resolved' => true,
                'resolution_notes' => $request->resolution_notes,
                'resolved_by' => auth()->id(),
                'resolved_at' => now()
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Alert resolved successfully'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to resolve alert: ' . $e->getMessage()
            ], 500);
        }
    }

    public function analytics(): View
    {
        $stats = [
            'total_alerts' => ProctoringLog::count(),
            'unresolved_alerts' => ProctoringLog::where('is_resolved', false)->count(),
            'critical_alerts' => ProctoringLog::where('severity', 'critical')->count(),
            'high_alerts' => ProctoringLog::where('severity', 'high')->count(),
        ];

        $alertTypes = ProctoringLog::selectRaw('event_type, COUNT(*) as count')
                                  ->groupBy('event_type')
                                  ->orderBy('count', 'desc')
                                  ->get();

        $dailyAlerts = ProctoringLog::selectRaw('DATE(created_at) as date, COUNT(*) as count')
                                   ->groupBy('date')
                                   ->orderBy('date', 'desc')
                                   ->take(30)
                                   ->get();

        return view('examination::proctoring.analytics', compact('stats', 'alertTypes', 'dailyAlerts'));
    }

    public function dashboard(): View|\Illuminate\Contracts\View\View|\Illuminate\Contracts\View\Factory
    {
        $exams = Exam::with(['attempts.student', 'attempts.proctoringLogs'])
            ->where('enable_proctoring', true)
            ->where('is_online', true)
            ->where('status', 'ongoing')
            ->get();

        $recentLogs = ProctoringLog::with(['attempt.student', 'attempt.exam'])
            ->orderBy('created_at', 'desc')
            ->take(50)
            ->get();

        if (request()->ajax() || request('ajax') == 1) {
            return view('examination::proctoring.partials.alerts', compact('recentLogs'));
        }

        return view('examination::proctoring.dashboard', compact('exams', 'recentLogs'));
    }
} 