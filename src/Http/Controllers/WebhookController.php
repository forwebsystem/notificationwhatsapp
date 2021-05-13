<?php

namespace ForWebSystem\NotificationWhatsApp\Http\Controllers;

use App\User;
use ForWebSystem\NotificationWhatsApp\Events\NotificationWhatsAppReceivedEvent;
use ForWebSystem\NotificationWhatsApp\Model\NotificationWhatsAppReceived;
use ForWebSystem\NotificationWhatsApp\Services\NotificacaoZApiMensagemReceived;
use Illuminate\Routing\Controller;

class WebhookController extends Controller
{

    /**
     *
     * Atualiza o webhook de entrega de mensagem recebidas pelo whatsapp
     */
    public function received()
    {

        $usuario = User::whereNotificationwhatsappToken(request('token'))->first();

        $received = new NotificacaoZApiMensagemReceived();
        $mensagem = $received->save(request()->method(), request()->url(), 'received', request()->toArray());

        $received = new NotificationWhatsAppReceived($mensagem->toArray());

        event(new NotificationWhatsAppReceivedEvent($received, $usuario));
    }

    /**
     * Atualiza o webhook de entrega da mensagem
     */
    public function delivery()
    {

    }

    /**
     * Atualiza o webhook para avisar quando o whatsapp desconectar
     */
    public function disconnected()
    {

    }


    /**
     * Atualiza o webhook de mudança nos status das mensagens
     */
    public function messageStatus()
    {

    }

}
