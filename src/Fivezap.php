<?php

namespace ForWebSystem\NotificationWhatsApp;

class Fivezap extends Notification
{
    /**
     * Url da API.
     *
     * @var string
     */
    private string $host;

    /**
     * ID do usuario na API.
     *
     * @var integer
     */
    private int $account_id;

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
        string $host,
        int $account_id,
        string $token,
        string $service = 'fivezap',
        string $content
    ) {
        $this->host = $host;
        $this->token = $token;
        $this->account_id = $account_id;
        parent::__construct($service, $content);
    }
}
