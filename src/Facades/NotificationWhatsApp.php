<?php

namespace ForWebSystem\NotificationWhatsApp\Facades;

use Illuminate\Support\Facades\Facade;

class NotificationWhatsApp extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor(): string
    {
        return 'notificationwhatsapp';
    }
}
