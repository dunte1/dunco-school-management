<?php

namespace App\Services\Notifications;

use App\Models\NotificationJob;
use App\Models\NotificationLog;
use App\Models\NotificationTemplate;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

namespace App\Services\Notifications;

use App\Models\NotificationJob;
use App\Models\NotificationLog;
use App\Models\NotificationTemplate;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Services\Notifications\Sms\SmsSender;

class NotificationDispatcher
{
    public function queue(string $templateName, string $channel, string $recipient, array $payload = [], ?\DateTimeInterface $scheduledAt = null, ?string $dedupKey = null): NotificationJob
    {
        $template = NotificationTemplate::where('name', $templateName)
            ->where('channel', $channel)
            ->where('is_active', true)
            ->first();

        if (!$template) {
            throw new \InvalidArgumentException("Active template not found: {$templateName} ({$channel})");
        }

        $dedupKey = $dedupKey ?? $this->buildDedupKey($channel, $recipient, $template->id, $payload);

        if (NotificationJob::where('dedup_key', $dedupKey)->exists()) {
            return NotificationJob::where('dedup_key', $dedupKey)->first();
        }
        $recentWindow = now()->subHours(6);
        if (NotificationLog::where('dedup_key', $dedupKey)->where('sent_at', '>=', $recentWindow)->exists()) {
            return NotificationJob::firstOrCreate(
                ['dedup_key' => $dedupKey],
                [
                    'template_id' => $template->id,
                    'channel' => $channel,
                    'recipient' => $recipient,
                    'payload' => $payload,
                    'scheduled_at' => $scheduledAt,
                    'status' => 'sent',
                ]
            );
        }

        $job = NotificationJob::create([
            'template_id' => $template->id,
            'channel' => $channel,
            'recipient' => $recipient,
            'payload' => $payload,
            'scheduled_at' => $scheduledAt,
            'status' => 'queued',
            'dedup_key' => $dedupKey,
        ]);
        // Optionally send web push immediately when channel is 'webpush'
        if ($channel === 'webpush') {
            try {
                // Placeholder: integrate WebPush library or FCM here
            } catch (\Throwable $e) {
                // swallow
            }
        }
        return $job;
    }

    public function dispatchDueJobs(callable $channelSender): int
    {
        $now = now();
        $jobs = NotificationJob::query()
            ->whereIn('status', ['queued', 'retrying'])
            ->where(function ($q) use ($now) {
                $q->whereNull('scheduled_at')->orWhere('scheduled_at', '<=', $now);
            })
            ->where(function ($q) use ($now) {
                $q->whereNull('next_run_at')->orWhere('next_run_at', '<=', $now);
            })
            ->limit(100)
            ->get();

        $sent = 0;
        foreach ($jobs as $job) {
            DB::beginTransaction();
            try {
                $template = $job->template;
                [$subject, $body] = $this->renderTemplate($template, $job->payload ?? []);
                $response = $channelSender($job->channel, $job->recipient, $subject, $body, $job->payload ?? []);

                $job->status = 'sent';
                $job->attempts = ($job->attempts ?? 0) + 1;
                $job->last_error = null;
                $job->next_run_at = null;
                $job->save();

                NotificationLog::create([
                    'template_id' => $job->template_id,
                    'channel' => $job->channel,
                    'recipient' => $job->recipient,
                    'payload' => $job->payload,
                    'response' => is_string($response) ? $response : json_encode($response),
                    'status' => 'sent',
                    'sent_at' => now(),
                    'dedup_key' => $job->dedup_key,
                ]);
                DB::commit();
                $sent++;
            } catch (\Throwable $e) {
                DB::rollBack();
                Log::error('Notification job failed', [
                    'job_id' => $job->id,
                    'error' => $e->getMessage(),
                ]);
                $job->attempts = ($job->attempts ?? 0) + 1;
                $job->last_error = $e->getMessage();
                // Exponential backoff: base 60s, capped at 30 min
                $delaySeconds = min(1800, 60 * (2 ** min(5, $job->attempts - 1)));
                $job->next_run_at = now()->addSeconds($delaySeconds);
                $job->status = $job->attempts >= 6 ? 'failed' : 'retrying';
                $job->save();
            }
        }

        return $sent;
    }

    private function renderTemplate(NotificationTemplate $template, array $payload): array
    {
        $variables = is_array($template->variables ?? null) ? $template->variables : [];
        $replacements = $this->resolveVariables($variables, $payload);

        $subject = $template->subject ? $this->applyReplacements($template->subject, $replacements) : null;
        $body = $this->applyReplacements($template->body, $replacements);
        return [$subject, $body];
    }

    private function applyReplacements(string $text, array $vars): string
    {
        foreach ($vars as $key => $value) {
            $text = str_replace('{{'.$key.'}}', (string) $value, $text);
        }
        return $text;
    }

    private function resolveVariables(array $variables, array $payload): array
    {
        $resolved = [];
        foreach ($variables as $var) {
            $resolved[$var] = data_get($payload, $var, '');
        }
        return $resolved;
    }

    public function buildDedupKey(string $channel, string $recipient, int $templateId, array $payload = []): string
    {
        $context = json_encode([
            'channel' => $channel,
            'recipient' => $recipient,
            'template_id' => $templateId,
            'payload' => $payload,
        ]);
        return substr(hash('sha256', $context), 0, 64);
    }
}


