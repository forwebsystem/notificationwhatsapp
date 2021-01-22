<?php

namespace ForWebSystem\NotificationWhatsApp\Services\Interfaces;

/**
 *
 * Webhooks de sua instância
 */
interface WebhooksInterface
{

    /**
     *Atualiza o webhook de entrega da mensagem
     */
    public function updateWebhookDelivery(string $url);

    /**
     *Atualiza o webhook para avisar quando o whatsapp desconectar
     */
    public function updateWebhookDisconnected(string $url);

    /**
     *Atualiza o webhook de entrega de mensagem recebidas pelo whatsapp
     */
    public function updateWebhookReceived(string $url);

    /**
     *Atualiza o webhook de mudança nos status das mensagens
     */
    public function updateWebhookMessageStatus(string $url);
}
