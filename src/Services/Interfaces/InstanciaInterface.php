<?php

namespace ForWebSystem\NotificationWhatsApp\Services\Interfaces;


interface InstanciaInterface
{

    /**
     * Retorna os bytes da imagem do QRCode do Whatsapp.
     */
    public function qrCode();

    /**
     * Retorna o Base64 da imagem do QRCode do Whatsapp.
     */
    public function qrCode​Imagem();

    /**
     * Reinicie sua instância
     */
    public function restart();

    /**
     * Desconectar conta do Whatsapp sua instância
     */
    public function disconnect();

    /**
     * Pegue o status da sua instância
     */
    public function status();

    /**
     * Restaurar a sessão da sua instância
     */
    public function restoreSession();
}
