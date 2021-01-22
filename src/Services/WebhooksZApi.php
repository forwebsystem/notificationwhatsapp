<?php

namespace ForWebSystem\NotificationWhatsApp\Services;

use ForWebSystem\NotificationWhatsApp\Services\Interfaces\WebhooksInterface;
use ForWebSystem\NotificationWhatsApp\Traits\RequestZApiTrait;

/**
 *
 * Webhooks de sua instância
 */
class WebhooksZApi implements WebhooksInterface
{
    use RequestZApiTrait;


    /**
     * Atualiza todos os webhook para o mesmo endereço de retorno
     *
     */
    public function updateWebhook(string $url)
    {
        $this->updateWebhookDelivery($url);
        $this->updateWebhookDisconnected($url);
        $this->updateWebhookReceived($url);
        $this->updateWebhookMessageStatus($url);
    }

    /**
     *Atualiza o webhook de entrega da mensagem
     */
    public function updateWebhookDelivery(string $url)
    {
        return $this->request('PUT', 'update-webhook-delivery', [
            'value' => $url
        ]);
    }

    /**
     *Atualiza o webhook para avisar quando o whatsapp desconectar
     */
    public function updateWebhookDisconnected(string $url)
    {
        return $this->request('PUT', 'update-webhook-disconnected', [
            'value' => $url
        ]);
    }

    /**
     *Atualiza o webhook de entrega de mensagem recebidas pelo whatsapp
     */
    public function updateWebhookReceived(string $url)
    {
        return $this->request('PUT', 'update-webhook-received', [
            'value' => $url
        ]);
    }

    /**
     *Atualiza o webhook de mudança nos status das mensagens
     */
    public function updateWebhookMessageStatus(string $url)
    {
        return $this->request('PUT', 'update-webhook-message-status', [
            'value' => $url
        ]);
    }
}
