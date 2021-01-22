<?php

namespace ForWebSystem\NotificationWhatsApp\Broadcasting;

use ForWebSystem\NotificationWhatsApp\Services\NotificacaoZApiService;
use Illuminate\Notifications\Notification;

class NotificationWhatsAppChannel
{


     /**
     * Send the given notification.
     *
     * @param  mixed  $notifiable
     * @param  \Illuminate\Notifications\Notification  $notification
     * @return void
     */
    public function send($notifiable, Notification $notification)
    {
        $notificacao = new NotificacaoZApiService($notifiable, config('notificationwhatsapp.instancia_id'), $notifiable->license);
        return $notification->toWhatsApp($notifiable, $notificacao);
    }
}
