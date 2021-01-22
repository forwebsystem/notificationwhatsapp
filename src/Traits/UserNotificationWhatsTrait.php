<?php

namespace ForWebSystem\NotificationWhatsApp\Traits;

trait UserNotificationWhatsApp {

    protected function getLicense() : string
    {
        return config('notificationwhats.license');
    }
}

