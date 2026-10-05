<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\Notifications\UseCases\FeeOverdueNotifier;
use App\Services\Notifications\NotificationDispatcher;

class SendFeeOverdueReminders extends Command
{
    protected $signature = 'notifications:fee-overdue {--days=1}';
    protected $description = 'Queue fee overdue reminders for unpaid invoices';

    public function handle(FeeOverdueNotifier $notifier, NotificationDispatcher $dispatcher): int
    {
        $days = (int) $this->option('days');
        $count = $notifier->handle($dispatcher, $days);
        $this->info("Queued reminders for {$count} invoice(s).");
        return self::SUCCESS;
    }
}


