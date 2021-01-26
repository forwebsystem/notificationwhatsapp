<?php

namespace ForWebSystem\NotificationWhatsApp\Services\Interfaces;

/**
 *
 * Envie e receba mensagens de texto, envie arquivos, marque mensagens como lida.
 */
interface NotificacaoMensagemReceivedInterface
{

    /**
     * public function receivedText(array $message);
     * public function receivedImage(string $caption, string $imageUrl, string $thumbnailUrl, string $mimeType);
     * public function receivedAudio(string $mimeType, string $audioUrl);
     * public function receivedVideo(string $caption, string $videoUrl, string $mimeType);
     * public function receivedContact(string $displayName, string $vCard);
     * public function receivedDocument(string $mimeType, string $fileName, string $title, string $pageCount, string $thumbnailUrl, string $documentUrl);
     * public function receivedLocation(string $longitude, string $latitude, string $url, string $name, string $address, string $thumbnailUrl);
     * public function receivedSticker(string $mimeType, string $stickerUrl);
     */
}
