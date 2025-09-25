<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class SecurityAlertMail extends Mailable
{
    use Queueable, SerializesModels;

    public string $subjectLine;
    public array $payload;

    public function __construct(string $subjectLine, array $payload = [])
    {
        $this->subjectLine = $subjectLine;
        $this->payload = $payload;
    }

    public function build(): self
    {
        return $this->subject($this->subjectLine)
            ->view('emails.security_alert')
            ->with(['payload' => $this->payload]);
    }
}


