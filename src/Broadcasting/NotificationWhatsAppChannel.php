<?php

namespace ForWebSystem\NotificationWhatsApp\Broadcasting;

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
        return $notification->toWhatsApp($notifiable);
    }
}
