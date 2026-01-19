<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class InvoiceSent extends Notification
{
    use Queueable;
    public $invoiceData;

    public function __construct($invoiceData)
    {
        $this->invoiceData = $invoiceData;
    }

    public function via($notifiable)
    {
        return ['mail'];
    }

    public function toMail($notifiable)
    {
        return (new MailMessage)
                    ->subject('Your Invoice is Ready')
                    ->greeting('Hello ' . $notifiable->name . ',')
                    ->line('Here is your invoice for the recent reservation.')
                    ->line('Total Amount: $' . $this->invoiceData['amount'])
                    ->action('View Invoice', $this->invoiceData['url'])
                    ->line('Thanks for booking with us!');
    }
}
