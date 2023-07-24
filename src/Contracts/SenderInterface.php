<?php

namespace ForWebSystem\NotificationWhatsApp\Contracts;

interface SenderInterface
{
    /**
     * Método responsável para fornecer o token.
     *
     * @return string
     */
    public function getToken(): string;

    /**
     * Método responsável por fornecer o identificador da conta do usuário.
     *
     * @return integer
     */
    public function getAccount(): int;

    /**
     * Método responsável por fornecer o id da caixa de entrada de destino.
     *
     * @return integer
     */
    public function getInbox(): int;
}
