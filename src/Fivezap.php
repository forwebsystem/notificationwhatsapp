<?php

namespace ForWebSystem\NotificationWhatsApp;

use ForWebSystem\NotificationWhatsApp\Contracts\SenderInterface as Sender;
use ForWebSystem\NotificationWhatsApp\Contracts\ReceiverInterface as Receiver;
use ForWebSystem\NotificationWhatsApp\Traits\RequestTrait;

class Fivezap
{
    use RequestTrait;

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

    /**
     * Token da conta no FiveZap.
     *
     * @var string
     */
    private string $token = '';

    /**
     * ID da conta no FiveZap.
     *
     * @var integer
     */
    private int $account_id;

    /**
     * ID da caixa de entrada no FiveZap.
     *
     * @var integer
     */
    private int $inbox;

    /**
     * Nome do destinatário.
     *
     * @var string
     */
    private string $receiver_name = '';

    /**
     * Email do destinatário.
     *
     * @var string
     */
    private string $receiver_email = '';

    /**
     * Telefone do destinatário.
     *
     * @var string
     */
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
        $this->url = $this->host . $this->api_version . $this->end_point;

        $this->headers =
        [
            'Content-Type' => 'application/json',
            'api_access_token' => $this->token
        ];

        $response = $this->makeHttpRequest();

        $meta = $response['meta'];
        $payload = $response['payload'];

        if ($meta['count'] == 1) {
            $this->contact = json_decode(json_encode($payload[0]), false);

            return $this->contact;
        }

        return $payload;
    }
}
