<?php

namespace ForWebSystem\NotificationWhatsApp\Services;

use Exception;
use ForWebSystem\NotificationWhatsApp\Model\NotificationWhatsAppMensagem;
use ForWebSystem\NotificationWhatsApp\Services\Interfaces\NotificacaoMensagemReceivedInterface;
use Illuminate\Support\Facades\Log;

/**
 *
 * Envie e receba mensagens de texto, envie arquivos, marque mensagens como lida.
 */
class NotificacaoZApiMensagemReceived implements NotificacaoMensagemReceivedInterface
{

    public function save($method, $url, $type, array $data)
    {
        try {

            $phone = isset($data['participantPhone']) ? $data['participantPhone'] : $data['phone'];

            return NotificationWhatsAppMensagem::create(array_merge([
                'user_type'         => 'Webhook',
                'user_id'           => '0',
                'service'           => 'zapi',
                'method'            => $method,
                'url'               => $url,
                'type_mensagem'     => $type,
                'phone_destination' => '--',
                'phone_participant' => $phone,
                'context'           => $this->getContext($data),
                'result'            => json_encode($data),
                'sender_name'       => $data['senderName'] ?? '',
                'chat_name'         => $data['chatName'] ?? '',
                'message_id'        => $data['messageId'] ?? '',
                'from_me'           => $data['fromMe'] ?? '',
                'status'            => $data['status'] ?? '',
            ], $data));

        } catch (Exception $error) {

            Log::debug(json_encode([
                'error_save' => json_encode([
                    'message' => $error->getMessage(),
                ]),
                'method'            => $method,
                'url'               => $url,
                'type_mensagem'     => $type,
                'phone_participant' => $phone,
                'context'           => $this->getContext($data),
                'result'            => json_encode($data),
                'sender_name'       => $data['senderName'] ?? '',
                'chat_name'         => $data['chatName'] ?? '',
                'message_id'        => $data['messageId'] ?? '',
                'from_me'           => $data['fromMe'] ?? '',
                'status'            => $data['status'] ?? '',
            ]));
        }
    }

    private function getContext(array $data)
    {
        $types = ['text', 'image', 'audio', 'video', 'contact', 'document', 'location', 'sticker'];
        foreach ($types as $type) {
            if (!empty($data[$type])) {
                return json_encode($data[$type]);
            }
        }
    }

    /**
     * public function receivedImage(string $caption, string $imageUrl, string $thumbnailUrl, string $mimeType);
     * public function receivedAudio(string $mimeType, string $audioUrl);
     * public function receivedVideo(string $caption, string $videoUrl, string $mimeType);
     * public function receivedContact(string $displayName, string $vCard);
     * public function receivedDocument(string $mimeType, string $fileName, string $title, string $pageCount, string $thumbnailUrl, string $documentUrl);
     * public function receivedLocation(string $longitude, string $latitude, string $url, string $name, string $address, string $thumbnailUrl);
     * public function receivedSticker(string $mimeType, string $stickerUrl);
     */
}
