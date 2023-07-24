<?php

namespace ForWebSystem\NotificationWhatsApp\Contracts;

interface ReceiverInterface
{
    /**
     * Método que retorna o nome do destinatário.
     *
     * @return string
     */
    public function getName(): string;

    /**
     * Método que retorna o email do destinatário.
     *
     * @return string
     */
    public function getEmail(): string;

    /**
     * Método que retorna o telefone do destinatário.
     *
     * @return string
     */
    public function getPhone(): string;
}
