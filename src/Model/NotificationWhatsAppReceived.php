<?php

namespace ForWebSystem\NotificationWhatsApp\Model;

use Illuminate\Database\Eloquent\Model;

class NotificationWhatsAppReceived extends Model
{

    protected $fillable = [
        'phone',
        'phone_participant',
        'sender_name',
        'chat_name',
        'momment',
        'message_id',
        'from_me',
        'context',
        'status',
        'photo',
        'type',
        'result',
        'broadcast'
    ];

    public function getPhoneAttributes()
    {
        return $this->phone;
    }


    public function getTypeContext()
    {
        $types = ['text', 'image', 'audio', 'video', 'contact', 'document', 'location', 'sticker'];
        $data = json_decode($this->result);
        foreach ($types as $type) {
            if (!empty($data->{$type})) {
                return $type;
            }
        }
        return '';
    }

    public function isBroadcast()
    {
        return boolval($this->broadcast);
    }
}
