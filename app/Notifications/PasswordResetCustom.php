<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class PasswordResetCustom extends Notification
{
     use Queueable;
    public $token;

    public function __construct($token)
    {
        $this->token = $token;
    }

    public function via($notifiable)
    {
        return ['mail'];
    }

    public function toMail($notifiable)
    {
        $url = url('/reset-password/' . $this->token . '?email=' . urlencode($notifiable->email));

        return (new MailMessage)
                    ->subject('Reset Your Password')
                    ->greeting('Hello!')
                    ->line('You requested to reset your password.')
                    ->action('Reset Password', $url)
                    ->line('If you didn\'t request this, please ignore this email.');
    }
}
