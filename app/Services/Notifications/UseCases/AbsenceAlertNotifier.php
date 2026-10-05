<?php

namespace App\Services\Notifications\UseCases;

use App\Services\Notifications\NotificationDispatcher;
use Modules\Academic\Models\AttendanceRecord;

class AbsenceAlertNotifier
{
    public function handle(NotificationDispatcher $dispatcher, string $date): int
    {
        $records = AttendanceRecord::query()
            ->whereDate('date', $date)
            ->whereIn('status', ['absent', 'late'])
            ->with(['student.user'])
            ->limit(1000)
            ->get();

        $count = 0;
        foreach ($records as $record) {
            $user = $record->student?->user;
            if (!$user) {
                continue;
            }
            $recipient = $user->email ?: $user->phone ?: null;
            if (!$recipient) {
                continue;
            }
            $channel = filter_var($recipient, FILTER_VALIDATE_EMAIL) ? 'email' : 'sms';
            $dispatcher->queue(
                templateName: 'attendance_status_alert',
                channel: $channel,
                recipient: $recipient,
                payload: []
            );
            $count++;
        }
        return $count;
    }
}


