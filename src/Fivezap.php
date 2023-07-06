<?php

namespace ForWebSystem\NotificationWhatsApp;

use ForWebSystem\NotificationWhatsApp\Contracts\SenderInterface as Sender;
use ForWebSystem\NotificationWhatsApp\Contracts\ReceiverInterface as Receiver;

class Fivezap extends Notification
{
    /**
     * Url da API.
     *
     * @var string
     */
    //private string $host = $_ENV['FIVEZAP_HOST'] ?? 'http://localhost:3000';
    private string $host = 'http://localhost:3000';

    /**
     * Versao da API.
     *
     * @var string
     */
    //private string $api_version = $_ENV['FIVEZAP_API_VERSION'] ?? 'api/v1';
    private string $api_version = 'api/v1';

    /**
     * ID da conversação atual.
     *
     * @var integer
     */
    private int $conversation_id;

    /**
     * Tipo do serviço que está sendo utilizado.
     *
     * @var string
     */
    private string $service = 'fivezap';

    /**
     * Objeto contendo o Remetente.
     *
     * @var Sender
     */
    private Sender $sender;

    /**
     * Objeto contendo o destinatário.
     *
     * @var Receiver
     */
    protected Receiver $receiver;

    public function __construct(Sender $sender, Receiver $receiver)
    {
        /*
        IMPLEMENTAR VALIDAÇÃO DE VARIAVEIS DO ENV.

        if(!isset($_ENV['FIVEZAP_HOST'])) {}
        if(!isset($_ENV['FIVEZAP_API_VERSION'])) {}
        */

        $this->sender = $sender;
        $this->receiver = $receiver;
    }

    /**
     * Coleta a mensagem de texto.
     *
     * @return object
     */
    public function text(): object
    {
        $this->method = 'POST';
        $this->end_point = "$this->host/accounts/{$this->sender->account()}/conversations/$this->conversation_id/messages";

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

    public function searchContact(string $param)
    {
        // Busca um contato pelo name, identifier, email ou phone number
        $this->method = 'POST';
        $this->end_point = "$this->host/$this->api_version/accounts/{$this->sender->account()}/contacts/search?q={$param}";
    }
}
