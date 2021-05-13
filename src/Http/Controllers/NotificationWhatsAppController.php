<?php

namespace ForWebSystem\NotificationWhatsApp\Http\Controllers;

use App\User;
use ForWebSystem\NotificationWhatsApp\Exceptions\ClientException;
use ForWebSystem\NotificationWhatsApp\Services\InstanciaZApi;
use ForWebSystem\NotificationWhatsApp\Services\WebhooksZApi;
use Illuminate\Routing\Controller;

class NotificationWhatsAppController extends Controller
{

    public function configuracao($idUser)
    {
        try {

            $user   = app(config('notificationwhatsapp.user_model'))->find($idUser);
            $config = WebhooksZApi::getInstancia($user);

            $config->updateWebhookDelivery(route('notificacaowhatsapp.webhook.delivery',[$user->notificationwhatsapp_token]));
            $config->updateWebhookDisconnected(route('notificacaowhatsapp.webhook.disconnected',[$user->notificationwhatsapp_token]));
            $config->updateWebhookReceived(route('notificacaowhatsapp.webhook.received',[$user->notificationwhatsapp_token]));
            $config->updateWebhookMessageStatus(route('notificacaowhatsapp.webhook.message-status',[$user->notificationwhatsapp_token]));

            // $webhookDelivery        = $config->updateWebhookDelivery('https://webhook.site/ad295589-4175-411d-94c2-dc0b2618ceda');
            // $webhookDisconnected    = $config->updateWebhookDisconnected('https://webhook.site/7df9b8c0-674f-45c5-8a80-e36ff72dc69a');
            // $webhookReceived        = $config->updateWebhookReceived('https://webhook.site/7fcf4ef8-d8f4-4303-9ff8-6e3528f5bb49');
            // $webhookMessageStatus   = $config->updateWebhookMessageStatus('https://webhook.site/49ab69c5-8f56-47f5-bcd2-e6964ad4fb73');

            return redirect()->back()->with('success', 'Webhook atualizados com sucesso');

        } catch( ClientException $e ){
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    public function instancia($idUser)
    {
        try {
            $user   = app(config('notificationwhatsapp.user_model'))->find($idUser);
            $config = InstanciaZApi::getInstancia($user);

            $imagens = $config->qrCode​Imagem();
            if (!empty($imagens['value'])) {
                die("<img src='{$imagens['value']}' />");
            }

            return redirect()->back()->with('success', 'Instância conectada com sucesso');

        } catch( ClientException $e ){
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    public function disconnect($idUser)
    {
        try {
            $user   = app(config('notificationwhatsapp.user_model'))->find($idUser);
            $config = InstanciaZApi::getInstancia($user);

            $config->disconnect();

            return redirect()->back()->with('success', 'Instância disconectada com sucesso');

        } catch( ClientException $e ){
            return redirect()->back()->with('error', $e->getMessage());
        }
    }
}
