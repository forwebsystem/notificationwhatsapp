<?php

namespace ForWebSystem\NotificationWhatsApp;

use ForWebSystem\NotificationWhatsApp\Contracts\NotificationInterface;
use ForWebSystem\NotificationWhatsApp\Traits\RequestTrait;

abstract class Notification implements NotificationInterface
{
    use RequestTrait;

    /**
     * Tipo do serviço a ser utilizado.
     *
     * @var string
     */
    protected string $service;

    /**
     * Conteudo da mensagem.
     *
     * @var string
     */
    protected string $content;

    public function __construct(string $service, string $content)
    {
        $this->service = $service;
        $this->content = $content;
    }

    /**
     * Coleta a mensagem de texto.
     *
     * @return object
     */
    public function text(string $content): object
    {
        // code...
        return $this;
    }

    /**
     * Envia anexos da mensagem se existir.
     *
     * @return object
     */
    public function attachments(array $data): object
    {
        // code...,
        return $this;
    }

    /**
     * Faz o envio da mensagem.
     *
     * @return boolean
     */
    public function send(): bool
    {
        // code...
        return false;
    }
}
