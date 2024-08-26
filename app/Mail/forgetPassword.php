<?php

namespace codenrx\forgetpassword\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class forgetPassword extends Mailable
{
    use Queueable, SerializesModels;

    public $token;

    /**
     * Create a new message instance.
     *
     * @param string $token
     * @return void
     */
    public function __construct($token)
    {
        $this->token = $token;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('formforgetpassword')
            ->from(config('forgetpassword.address'), config('forgetpassword.name'))
            ->with(['token' => $this->token, 'url' => config('forgetpassword.url')]);
    }
}
