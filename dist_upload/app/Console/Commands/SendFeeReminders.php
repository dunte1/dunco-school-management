<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Modules\Academic\Models\StudentFee;
use App\Notifications\FeeDueReminder;
use App\Notifications\FeeOverdue;
use Carbon\Carbon;

class SendFeeReminders extends Command
{
    protected $signature = 'fees:send-reminders';
    protected $description = 'Send due and overdue fee reminders to parents';

    public function handle()
    {
        $today = Carbon::today();
        $dueSoon = StudentFee::where('status', 'unpaid')
            ->whereDate('due_date', $today->copy()->addDays(3))
            ->get();
        foreach ($dueSoon as $fee) {
            foreach ($fee->student->parents as $parent) {
                $parent->notify(new FeeDueReminder($fee));
            }
        }
        $overdue = StudentFee::where('status', 'unpaid')
            ->whereDate('due_date', '<', $today)
            ->get();
        foreach ($overdue as $fee) {
            foreach ($fee->student->parents as $parent) {
                $parent->notify(new FeeOverdue($fee));
            }
        }
        $this->info('Fee reminders sent.');
    }
} 