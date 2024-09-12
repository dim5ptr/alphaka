<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Messages\BroadcastMessage;

class OrganizationCreated extends Notification
{
    use Queueable;

    protected $organizationName;
    protected $verificationToken;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($organizationName, $verificationToken)
    {
        $this->organizationName = $organizationName;
        $this->verificationToken = $verificationToken;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['database', 'broadcast']; // Menyimpan notifikasi di database dan broadcast untuk real-time
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return [
            'organization_name' => $this->organizationName,
            'verification_token' => $this->verificationToken,
            'message' => 'Organization created successfully with verification token.',
        ];
    }

    /**
     * Broadcast notification untuk real-time.
     */
    public function toBroadcast($notifiable)
    {
        return new BroadcastMessage([
            'organization_name' => $this->organizationName,
            'verification_token' => $this->verificationToken,
            'message' => 'Organization created successfully with verification token.',
        ]);
    }
}
