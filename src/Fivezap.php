<?php

namespace ForWebSystem\NotificationWhatsApp;

use ForWebSystem\NotificationWhatsApp\Contracts\FivezapInterface;
use ForWebSystem\NotificationWhatsApp\Traits\RequestTrait;
use ForWebSystem\NotificationWhatsApp\Contracts\SenderInterface as Sender;
use ForWebSystem\NotificationWhatsApp\Contracts\ReceiverInterface as Receiver;
use Spatie\Async\Pool;

class Fivezap implements FivezapInterface
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
     * Conversação do contato.
     *
     * @var object
     */
    private object $conversation;

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
        $this->prepare();
    }

    /**
     * Busca todos os dados necessários e preenche propriedades.
     *
     * @return void
     */
    public function prepare()
    {
        $this->searchContact();
        $this->getContactConversation();
    }


    /**
     * Envia mensagem de texto.
     *
     * @param string $message
     * @return void
     */
    public function message(string $message)
    {
        $this->method = 'POST';
        $this->end_point = "/accounts/$this->account_id/conversations/{$this->conversation->id}/messages";
        $this->url = $this->host . $this->api_version . $this->end_point;

        $this->headers =
        [
            'Content-Type' => 'application/json',
            'api_access_token' => $this->token
        ];

        $this->body = 
        [
            "content" => $message,
            "message_type" => "outgoing"
        ];

        $response = $this->makeHttpRequest();
        return $response;
        
    }

    /**
     * Busca um contato pelo name, identifier, email ou phone number
     *
     * @param string $param
     * @return array|object
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

        if ($meta['count'] == 1 && $payload) {
            $this->contact = $this->toObject($payload[0]);
        }
        
        return $this;
    }

    /**
     * Cria novo contato.
     *
     * @return object
     */
    public function createContact()
    {
        $this->method = 'POST';
        $this->end_point = "/accounts/$this->account_id/contacts";
        $this->url = $this->host . $this->api_version . $this->end_point;

        $this->headers =
        [
            'Content-Type' => 'application/json',
            'api_access_token' => $this->token
        ];

        $this->body =
        [
            'name' => $this->receiver_name,
            'inbox_id' => $this->inbox,
            'source_id' => $this->receiver_phone,
            'phone_number' => $this->receiver_phone,
            'email' => $this->receiver_email
        ];

        $response = $this->makeHttpRequest();
        $payload = $response['payload'];
        $this->contact = $this->toObject($payload['contact']);

        return $this->contact;
    }

    /**
     * Busca conversação atual do contato.
     *
     * @return void
     */
    public function getContactConversation()
    {
        $this->method = 'GET';
        $this->end_point = "/accounts/{$this->account_id}/contacts/{$this->contact->id}/conversations";
        $this->url = $this->host . $this->api_version . $this->end_point;

        $this->headers =
        [
            'Content-Type' => 'application/json',
            'api_access_token' => $this->token
        ];

        $response = $this->makeHttpRequest();
        $conversations = $response['payload'];

        // filtra array de conversação e pega a conversação da inbox.
        $result = array_filter($conversations, fn($el) => (
            ($el['inbox_id'] == $this->inbox) && ($el['status'] == 'open')
        ));
        
        $conversation = reset($result);

        $this->conversation = $this->toObject($conversation);
        return $this;
    }

    /**
     * Converte array para objeto.
     *
     * @param array $array
     * @return void
     */
    public function toObject(array $array)
    {
        return json_decode(json_encode($array), false);
    }
}
