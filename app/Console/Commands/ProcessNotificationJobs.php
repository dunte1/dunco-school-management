<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\Notifications\NotificationDispatcher;

class ProcessNotificationJobs extends Command
{
    protected $signature = 'notifications:process {--limit=100}';
    protected $description = 'Process queued notification jobs and send them via configured channels';

    public function handle(NotificationDispatcher $dispatcher): int
    {
        $limit = (int) $this->option('limit');
        $sent = $dispatcher->dispatchDueJobs(function (string $channel, string $recipient, ?string $subject, string $body, array $payload) {
            switch ($channel) {
                case 'email':
                    \Mail::raw($body, function ($message) use ($recipient, $subject) {
                        $message->to($recipient)->subject($subject ?? '');
                    });
                    return 'email_queued';
                case 'sms':
                    return app(\App\Services\Notifications\Sms\SmsSender::class)->send($recipient, $body);
                case 'whatsapp':
                    return app(\App\Services\Notifications\Whatsapp\WhatsappSender::class)->send($recipient, $body);
                default:
                    throw new \InvalidArgumentException('Unsupported channel: '.$channel);
            }
        });

        $this->info("Sent {$sent} notification(s).");
        return self::SUCCESS;
    }
}


