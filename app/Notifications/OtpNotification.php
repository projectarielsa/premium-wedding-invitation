<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class OtpNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(private string $otp, private int $expiresInMinutes = 10) {}

    public function via(object $notifiable): array { return ['mail']; }

    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('Your Verification Code - ' . config('app.name'))
            ->greeting('Hello ' . $notifiable->name . '!')
            ->line('Your verification code is:')
            ->line('**' . $this->otp . '**')
            ->line('This code will expire in ' . $this->expiresInMinutes . ' minutes.')
            ->line('If you did not request this code, please ignore this email.');
    }
}
