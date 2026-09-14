<?php

namespace Modules\Examination\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

class ProctoringController extends Controller
{
    public function index()
    {
        $logs = collect([
            (object)[
                'id' => 1,
                'exam_name' => 'Mathematics Final',
                'student_name' => 'John Doe',
                'event_type' => 'tab_switch',
                'severity' => 'medium',
                'is_resolved' => true,
                'created_at' => \Carbon\Carbon::parse('2024-12-15 10:30:00')
            ],
            (object)[
                'id' => 2,
                'exam_name' => 'Physics Midterm',
                'student_name' => 'Jane Smith',
                'event_type' => 'multiple_windows',
                'severity' => 'high',
                'is_resolved' => false,
                'created_at' => \Carbon\Carbon::parse('2024-12-15 11:15:00')
            ],
            (object)[
                'id' => 3,
                'exam_name' => 'English Literature',
                'student_name' => 'Mike Johnson',
                'event_type' => 'copy_paste',
                'severity' => 'critical',
                'is_resolved' => true,
                'created_at' => \Carbon\Carbon::parse('2024-12-15 12:00:00')
            ],
            (object)[
                'id' => 4,
                'exam_name' => 'Chemistry Final',
                'student_name' => 'Sarah Wilson',
                'event_type' => 'screen_share',
                'severity' => 'low',
                'is_resolved' => false,
                'created_at' => \Carbon\Carbon::parse('2024-12-15 13:45:00')
            ]
        ]);
        
        // Create a paginated collection
        $perPage = 10;
        $currentPage = request()->get('page', 1);
        $offset = ($currentPage - 1) * $perPage;
        $paginatedLogs = $logs->slice($offset, $perPage);
        
        $logs = new \Illuminate\Pagination\LengthAwarePaginator(
            $paginatedLogs->values(),
            $logs->count(),
            $perPage,
            $currentPage,
            ['path' => request()->url(), 'pageName' => 'page']
        );
        
        return view('examination::proctoring.index', compact('logs'));
    }

    public function monitor($examId)
    {
        $exam = \Modules\Examination\Models\Exam::find($examId);

        return view('examination::proctoring.monitor', compact('exam', 'examId'));
    }

    public function logs($examId)
    {
        $logs = collect([
            (object)[
                'id' => 1,
                'event_type' => 'Tab Switch',
                'timestamp' => '2024-12-15 10:30:00',
                'description' => 'Student switched to another tab',
                'severity' => 'Medium'
            ],
            (object)[
                'id' => 2,
                'event_type' => 'Multiple Windows',
                'timestamp' => '2024-12-15 11:15:00',
                'description' => 'Multiple browser windows detected',
                'severity' => 'High'
            ]
        ]);
        
        // Create a paginated collection
        $perPage = 10;
        $currentPage = request()->get('page', 1);
        $offset = ($currentPage - 1) * $perPage;
        $paginatedLogs = $logs->slice($offset, $perPage);
        
        $logs = new \Illuminate\Pagination\LengthAwarePaginator(
            $paginatedLogs->values(),
            $logs->count(),
            $perPage,
            $currentPage,
            ['path' => request()->url(), 'pageName' => 'page']
        );
        
        return view('examination::proctoring.logs', compact('logs'));
    }

    public function settings()
    {
        return view('examination::proctoring.settings');
    }

    public function updateSettings(Request $request)
    {
        // Proctoring settings update logic
        return redirect()->route('examination.proctoring.settings')->with('success', 'Settings updated');
    }

    public function liveMonitoring($exam)
    {
        $examModel = \Modules\Examination\Models\Exam::find($exam);

        return view('examination::proctoring.live', compact('examModel', 'exam'));
    }

    public function resolveLog($log)
    {
        return redirect()->back()->with('success', 'Log resolved successfully');
    }

    public function analytics()
    {
        return view('examination::proctoring.analytics');
    }

    public function dashboard()
    {
        $exams = \Modules\Examination\Models\Exam::withCount('attempts')->orderByDesc('start_date')->limit(10)->get();
        $recentLogs = \Modules\Examination\Models\ProctoringLog::with('attempt')->latest()->limit(10)->get();

        return view('examination::proctoring.dashboard', compact('exams', 'recentLogs'));
    }
}
