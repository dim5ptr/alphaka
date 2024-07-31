<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ActivationMail extends Mailable
{
    use Queueable, SerializesModels;

    public $activationKey;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($activationKey)
    {
        $this->activationKey = $activationKey;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('emails.verification')->with(['activationKey' => $this->activationKey]);
    }
}