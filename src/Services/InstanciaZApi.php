<?php

namespace ForWebSystem\NotificationWhatsApp\Services;

use ForWebSystem\NotificationWhatsApp\Services\Interfaces\InstanciaInterface;
use ForWebSystem\NotificationWhatsApp\Traits\RequestZApiTrait;

/**
 *
 * APIs relacionada a conexão e status da sua instância
*/
class InstanciaZApi implements InstanciaInterface
{
    use RequestZApiTrait;

    /**
     * Retorna os bytes da imagem do QRCode do Whatsapp.
     */
    public function qrCode()
    {
        return $this->request('GET', 'qr-code', []);
    }

    /**
     * Retorna o Base64 da imagem do QRCode do Whatsapp.
     */
    public function qrCode​Imagem()
    {
        return $this->request('GET', 'qr-code/image', []);
    }

    /**
     * Reinicie sua instância
     */
    public function restart()
    {
        return $this->request('GET', 'restart', []);
    }

    /**
     * Desconectar conta do Whatsapp sua instância
     */
    public function disconnect()
    {
        return $this->request('GET', 'disconnect', []);
    }

    /**
     * Pegue o status da sua instância
     */
    public function status()
    {
        return $this->request('GET', 'status', []);
    }

    /**
     * Restaurar a sessão da sua instância
     */
    public function restoreSession()
    {
        return $this->request('GET', 'restore-session', []);
    }
}
