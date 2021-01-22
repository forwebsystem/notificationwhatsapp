<?php

namespace ForWebSystem\NotificationWhatsApp\Services;


use ForWebSystem\NotificationWhatsApp\Services\Interfaces\NotificacaoMensagensInterface;
use ForWebSystem\NotificationWhatsApp\Traits\RequestZApiTrait;

class NotificacaoZApiService implements NotificacaoMensagensInterface
{

    use RequestZApiTrait;

    /**
     * Envie uma mensagem de texto
     */
    public function sendText(string $phone, string $message)
    {
        return $this->request('POST', 'send-text', [
            'phone' => $phone,
            'message' => $message
        ]);
    }

    /**
     * Envie um contato
     */
    public function sendContact(string $phone, string $contactName, string $contactPhone, string $contactBusinessDescription = null)
    {
        return $this->request('POST', 'send-contact', [
            'phone'                         => $phone,
            'contactName'                   => $contactName,
            'contactPhone'                  => $contactPhone,
            'contactBusinessDescription'    => $contactBusinessDescription
        ]);
    }

    /**
     * Envie uma imagem em base64 ou URL
     */
    public function sendImage(string $phone, string $image)
    {
        return $this->request('POST', 'send-image', [
            'phone' => $phone,
            'image' => $image
        ]);
    }

    /**
     * Envie um audio em base64 ou URL
     */
    public function sendAudio(string $phone, string $audio)
    {
        return $this->request('POST', 'send-audio', [
            'phone' => $phone,
            'audio' => $audio
        ]);
    }

    /**
     * Envie um video em base64 ou URL
     */
    public function sendVideo(string $phone, string $video)
    {
        return $this->request('POST', 'send-video', [
            'phone' => $phone,
            'video' => $video
        ]);
    }

    /**
     * Enviar arquivo
     */
    public function sendDocument​(string $phone, string $document)
    {
        return $this->request('POST', 'send-document​', [
            'phone' => $phone,
            'document' => $document
        ]);
    }

    /**
     * Envie um link
     */
    public function sendLink(string $phone, string $message, string $linkUrl, string $image = null, string $title = null, string $linkDescription = null)
    {
        return $this->request('POST', 'send-link', [
            'phone'             => $phone,
            'message'           => $message,
            'linkUrl'           => $linkUrl,
            'image'             => $image,
            'title'             => $title,
            'linkDescription'   => $linkDescription
        ]);
    }

    /**
     * Marque uma mensagem como lida
     */
    public function readMessage(string $phone, string $messageId)
    {
        return $this->request('POST', 'read-message', [
            'phone' => $phone,
            'messageId' => $messageId
        ]);
    }
}
