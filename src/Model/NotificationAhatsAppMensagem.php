<?php

namespace ForWebSystem\NotificationWhatsApp\Model;

use ForWebSystem\NotificationWhatsApp\Traits\UuidsTrait;
use Illuminate\Database\Eloquent\Model;

class NotificationAhatsAppMensagem extends Model
{

    use UuidsTrait;

    protected $table = 'notificationwhatsapp_mensagens';

    protected $fillable = [
        'user_type',
        'user_id',
        'service',
        'url',
        'type_mensagem',
        'phone_destination',
        'context',
        'result',
        'status',
    ];
}
