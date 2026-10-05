<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\Notifications\UseCases\AbsenceAlertNotifier;
use App\Services\Notifications\NotificationDispatcher;

class SendAbsenceAlerts extends Command
{
    protected $signature = 'notifications:absence-alerts {date?}';
    protected $description = 'Queue absence/late alerts for the given date (defaults to today)';

    public function handle(AbsenceAlertNotifier $notifier, NotificationDispatcher $dispatcher): int
    {
        $date = $this->argument('date') ?: now()->toDateString();
        $count = $notifier->handle($dispatcher, $date);
        $this->info("Queued alerts for {$count} attendance record(s) on {$date}.");
        return self::SUCCESS;
    }
}


