<?php

namespace ForWebSystem\NotificationWhatsApp\Traits;

use ForWebSystem\NotificationWhatsApp\Services\Interfaces\NotificacaoMensagensInterface;
use ForWebSystem\NotificationWhatsApp\Services\NotificacaoZApiService;

trait NotificationWhatsAppChannelTrait
{

    protected abstract function getUserNotification();

    protected abstract function toWhatsApp($notifiable);

    protected function getNotificacaoMensagem(): NotificacaoMensagensInterface
    {
        return NotificacaoZApiService::getInstancia($this->getUserNotification());
    }
}
