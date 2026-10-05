<?php

namespace Modules\Examination\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Examination\Models\ExamAttempt;
use Modules\Examination\Models\ProctoringLog;
use Illuminate\Support\Facades\Storage;

class AdvancedProctoringController extends Controller
{
    public function dashboard()
    {
        $activeAttempts = ExamAttempt::where('status', 'in_progress')
            ->with(['exam', 'student'])
            ->get();

        $ongoingExams = ExamAttempt::where('status', 'in_progress')
            ->with(['exam', 'student', 'proctoringLogs'])
            ->get();

        $recentLogs = ProctoringLog::with(['examAttempt.student', 'examAttempt.exam'])
            ->latest()
            ->limit(20)
            ->get();

        // Calculate statistics
        $stats = [
            'active_exams' => $activeAttempts->count(),
            'violations_today' => ProctoringLog::whereDate('created_at', today())
                ->whereIn('severity', ['high', 'critical'])
                ->count(),
            'total_screenshots' => ProctoringLog::where('event_type', 'screenshot')
                ->whereDate('created_at', today())
                ->count(),
            'total_recordings' => ProctoringLog::where('event_type', 'recording')
                ->whereDate('created_at', today())
                ->count(),
        ];

        return view('examination::proctoring.dashboard', compact('activeAttempts', 'ongoingExams', 'recentLogs', 'stats'));
    }

    public function studentDetails($attemptId)
    {
        $attempt = ExamAttempt::with(['exam', 'student', 'proctoringLogs'])
            ->findOrFail($attemptId);

        return view('examination::proctoring.student-details', compact('attempt'));
    }

    public function getLiveFeed($attemptId)
    {
        $attempt = ExamAttempt::findOrFail($attemptId);
        
        // Return live feed data (this would be implemented with WebSockets in production)
        return response()->json([
            'attempt_id' => $attemptId,
            'status' => $attempt->status,
            'timestamp' => now()->toISOString(),
            'feed_url' => route('examination.proctoring.live-feed', $attemptId)
        ]);
    }

    public function logEvent(Request $request, $attemptId)
    {
        $request->validate([
            'event_type' => 'required|string',
            'description' => 'required|string',
            'severity' => 'required|in:low,medium,high,critical',
            'metadata' => 'nullable|array'
        ]);

        $attempt = ExamAttempt::findOrFail($attemptId);

        $log = ProctoringLog::create([
            'exam_attempt_id' => $attemptId,
            'event_type' => $request->event_type,
            'description' => $request->description,
            'severity' => $request->severity,
            'metadata' => $request->metadata ?? [],
            'resolved' => false
        ]);

        return response()->json([
            'success' => true,
            'log_id' => $log->id,
            'message' => 'Event logged successfully'
        ]);
    }

    public function heartbeat(Request $request, $attemptId)
    {
        $attempt = ExamAttempt::findOrFail($attemptId);
        
        $attempt->update([
            'last_activity' => now(),
            'proctoring_data' => array_merge($attempt->proctoring_data ?? [], [
                'last_heartbeat' => now()->toISOString(),
                'browser_info' => $request->browser_info ?? null,
                'screen_resolution' => $request->screen_resolution ?? null
            ])
        ]);

        return response()->json(['success' => true]);
    }

    public function uploadScreenshot(Request $request, $attemptId)
    {
        $request->validate([
            'screenshot' => 'required|image|max:2048', // 2MB max
            'timestamp' => 'required|numeric'
        ]);

        $attempt = ExamAttempt::findOrFail($attemptId);
        
        $path = $request->file('screenshot')->store(
            "examination/proctoring/screenshots/{$attemptId}", 
            'public'
        );

        ProctoringLog::create([
            'exam_attempt_id' => $attemptId,
            'event_type' => 'screenshot_captured',
            'description' => 'Screenshot captured during exam',
            'severity' => 'medium',
            'metadata' => [
                'screenshot_path' => $path,
                'timestamp' => $request->timestamp
            ]
        ]);

        return response()->json([
            'success' => true,
            'path' => $path,
            'url' => Storage::url($path)
        ]);
    }

    public function uploadRecording(Request $request)
    {
        $request->validate([
            'recording' => 'required|file|mimes:webm,mp4|max:102400', // 100MB max
            'attempt_id' => 'required|exists:exam_attempts,id',
            'type' => 'required|in:webcam,screen'
        ]);

        $attempt = ExamAttempt::findOrFail($request->attempt_id);
        
        $path = $request->file('recording')->store(
            "examination/proctoring/recordings/{$request->attempt_id}", 
            'public'
        );

        ProctoringLog::create([
            'exam_attempt_id' => $request->attempt_id,
            'event_type' => 'recording_uploaded',
            'description' => ucfirst($request->type) . ' recording uploaded',
            'severity' => 'low',
            'metadata' => [
                'recording_path' => $path,
                'recording_type' => $request->type,
                'file_size' => $request->file('recording')->getSize()
            ]
        ]);

        return response()->json([
            'success' => true,
            'path' => $path,
            'url' => Storage::url($path)
        ]);
    }

    public function immediateAlert(Request $request)
    {
        $request->validate([
            'attempt_id' => 'required|exists:exam_attempts,id',
            'alert_type' => 'required|string',
            'severity' => 'required|in:high,critical',
            'description' => 'required|string'
        ]);

        $log = ProctoringLog::create([
            'exam_attempt_id' => $request->attempt_id,
            'event_type' => $request->alert_type,
            'description' => $request->description,
            'severity' => $request->severity,
            'metadata' => [
                'immediate_alert' => true,
                'alerted_at' => now()->toISOString()
            ],
            'resolved' => false
        ]);

        // Here you would trigger real-time notifications to proctors
        // This could be done via WebSockets, Pusher, or other real-time systems

        return response()->json([
            'success' => true,
            'alert_id' => $log->id,
            'message' => 'Immediate alert sent to proctors'
        ]);
    }

    public function resolveViolation($logId)
    {
        $log = ProctoringLog::findOrFail($logId);
        
        $log->update([
            'resolved' => true,
            'resolved_at' => now(),
            'resolved_by' => auth()->id()
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Violation resolved successfully'
        ]);
    }
}