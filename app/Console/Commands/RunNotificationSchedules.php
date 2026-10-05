<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\NotificationSchedule;
use App\Services\Notifications\NotificationDispatcher;
use Cron\CronExpression;

class RunNotificationSchedules extends Command
{
    protected $signature = 'notifications:run-schedules';
    protected $description = 'Evaluate notification schedules and dispatch messages';

    public function handle(NotificationDispatcher $dispatcher): int
    {
        $now = now();
        $schedules = NotificationSchedule::with('template')->where('is_active', true)->get();
        $count = 0;
        foreach ($schedules as $schedule) {
            $expr = new CronExpression($schedule->cron);
            if ($expr->isDue($now)) {
                $audience = $schedule->audience ?? [];
                $recipients = $this->resolveRecipients($audience);
                foreach ($recipients as $recipient) {
                    $dispatcher->queue($schedule->template->name, $schedule->channel, $recipient, []);
                    $count++;
                }
                $schedule->last_run_at = $now;
                $schedule->save();
            }
        }
        $this->info("Dispatched {$count} scheduled notifications");
        return self::SUCCESS;
    }
}



