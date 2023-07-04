<?php

namespace ForWebSystem\NotificationWhatsApp\Contracts;

interface NotificationInterface
{
    /**
     * Coleta a mensagem.
     *
     * @return array
     */
    public function text(): object;

    /**
     * Coleta os anexos da mensagem se existir.
     *
     * @return void
     */
    public function attachments(array $data): object;

    /**
     * Envia a mensagem.
     *
     * @return boolean
     */
    public function send(): bool;
}
