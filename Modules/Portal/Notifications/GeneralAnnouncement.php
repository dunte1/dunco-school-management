<?php

namespace Modules\Portal\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class GeneralAnnouncement extends Notification
{
    use Queueable;

    public function __construct(
        public string $title = '',
        public string $message = ''
    ) {
    }

    public function via(object $notifiable): array
    {
        return ['database'];
    }

    public function toArray(object $notifiable): array
    {
        return [
            'title' => $this->title,
            'message' => $this->message,
        ];
    }
}
