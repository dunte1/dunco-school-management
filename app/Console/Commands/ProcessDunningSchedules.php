<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Modules\Finance\Models\Invoice;
use App\Models\NotificationTemplate;
use App\Services\Notifications\NotificationDispatcher;

class ProcessDunningSchedules extends Command
{
    protected $signature = 'finance:dunning:process';
    protected $description = 'Evaluate dunning rules and enqueue notifications';

    public function handle(NotificationDispatcher $dispatcher): int
    {
        $rules = \DB::table('finance_dunning_rules')->where('is_active', true)->get();
        $today = now()->toDateString();
        $created = 0;
        foreach ($rules as $rule) {
            $criteria = json_decode($rule->criteria ?? '{}', true);
            $cadence = json_decode($rule->cadence ?? '[]', true);
            $minDays = (int)($criteria['min_days_past_due'] ?? 0);

            $invoices = Invoice::with('payments')
                ->whereIn('status', ['unpaid','partial','overdue'])
                ->get();
            foreach ($invoices as $inv) {
                $paid = (float) $inv->payments->sum('amount');
                $outstanding = max(0.0, (float) $inv->total_amount - $paid);
                if ($outstanding <= 0) continue;
                $daysPast = now()->diffInDays(\Illuminate\Support\Carbon::parse($inv->due_date), false) * -1;
                if ($daysPast < $minDays) continue;
                foreach ($cadence as $offset) {
                    $dueOn = \Illuminate\Support\Carbon::parse($inv->due_date)->addDays($offset);
                    if ($dueOn->isSameDay(now())) {
                        \DB::table('finance_dunning_events')->updateOrInsert(
                            ['rule_id' => $rule->id, 'invoice_id' => $inv->id, 'due_on' => $dueOn->toDateString()],
                            ['status' => 'queued', 'updated_at' => now(), 'created_at' => now()]
                        );
                        $student = $inv->student?->user;
                        if ($student) {
                            $payload = [
                                'student' => ['name' => $student->name],
                                'invoice' => ['id' => $inv->id, 'amount' => $outstanding, 'due_date' => $inv->due_date],
                            ];
                            try {
                                $dispatcher->queue(
                                    templateName: NotificationTemplate::find($rule->template_id)?->name ?? 'fee_overdue',
                                    channel: $rule->channel,
                                    recipient: $student->email ?: $student->phone,
                                    payload: $payload
                                );
                                $created++;
                            } catch (\Throwable $e) {
                                // continue
                            }
                        }
                    }
                }
            }
        }
        $this->info("Queued {$created} dunning notifications.");
        return self::SUCCESS;
    }
}


