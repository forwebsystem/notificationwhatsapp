<?php

namespace ForWebSystem\NotificationWhatsApp\Model;

use ForWebSystem\NotificationWhatsApp\Traits\UuidsTrait;
use Illuminate\Database\Eloquent\Model;

class NotificationWhatsAppMensagem extends Model
{

    use UuidsTrait;

    protected $table = 'notificationwhatsapp_mensagens';

    protected $fillable = [
        'id',
        'user_type',
        'user_id',
        'service',
        'url',
        'type_mensagem',
        'phone_destination',
        'phone_participant',
        'sender_name',
        'chat_name',
        'momment',
        'message_id',
        'from_me',
        'context',
        'photo',
        'type',
        'result',
        'status',
    ];


}
