<?php

namespace ForWebSystem\NotificationWhatsApp\Http\Controllers;

use App\User;
use ForWebSystem\NotificationWhatsApp\Services\InstanciaZApi;
use ForWebSystem\NotificationWhatsApp\Services\WebhooksZApi;
use Illuminate\Routing\Controller;

class NotificationWhatsAppController extends Controller
{

    public function configuracao()
    {
        $user   = auth()->user();// app(User::class)->fill(['name' => 'API','email' => 'joa@tre.com','license' => '123']);
        $config = new WebhooksZApi($user, config('notificationwhatsapp.instancia_id'), $user->license);

        $config->updateWebhookDelivery(route('notificacaowhatsapp.webhook.delivery'));
        $config->updateWebhookDisconnected(route('notificacaowhatsapp.webhook.disconnected'));
        $config->updateWebhookReceived(route('notificacaowhatsapp.webhook.received'));
        $config->updateWebhookMessageStatus(route('notificacaowhatsapp.webhook.message-status'));

        // $webhookDelivery        = $config->updateWebhookDelivery('https://webhook.site/ad295589-4175-411d-94c2-dc0b2618ceda');
        // $webhookDisconnected    = $config->updateWebhookDisconnected('https://webhook.site/7df9b8c0-674f-45c5-8a80-e36ff72dc69a');
        // $webhookReceived        = $config->updateWebhookReceived('https://webhook.site/7fcf4ef8-d8f4-4303-9ff8-6e3528f5bb49');
        // $webhookMessageStatus   = $config->updateWebhookMessageStatus('https://webhook.site/49ab69c5-8f56-47f5-bcd2-e6964ad4fb73');

        return redirect()->back();
    }

    public function instancia()
    {
        $user   = auth()->user();// app(User::class)->fill(['name' => 'API','email' => 'joa@tre.com','license' => '123']);
        $config = new InstanciaZApi($user, config('notificationwhatsapp.instancia_id'), $user->license);

        $imagens = $config->qrCode​Imagem();
        if (!empty($imagens['value'])) {
            die("<img src='{$imagens['value']}' />");
        }

        return redirect()->back();
    }

    public function disconnect()
    {
        $user   = auth()->user();// app(User::class)->fill(['name' => 'API','email' => 'joa@tre.com','license' => '123']);
        $config = new InstanciaZApi($user, config('notificationwhatsapp.instancia_id'), $user->license);

        $imagens = $config->disconnect();
        if (!empty($imagens['value'])) {
            die("<img src='{$imagens['value']}' />");
        }
        
        return redirect()->back();
    }
}
