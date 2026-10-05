<?php

namespace Modules\Examination\Services;

use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Notification;
use Modules\Examination\Models\Exam;
use Modules\Examination\Models\ExamAttempt;
use Modules\Examination\Models\User;
use Modules\Examination\Notifications\ExamScheduledNotification;
use Modules\Examination\Notifications\ExamReminderNotification;
use Modules\Examination\Notifications\ExamCompletedNotification;
use Modules\Examination\Notifications\GradeAvailableNotification;
use Modules\Examination\Notifications\ProctoringAlertNotification;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Queue;

class NotificationService
{
    public function sendExamScheduledNotification($examId, $studentIds = null)
    {
        $exam = Exam::findOrFail($examId);
        
        if (!$studentIds) {
            $studentIds = $exam->schedules()
                ->where('status', 'active')
                ->pluck('student_id')
                ->toArray();
        }

        $students = User::whereIn('id', $studentIds)->get();

        foreach ($students as $student) {
            try {
                $student->notify(new ExamScheduledNotification($exam));
                
                // Send email notification
                if ($student->email) {
                    $this->sendEmailNotification($student, 'exam_scheduled', [
                        'exam' => $exam,
                        'student' => $student
                    ]);
                }

                // Send SMS if phone number exists
                if ($student->phone) {
                    $this->sendSMSNotification($student, 'exam_scheduled', [
                        'exam_name' => $exam->name,
                        'exam_date' => $exam->start_date,
                        'exam_time' => $exam->start_time
                    ]);
                }

            } catch (\Exception $e) {
                Log::error('Failed to send exam scheduled notification: ' . $e->getMessage());
            }
        }

        return [
            'success' => true,
            'message' => 'Exam scheduled notifications sent to ' . count($students) . ' students',
            'sent_count' => count($students)
        ];
    }

    public function sendExamReminderNotification($examId, $reminderType = '24_hours')
    {
        $exam = Exam::findOrFail($examId);
        
        $studentIds = $exam->schedules()
            ->where('status', 'active')
            ->pluck('student_id')
            ->toArray();

        $students = User::whereIn('id', $studentIds)->get();

        foreach ($students as $student) {
            try {
                $student->notify(new ExamReminderNotification($exam, $reminderType));
                
                if ($student->email) {
                    $this->sendEmailNotification($student, 'exam_reminder', [
                        'exam' => $exam,
                        'student' => $student,
                        'reminder_type' => $reminderType
                    ]);
                }

            } catch (\Exception $e) {
                Log::error('Failed to send exam reminder notification: ' . $e->getMessage());
            }
        }

        return [
            'success' => true,
            'message' => 'Exam reminder notifications sent to ' . count($students) . ' students',
            'sent_count' => count($students)
        ];
    }

    public function sendExamCompletedNotification($attemptId)
    {
        $attempt = ExamAttempt::with(['exam', 'student'])->findOrFail($attemptId);
        
        try {
            $attempt->student->notify(new ExamCompletedNotification($attempt));
            
            if ($attempt->student->email) {
                $this->sendEmailNotification($attempt->student, 'exam_completed', [
                    'attempt' => $attempt,
                    'exam' => $attempt->exam,
                    'student' => $attempt->student
                ]);
            }

            return [
                'success' => true,
                'message' => 'Exam completed notification sent successfully'
            ];

        } catch (\Exception $e) {
            Log::error('Failed to send exam completed notification: ' . $e->getMessage());
            return [
                'success' => false,
                'message' => 'Failed to send notification: ' . $e->getMessage()
            ];
        }
    }

    public function sendGradeAvailableNotification($gradingId)
    {
        $grading = \Modules\Examination\Models\QuestionGrading::with(['examAttempt.student', 'examAttempt.exam', 'question'])
            ->findOrFail($gradingId);
        
        try {
            $grading->examAttempt->student->notify(new GradeAvailableNotification($grading));
            
            if ($grading->examAttempt->student->email) {
                $this->sendEmailNotification($grading->examAttempt->student, 'grade_available', [
                    'grading' => $grading,
                    'exam' => $grading->examAttempt->exam,
                    'student' => $grading->examAttempt->student
                ]);
            }

            return [
                'success' => true,
                'message' => 'Grade available notification sent successfully'
            ];

        } catch (\Exception $e) {
            Log::error('Failed to send grade available notification: ' . $e->getMessage());
            return [
                'success' => false,
                'message' => 'Failed to send notification: ' . $e->getMessage()
            ];
        }
    }

    public function sendProctoringAlertNotification($attemptId, $alertType, $severity = 'medium')
    {
        $attempt = ExamAttempt::with(['exam', 'student'])->findOrFail($attemptId);
        
        try {
            // Notify proctors
            $proctors = User::where('role', 'proctor')->orWhere('role', 'admin')->get();
            
            foreach ($proctors as $proctor) {
                $proctor->notify(new ProctoringAlertNotification($attempt, $alertType, $severity));
            }
            
            // Send email to proctors
            foreach ($proctors as $proctor) {
                if ($proctor->email) {
                    $this->sendEmailNotification($proctor, 'proctoring_alert', [
                        'attempt' => $attempt,
                        'exam' => $attempt->exam,
                        'student' => $attempt->student,
                        'alert_type' => $alertType,
                        'severity' => $severity
                    ]);
                }
            }

            return [
                'success' => true,
                'message' => 'Proctoring alert notifications sent successfully'
            ];

        } catch (\Exception $e) {
            Log::error('Failed to send proctoring alert notification: ' . $e->getMessage());
            return [
                'success' => false,
                'message' => 'Failed to send notification: ' . $e->getMessage()
            ];
        }
    }

    public function sendBulkNotification($userIds, $type, $data)
    {
        $users = User::whereIn('id', $userIds)->get();
        $sentCount = 0;

        foreach ($users as $user) {
            try {
                switch ($type) {
                    case 'exam_scheduled':
                        $user->notify(new ExamScheduledNotification($data['exam']));
                        break;
                    case 'exam_reminder':
                        $user->notify(new ExamReminderNotification($data['exam'], $data['reminder_type']));
                        break;
                    case 'grade_available':
                        $user->notify(new GradeAvailableNotification($data['grading']));
                        break;
                    case 'custom':
                        $user->notify(new \Modules\Examination\Notifications\CustomNotification($data['subject'], $data['message']));
                        break;
                }

                $sentCount++;

            } catch (\Exception $e) {
                Log::error('Failed to send bulk notification to user ' . $user->id . ': ' . $e->getMessage());
            }
        }

        return [
            'success' => true,
            'message' => "Bulk notifications sent to {$sentCount} users",
            'sent_count' => $sentCount
        ];
    }

    public function scheduleReminderNotifications($examId)
    {
        $exam = Exam::findOrFail($examId);
        
        // Schedule 24-hour reminder
        Queue::later(
            now()->addHours(24),
            function() use ($examId) {
                $this->sendExamReminderNotification($examId, '24_hours');
            }
        );

        // Schedule 1-hour reminder
        Queue::later(
            now()->addHours(1),
            function() use ($examId) {
                $this->sendExamReminderNotification($examId, '1_hour');
            }
        );

        // Schedule 15-minute reminder
        Queue::later(
            now()->addMinutes(15),
            function() use ($examId) {
                $this->sendExamReminderNotification($examId, '15_minutes');
            }
        );

        return [
            'success' => true,
            'message' => 'Reminder notifications scheduled successfully'
        ];
    }

    private function sendEmailNotification($user, $type, $data)
    {
        try {
            $emailData = $this->prepareEmailData($type, $data);
            
            Mail::send("examination::emails.{$type}", $emailData, function($message) use ($user, $type) {
                $message->to($user->email, $user->name)
                        ->subject($this->getEmailSubject($type));
            });

        } catch (\Exception $e) {
            Log::error('Failed to send email notification: ' . $e->getMessage());
        }
    }

    private function sendSMSNotification($user, $type, $data)
    {
        try {
            $message = $this->prepareSMSMessage($type, $data);
            
            // This would integrate with an SMS service like Twilio
            // For now, just log the message
            Log::info("SMS to {$user->phone}: {$message}");

        } catch (\Exception $e) {
            Log::error('Failed to send SMS notification: ' . $e->getMessage());
        }
    }

    private function prepareEmailData($type, $data)
    {
        switch ($type) {
            case 'exam_scheduled':
                return [
                    'exam' => $data['exam'],
                    'student' => $data['student'],
                    'exam_url' => route('examination.online.exam', $data['exam']->id)
                ];
            
            case 'exam_reminder':
                return [
                    'exam' => $data['exam'],
                    'student' => $data['student'],
                    'reminder_type' => $data['reminder_type'],
                    'exam_url' => route('examination.online.exam', $data['exam']->id)
                ];
            
            case 'exam_completed':
                return [
                    'attempt' => $data['attempt'],
                    'exam' => $data['exam'],
                    'student' => $data['student'],
                    'results_url' => route('examination.results.show', $data['attempt']->id)
                ];
            
            case 'grade_available':
                return [
                    'grading' => $data['grading'],
                    'exam' => $data['exam'],
                    'student' => $data['student'],
                    'results_url' => route('examination.results.show', $data['grading']->exam_attempt_id)
                ];
            
            case 'proctoring_alert':
                return [
                    'attempt' => $data['attempt'],
                    'exam' => $data['exam'],
                    'student' => $data['student'],
                    'alert_type' => $data['alert_type'],
                    'severity' => $data['severity'],
                    'proctoring_url' => route('examination.proctoring.student-details', $data['attempt']->id)
                ];
            
            default:
                return $data;
        }
    }

    private function getEmailSubject($type)
    {
        $subjects = [
            'exam_scheduled' => 'New Exam Scheduled',
            'exam_reminder' => 'Exam Reminder',
            'exam_completed' => 'Exam Completed',
            'grade_available' => 'Grade Available',
            'proctoring_alert' => 'Proctoring Alert',
        ];

        return $subjects[$type] ?? 'Notification';
    }

    private function prepareSMSMessage($type, $data)
    {
        switch ($type) {
            case 'exam_scheduled':
                return "New exam '{$data['exam_name']}' scheduled for {$data['exam_date']} at {$data['exam_time']}. Check your dashboard for details.";
            
            case 'exam_reminder':
                return "Reminder: Exam '{$data['exam_name']}' starts soon. Please log in to your dashboard.";
            
            case 'exam_completed':
                return "Your exam has been completed. Results will be available shortly.";
            
            case 'grade_available':
                return "Your grade for the exam is now available. Check your dashboard for details.";
            
            default:
                return "You have a new notification. Please check your dashboard.";
        }
    }

    public function getNotificationStats($dateRange = null)
    {
        $query = \Modules\Examination\Models\Notification::query();
        
        if ($dateRange) {
            $query->whereBetween('created_at', $dateRange);
        }

        $notifications = $query->get();

        return [
            'total_sent' => $notifications->count(),
            'by_type' => $notifications->groupBy('type')->map(function($group) {
                return $group->count();
            }),
            'by_channel' => $notifications->groupBy('channel')->map(function($group) {
                return $group->count();
            }),
            'success_rate' => $notifications->where('status', 'sent')->count() / max($notifications->count(), 1) * 100,
        ];
    }
}
