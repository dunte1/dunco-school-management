<?php

namespace App\Services\Notifications\UseCases;

use App\Services\Notifications\NotificationDispatcher;
use Modules\Finance\Models\Invoice;

class FeeOverdueNotifier
{
    public function handle(NotificationDispatcher $dispatcher, ?int $daysOverdue = 1): int
    {
        $today = now()->toDateString();
        $query = Invoice::query()
            ->where('status', 'unpaid')
            ->whereDate('due_date', '<', $today);

        if ($daysOverdue && $daysOverdue > 0) {
            $threshold = now()->subDays($daysOverdue)->toDateString();
            $query->whereDate('due_date', '<=', $threshold);
        }

        $invoices = $query->with('student.user')->limit(500)->get();
        $count = 0;
        foreach ($invoices as $invoice) {
            $user = $invoice->student?->user;
            if (!$user) {
                continue;
            }
            $recipient = $user->email ?: $user->phone ?: null;
            if (!$recipient) {
                continue;
            }
            $channel = filter_var($recipient, FILTER_VALIDATE_EMAIL) ? 'email' : 'sms';
            $dispatcher->queue(
                templateName: 'fee_overdue_reminder',
                channel: $channel,
                recipient: $recipient,
                payload: []
            );
            $count++;
        }
        return $count;
    }
}


