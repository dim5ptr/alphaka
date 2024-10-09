<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class VerifAddMember extends Mailable
{
    use Queueable, SerializesModels;

    public $email; // or other properties you need

    public function __construct($email)
    {
        $this->email = $email;
    }

    public function build()
    {
        return $this->view('emails.verif-addmember') // Ensure this view exists
                    ->with(['email' => $this->email]);
    }
}
