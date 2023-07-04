<?php

namespace ForWebSystem\NotificationWhatsApp;

class Fivezap extends Notification
{
    /**
     * Url da API.
     *
     * @var string
     */
    private string $host = 'https://chat.fivezap.com.br/api/v1';

    /**
     * ID do usuario na API.
     *
     * @var integer
     */
    private int $account_id;

    /**
     * ID da conversação atual na API.
     *
     * @var integer
     */
    private int $conversation_id;

    /**
     * Token do usuario na API.
     *
     * @var string
     */
    private string $token;

    /**
     * Tipo do serviço que está sendo utilizado.
     *
     * @var string
     */
    private string $service;

    public function __construct(
        int $account_id,
        string $token,
        string $content,
        string $service = 'fivezap'
    ) {
        $this->token = $token;
        $this->account_id = $account_id;
        $this->service = $service;

        parent::__construct($content);
    }

    /**
     * Coleta a mensagem de texto.
     *
     * @return object
     */
    public function text(): object
    {
        $this->method = 'POST';
        $this->end_point = "$this->host/accounts/$this->account_id/conversations/$this->conversation_id/messages";

        $this->data['content'] = $this->content;
        $this->data['message_type'] = 'outgoing';

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
}
