<?php

namespace App\Mail\Marketing;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ContactSubmitted extends Mailable
{
    use Queueable, SerializesModels;

    public array $payload;

    public function __construct(array $payload)
    {
        $this->payload = $payload;
    }

    public function build()
    {
        return $this->subject('New contact: '.$this->payload['subject'])
            ->markdown('emails.marketing.contact_submitted', [
                'data' => $this->payload,
            ]);
    }
}


