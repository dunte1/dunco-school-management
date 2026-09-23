<?php

namespace Modules\Examination\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\File;
use Modules\Examination\Models\ProctoringLog;
use Modules\Examination\Models\Exam;
use Modules\Examination\Models\ExamAttempt;

class ProctoringController extends Controller
{
    protected $settingsPath;

    public function __construct()
    {
        $this->settingsPath = storage_path('exam_proctoring_settings.json');
    }

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
        $settings = $this->loadSettings();

        return view('examination::proctoring.settings', compact('settings'));
    }

    public function updateSettings(Request $request)
    {
        $validated = $request->validate([
            'proctor_webcam' => 'boolean',
            'proctor_tab_switch' => 'boolean',
            'proctor_face_detection' => 'boolean',
            'proctor_idle_timeout' => 'nullable|integer|min:30|max:600',
        ]);

        $settings = $this->loadSettings();
        $settings = array_merge($settings, [
            'proctor_webcam' => $request->boolean('proctor_webcam', false),
            'proctor_tab_switch' => $request->boolean('proctor_tab_switch', false),
            'proctor_face_detection' => $request->boolean('proctor_face_detection', false),
            'proctor_idle_timeout' => $validated['proctor_idle_timeout'] ?? $settings['proctor_idle_timeout'] ?? 120,
            'updated_at' => now()->toIso8601String(),
        ]);

        $this->saveSettings($settings);

        return redirect()->route('examination.proctoring.settings')
            ->with('success', 'Proctoring settings updated successfully.');
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

    protected function loadSettings(): array
    {
        $defaults = [
            'proctor_webcam' => false,
            'proctor_tab_switch' => true,
            'proctor_face_detection' => false,
            'proctor_idle_timeout' => 120,
        ];

        if (!File::exists($this->settingsPath)) {
            return $defaults;
        }

        $content = File::get($this->settingsPath);
        $stored = json_decode($content, true);

        return is_array($stored) ? array_merge($defaults, $stored) : $defaults;
    }

    protected function saveSettings(array $settings): void
    {
        File::put($this->settingsPath, json_encode($settings, JSON_PRETTY_PRINT));
    }
}
