<?php

namespace ForWebSystem\NotificationWhatsApp\Contracts;

interface SenderInterface
{
    /**
     * Método responsável para fornecer o token.
     *
     * @return string
     */
    public function token(): string;

    /**
     * Método responsável por fornecer o identificador da conta do usuário.
     *
     * @return integer
     */
    public function account(): int;

    /**
     * Método responsável por fornecer o id da caixa de entrada de destino.
     *
     * @return integer
     */
    public function inbox(): int;
}
