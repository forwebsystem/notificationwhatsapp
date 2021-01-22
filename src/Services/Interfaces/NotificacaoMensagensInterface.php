<?php

namespace ForWebSystem\NotificationWhatsApp\Services\Interfaces;

/**
 *
 * Envie e receba mensagens de texto, envie arquivos, marque mensagens como lida.
 */
interface NotificacaoMensagensInterface
{

    /**
     * Envie uma mensagem de texto
     */
    public function sendText(string $phone, string $message);

    /**
     * Envie um contato
     */
    public function sendContact(string $phone, string $contactName, string $contactPhone, string $contactBusinessDescription=null);

    /**
     * Envie uma imagem em base64 ou URL
     */
    public function sendImage(string $phone, string $image);

    /**
     * Envie um audio em base64 ou URL
     */
    public function sendAudio(string $phone, string $audio);

    /**
     * Envie um video em base64 ou URL
     */
    public function sendVideo(string $phone, string $video);

    /**
     * Enviar arquivo
     */
    public function sendDocument​(string $phone, string $document);

    /**
     * Envie um link
     */
    public function sendLink(string $phone, string $message, string $linkUrl, string $image = null, string $title = null, string $linkDescription = null);

    /**
     * Marque uma mensagem como lida
     */
    public function readMessage(string $phone, string $messageId);
}
