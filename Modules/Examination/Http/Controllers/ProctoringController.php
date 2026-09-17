<?php

namespace Modules\Examination\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Examination\Models\ProctoringLog;
use Modules\Examination\Models\Exam;
use Modules\Examination\Models\ExamAttempt;

class ProctoringController extends Controller
{
    public function index()
    {
        $logs = ProctoringLog::with('attempt.exam', 'attempt.student')
            ->orderByDesc('created_at')
            ->paginate(15);

        return view('examination::proctoring.index', compact('logs'));
    }

    public function monitor($examId)
    {
        $exam = Exam::find($examId);
        $attempts = ExamAttempt::where('exam_id', $examId)
            ->with('student')
            ->where('status', '!=', 'completed')
            ->get();

        return view('examination::proctoring.monitor', compact('exam', 'attempts', 'examId'));
    }

    public function logs($examId)
    {
        $logs = ProctoringLog::whereHas('attempt', function ($q) use ($examId) {
            $q->where('exam_id', $examId);
        })->with('attempt.student')->orderByDesc('created_at')->paginate(15);

        return view('examination::proctoring.logs', compact('logs', 'examId'));
    }

    public function settings()
    {
        return view('examination::proctoring.settings');
    }

    public function updateSettings(Request $request)
    {
        $request->validate([
            'tab_switch_detection' => 'boolean',
            'webcam_required' => 'boolean',
            'face_detection' => 'boolean',
        ]);

        return redirect()->back()->with('success', 'Proctoring settings updated.');
    }

    public function liveMonitoring($exam)
    {
        $examModel = Exam::find($exam);
        $activeAttempts = ExamAttempt::where('exam_id', $exam)
            ->with('student')
            ->whereIn('status', ['started', 'in_progress'])
            ->get();

        return view('examination::proctoring.live', compact('examModel', 'exam', 'activeAttempts'));
    }

    public function resolveLog($log)
    {
        $logEntry = ProctoringLog::findOrFail($log);
        $logEntry->update([
            'is_resolved' => true,
            'resolved_by' => auth()->id(),
            'resolved_at' => now(),
        ]);

        return redirect()->back()->with('success', 'Log resolved successfully.');
    }

    public function analytics()
    {
        $stats = [
            'total_events' => ProctoringLog::count(),
            'unresolved' => ProctoringLog::where('is_resolved', false)->count(),
            'critical' => ProctoringLog::where('severity', 'critical')->count(),
        ];

        return view('examination::proctoring.analytics', compact('stats'));
    }

    public function dashboard()
    {
        $recentLogs = ProctoringLog::with('attempt.exam', 'attempt.student')
            ->orderByDesc('created_at')
            ->limit(10)
            ->get();

        $activeExams = Exam::where('status', 'ongoing')
            ->withCount('attempts')
            ->get();

        return view('examination::proctoring.dashboard', compact('recentLogs', 'activeExams'));
    }
}
