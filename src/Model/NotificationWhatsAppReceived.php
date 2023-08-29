<?php

namespace ForWebSystem\NotificationWhatsApp\Model;

use Illuminate\Database\Eloquent\Model;

class NotificationWhatsAppReceived extends Model
{

    protected $fillable = [
        'service',
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
        // parse do conteudo da coluna context.
        return json_decode($this->context);
    }

    public function html($height="250px")
    {
        // conluna context do banco
        $context = $this->getContext();

        // tipo da mensagem
        $type = $this->getTypeContext();
        switch($type){
            case 'text':
                return $context->message ?? $context;
            case 'image':
                $descricao = $context->caption ?? '';
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
            // verifica existencia de indices e tipo para mensagens do fivezap 2.0
            if (isset($data->content_type) && $data->content_type == $type) {
                return $type;
            }
            
            // fivezap 1.0.
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
