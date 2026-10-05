<?php

namespace Modules\Examination\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Examination\Services\NotificationService;

class NotificationController extends Controller
{
    private $notificationService;

    public function __construct(NotificationService $notificationService)
    {
        $this->notificationService = $notificationService;
    }

    public function sendExamScheduled($examId)
    {
        $result = $this->notificationService->sendExamScheduledNotification($examId);
        
        return response()->json($result);
    }

    public function sendExamReminder(Request $request, $examId)
    {
        $request->validate([
            'reminder_type' => 'required|in:24_hours,1_hour,15_minutes'
        ]);

        $result = $this->notificationService->sendExamReminderNotification($examId, $request->reminder_type);
        
        return response()->json($result);
    }

    public function sendExamCompleted($attemptId)
    {
        $result = $this->notificationService->sendExamCompletedNotification($attemptId);
        
        return response()->json($result);
    }

    public function sendGradeAvailable($gradingId)
    {
        $result = $this->notificationService->sendGradeAvailableNotification($gradingId);
        
        return response()->json($result);
    }

    public function sendProctoringAlert(Request $request, $attemptId)
    {
        $request->validate([
            'alert_type' => 'required|string',
            'severity' => 'required|in:low,medium,high,critical'
        ]);

        $result = $this->notificationService->sendProctoringAlertNotification(
            $attemptId, 
            $request->alert_type, 
            $request->severity
        );
        
        return response()->json($result);
    }

    public function sendBulkNotification(Request $request)
    {
        $request->validate([
            'user_ids' => 'required|array',
            'user_ids.*' => 'exists:users,id',
            'type' => 'required|in:exam_scheduled,exam_reminder,grade_available,custom',
            'data' => 'required|array'
        ]);

        $result = $this->notificationService->sendBulkNotification(
            $request->user_ids,
            $request->type,
            $request->data
        );
        
        return response()->json($result);
    }

    public function scheduleReminderNotifications($examId)
    {
        $result = $this->notificationService->scheduleReminderNotifications($examId);
        
        return response()->json($result);
    }

    public function getNotificationStats(Request $request)
    {
        $dateRange = null;
        if ($request->has('start_date') && $request->has('end_date')) {
            $dateRange = [$request->start_date, $request->end_date];
        }

        $stats = $this->notificationService->getNotificationStats($dateRange);
        
        return response()->json([
            'success' => true,
            'stats' => $stats
        ]);
    }
}
