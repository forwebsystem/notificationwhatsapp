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
    private string $host = 'http://localhost:3000/';

    /**
     * Versao da API.
     *
     * @var string
     */
    //private string $api_version = $_ENV['FIVEZAP_API_VERSION'] ?? 'api/v1';
    private string $api_version = 'api/v1';

    private string $token = '';
    private int $account_id;
    private int $inbox;

    private string $receiver_name = '';
    private string $receiver_email = '';
    private string $receiver_phone = '';

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

    /**
     * Contato buscado na API pelo método searchContact().
     *
     */
    private object $contact;

    public function __construct(Sender $sender, Receiver $receiver)
    {
        /*
        IMPLEMENTAR VALIDAÇÃO DE VARIAVEIS DO ENV.

        if(!isset($_ENV['FIVEZAP_HOST'])) {}
        if(!isset($_ENV['FIVEZAP_API_VERSION'])) {}
        */

        // Preenche propriedades do remetente.
        $this->token = $sender->token();
        $this->account_id = $sender->account();
        $this->inbox = $sender->inbox();

        // Preenche propriedades do destinatário.
        $this->receiver_name = $receiver->name();
        $this->receiver_email = $receiver->email();
        $this->receiver_phone = $receiver->phone();
    }

    /**
     * Coleta a mensagem de texto.
     *
     * @return object
     */
    public function text(): object
    {
        $this->method = 'POST';
        $this->end_point = "/accounts/$this->account_id/conversations/$this->conversation_id/messages";

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
     * Busca um contato pelo name, identifier, email ou phone number
     *
     * @param string $param
     * @return object
     */
    public function searchContact(string $param = null)
    {
        $value = $param ?? $this->receiver_phone;

        $this->method = 'GET';
        $this->end_point = "/accounts/$this->account_id/contacts/search?q={$value}";

        $this->headers =
        [
            'Content-Type' => 'application/json',
            'api_access_token' => $this->token
        ];

        $response = $this->makeHttpRequest(
            $this->method,
            $this->host . $this->api_version . $this->end_point,
            $this->headers,
            $this->body
        );

        $meta = $response['meta'];
        $payload = $response['payload'];

        if ($meta['count'] == 1) {
            $this->contact = json_decode(json_encode($payload[0]), false);

            return $this->contact;
        }

        return $payload;
    }
}
