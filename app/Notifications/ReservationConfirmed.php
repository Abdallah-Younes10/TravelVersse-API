<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ReservationConfirmed extends Notification
{
   use Queueable;

    public function via($notifiable)
    {
        return ['mail'];
    }

    public function toMail($notifiable)
    {
        return (new MailMessage)
                    ->subject('Reservation Confirmed')
                    ->greeting('Hello!')
                    ->line('Your reservation has been confirmed successfully.')
                    ->action('View Reservations', url('/user/reservations'))
                    ->line('Thank you for using our platform!');
    }
}
