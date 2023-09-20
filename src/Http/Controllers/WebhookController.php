<?php

namespace ForWebSystem\NotificationWhatsApp\Http\Controllers;

use App\User;
use ForWebSystem\NotificationWhatsApp\Events\NotificationWhatsAppReceivedEvent;
use ForWebSystem\NotificationWhatsApp\Model\NotificationWhatsAppReceived;
use ForWebSystem\NotificationWhatsApp\Services\FivezapMessageService;
use ForWebSystem\NotificationWhatsApp\Services\NotificacaoZApiMensagemReceived;
use Illuminate\Routing\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class WebhookController extends Controller
{

    /**
     *
     * Atualiza o webhook de entrega de mensagem recebidas pelo whatsapp
     */
    public function received()
    {

        try {

            $usuario = User::whereNotificationwhatsappToken(request('token'))->first();

            $received = new NotificacaoZApiMensagemReceived();
            $mensagem = $received->save(request()->method(), request()->url(), 'received', request()->toArray());

            $received = new NotificationWhatsAppReceived($mensagem->toArray());

            event(new NotificationWhatsAppReceivedEvent($received, $usuario));

            return response()->json([
                'code' => 200,
                'message' => 'Mensagem Received success'
            ]);
            
        } catch (\Exception $th) {
            return response([
                'code' => 500,
                'message' => $th->getMessage()
            ], 500);
        }
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

    public function fivezapMessage(Request $request, FivezapMessageService $fivezap)
    {
        $method         = $request->method();
        $url            = $request->url();
        $message_type   = $request->message_type;
        $account_id     = $request->account['id'];
        $inbox_id       = $request->conversation['inbox_id'];
        
        if ($message_type == 'incoming') {
            // busca a instancia
            $user = User::where('notificationwhatsapp_license', $account_id)
            ->where('notificationwhatsapp_token', $inbox_id)
            ->first();

            $notification = $fivezap->save($method, $url, $message_type, $request->all());

            // preenche objeto de mensagem e dispara evento.
            $fivezap_message = new NotificationWhatsAppReceived($notification->toArray());
            event(new NotificationWhatsAppReceivedEvent($fivezap_message, $user));
        }
    }
}
