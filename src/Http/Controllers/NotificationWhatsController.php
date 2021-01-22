<?php

namespace ForWebSystem\NotificationWhatsApp\Http\Controllers;

use ForWebSystem\NotificationWhatsApp\Events\NotificationWhatsAppRecebidoEvent;
use ForWebSystem\NotificationWhatsApp\Model\WoowaMensagem;
use ForWebSystem\NotificationWhatsApp\RegraDeNegocio\NotificationWhatsAppEnviarTexto;
use Illuminate\Routing\Controller;

class NotificationWhatsAppController extends Controller
{

    public function configuracao()
    {
        $enviarTexto = new NotificationWhatsAppEnviarTexto();
        // $teste = $enviarTexto->setWebHook();

        $teste = $enviarTexto->enviarWhatsApp('Estou fazendo teste, :( não tenho outro numero kkk', '+5562992619927');
        dd( $teste );
        return 'Teste JP';
    }

    public function listen()
    {
        event(new NotificationWhatsAppRecebidoEvent(new WoowaMensagem()));
    }
}
