<?php

namespace App\Notifications;

use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;

class ResetPasswordNotification extends Notification
{
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
        $url = env('FRONTEND_URL') . '/reset-password?token=' . $this->token . '&email=' . $notifiable->email;

        return (new MailMessage)
            ->subject('استعادة كلمة المرور')
            ->greeting('مرحبًا 👋')
            ->line('لقد تلقينا طلبًا لإعادة تعيين كلمة المرور الخاصة بك.')
            ->action('إعادة تعيين كلمة المرور', $url)
            ->line('إذا لم تطلب هذا التغيير، فلا داعي لاتخاذ أي إجراء.')
            ->salutation('مع تحيات فريق الدعم 💼');
    }
}
