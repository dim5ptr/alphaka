<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class RegistrationSuccessful extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     */
    public function __construct()
    {
        //
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        $loginUrl = url('/login');

        return (new MailMessage)
                    ->subject('Registrasi Berhasil')
                    ->greeting('Selamat!')
                    ->line('Anda telah berhasil mendaftarkan akun.')
                    ->line('Kami sangat senang Anda bergabung dengan kami. Sekarang Anda dapat masuk dan mulai menggunakan aplikasi.')
                    ->action('Login sekarang', $loginUrl)
                    ->line('Terima kasih telah mendaftar!');
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
            //
        ];
    }
}
