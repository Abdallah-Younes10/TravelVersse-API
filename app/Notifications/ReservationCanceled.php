<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ReservationCanceled extends Notification
{
   use Queueable;

    public function via($notifiable)
    {
        return ['mail'];
    }

    public function toMail($notifiable)
    {
        return (new MailMessage)
                    ->subject('Reservation Cancelled')
                    ->greeting('Hello!')
                    ->line('Your reservation has been cancelled successfully.')
                    ->action('View Reservations', url('/user/reservations'))
                    ->line('We hope to serve you again soon.');
    }
}
