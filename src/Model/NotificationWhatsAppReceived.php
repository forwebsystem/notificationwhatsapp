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


    public function getPhoneParticipant()
    {
        $numero             = $this->phone_participant;
        $dd                 = substr($numero, 2,2);
        $telefone           = substr($numero, 4);
        return strlen($telefone) == 8 ? "{$dd}9{$telefone}" : "{$dd}{$telefone}";
    }

    public function getContext()
    {
        return json_decode($this->context);
    }

    public function html($height="250px")
    {
        $context = $this->getContext();
        $type = $this->getTypeContext();
        switch($type){

            case 'text':
                return $context->message;
            case 'image':
                $descricao = isset($context->caption) ? $context->caption : '';
                return "<img src='{$context->imageUrl}' height='{$height}' width='auto' /><br />". $descricao ?? '';
            default:
                return "Mensagem do tipo {$type} ainda não suportada";

        }
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
