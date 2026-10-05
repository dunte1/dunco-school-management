<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\Notifications\UseCases\AttendanceAlertRulesEvaluator;
use App\Services\Notifications\NotificationDispatcher;

class EvaluateAttendanceAlertRules extends Command
{
    protected $signature = 'attendance:eval-alert-rules {school_id?}';
    protected $description = 'Evaluate attendance alert rules and queue notifications';

    public function handle(AttendanceAlertRulesEvaluator $evaluator, NotificationDispatcher $dispatcher): int
    {
        $schoolId = $this->argument('school_id') ? (int)$this->argument('school_id') : null;
        $count = $evaluator->handle($dispatcher, $schoolId);
        $this->info("Queued notifications for {$count} rule matches.");
        return self::SUCCESS;
    }
}





