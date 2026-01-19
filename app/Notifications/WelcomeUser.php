<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class WelcomeUser extends Notification
{
    use Queueable;
    public function __construct() {}

    public function via($notifiable)
    {
        return ['mail'];
    }

    public function toMail($notifiable)
    {
        return (new MailMessage)
                    ->subject('Welcome to TravelVerse!')
                    ->greeting('Welcome, ' . $notifiable->name . '!')
                    ->line('We are excited to have you on board. Start your journey with us today!')
                    ->action('Explore Now', url('/'))
                    ->line('Enjoy your experience with TravelVerse.');
    }
}
