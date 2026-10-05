<?php

namespace App\Services\Notifications\UseCases;

use App\Models\AttendanceAlertRule;
use App\Services\Notifications\NotificationDispatcher;
use Modules\Academic\Models\AttendanceRecord;
use Modules\Academic\Models\Student;

class AttendanceAlertRulesEvaluator
{
    public function handle(NotificationDispatcher $dispatcher, ?int $schoolId = null): int
    {
        $rules = AttendanceAlertRule::query()
            ->where('is_active', true)
            ->when($schoolId, fn($q) => $q->where('school_id', $schoolId))
            ->get();

        $queued = 0;
        foreach ($rules as $rule) {
            $queued += $this->evaluateRule($dispatcher, $rule);
            $rule->last_run_at = now();
            $rule->save();
        }
        return $queued;
    }

    private function evaluateRule(NotificationDispatcher $dispatcher, AttendanceAlertRule $rule): int
    {
        $endDate = now()->toDateString();
        $startDate = now()->subDays($rule->window_days - 1)->toDateString();

        $students = Student::query()
            ->when($rule->school_id, fn($q) => $q->where('school_id', $rule->school_id))
            ->with('user')
            ->get();

        $sent = 0;
        foreach ($students as $student) {
            if (!$student->user) { continue; }

            if ($rule->type === 'absent_consecutive') {
                $count = AttendanceRecord::query()
                    ->where('student_id', $student->id)
                    ->whereBetween('date', [$startDate, $endDate])
                    ->orderByDesc('date')
                    ->take($rule->threshold)
                    ->where('status', 'absent')
                    ->count();
                if ($count < $rule->threshold) { continue; }
            } elseif ($rule->type === 'late_count') {
                $count = AttendanceRecord::query()
                    ->where('student_id', $student->id)
                    ->whereBetween('date', [$startDate, $endDate])
                    ->where('status', 'late')
                    ->count();
                if ($count < $rule->threshold) { continue; }
            } else {
                continue;
            }

            $recipient = $student->user->email ?: $student->user->phone ?: null;
            if (!$recipient) { continue; }
            $channel = filter_var($recipient, FILTER_VALIDATE_EMAIL) ? 'email' : 'sms';
            if ($rule->channel === 'both') { $channel = $channel; }
            elseif ($rule->channel === 'email' && $channel !== 'email') { $channel = 'email'; $recipient = $student->user->email; }
            elseif ($rule->channel === 'sms' && $channel !== 'sms') { $channel = 'sms'; $recipient = $student->user->phone; }
            if (!$recipient) { continue; }

            $dispatcher->queue(
                templateName: $rule->template_name,
                channel: $channel,
                recipient: $recipient,
                payload: [
                    'student' => ['name' => $student->name ?? $student->user->name],
                    'attendance' => [
                        'rule' => $rule->type,
                        'threshold' => $rule->threshold,
                        'window_days' => $rule->window_days,
                        'date' => $endDate,
                    ],
                ]
            );
            $sent++;
        }
        return $sent;
    }
}



