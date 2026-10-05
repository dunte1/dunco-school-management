<?php

namespace Modules\ChatBot\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ErrorNotification extends Mailable
{
    use Queueable, SerializesModels;

    public $errorData;

    /**
     * Create a new message instance.
     *
     * @param array $errorData
     * @return void
     */
    public function __construct($errorData)
    {
        $this->errorData = $errorData;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject('Critical ChatBot Error - ' . config('app.name'))
                    ->view('chatbot::emails.error-notification')
                    ->with('errorData', $this->errorData);
    }
}