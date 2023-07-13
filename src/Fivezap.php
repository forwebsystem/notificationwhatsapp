<?php

namespace ForWebSystem\NotificationWhatsApp;

use Exception;
use ForWebSystem\NotificationWhatsApp\Contracts\FivezapInterface;
use ForWebSystem\NotificationWhatsApp\Traits\RequestTrait;
use ForWebSystem\NotificationWhatsApp\Contracts\SenderInterface as Sender;
use ForWebSystem\NotificationWhatsApp\Contracts\ReceiverInterface as Receiver;

class Fivezap implements FivezapInterface
{
    use RequestTrait;

    /**
     * Url da API.
     *
     * @var string
     */
    private string $host = "http://localhost:3000/";

    /**
     * Versao da API.
     *
     * @var string
     */
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
     * Identificador unico que vincula contact a inbox.
     *
     * @var string
     */
    private string $source_id;

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

    /**
     * Guarda alguns erros que podem ocorrer.
     *
     * @var string
     */
    private string $errors;

    public function __construct(Sender $sender, Receiver $receiver)
    {
        // if (!$this->checkEnv()) {
        //     echo $this->getErrors(); die;
        // }
        
        // $this->host = $_ENV['FIVEZAP_HOST'];
        // $this->api_version = $_ENV['FIVEZAP_API_VERSION'];
        
        // Preenche propriedades do remetente.
        $this->token = $sender->token();
        $this->account_id = $sender->account();
        $this->inbox = $sender->inbox();
        
        // Preenche propriedades do destinatário.
        $this->receiver_name = $receiver->name();
        $this->receiver_email = $receiver->email();
        $this->receiver_phone = $receiver->phone();

        // cabeçalho para todas as requisições da trait.
        $this->headers =
        [
            'Content-Type' => 'application/json',
            'api_access_token' => $this->token
        ];

        $this->searchContact();
    }
    
    /**
     * Envia mensagem de texto.
     *
     * @param string $message
     * @return void
     */
    public function message(string $message)
    {
        // Se ainda não existe uma conversação, busca uma aberta ou cria nova.
        try {
            if (!isset($this->conversation)) {
                throw new Exception('Ooops... erro ao se conectar com a API.');
            }

            $this->method = 'POST';
            $this->end_point = "/accounts/$this->account_id/conversations/{$this->conversation->id}/messages";
            $this->url = $this->host . $this->api_version . $this->end_point;

            $this->body = 
            [
                "content" => $message,
                "message_type" => "outgoing"
            ];

            $response = $this->makeHttpRequest();

            return $response;
        } catch (Exception $e) {
            return $e->getMessage();
        }
        
        
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

        $response = $this->makeHttpRequest();
        
        if (isset($response['meta']) && $response['meta']['count'] == 1) {
            $meta = $response['meta'];
            $payload = $response['payload'];
            $this->source_id = $payload[0]['contact_inboxes'][0]['source_id'];
            $this->contact = $this->toObject($payload[0]);
            if ($this->status_code == 200 && $response['meta']['count'] == 1) {
                $this->getContactConversation();
            }
            
            return $this->contact;
        }

        return $this->createContact();
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

        $this->body =
        [
            'name' => $this->receiver_name,
            'inbox_id' => $this->inbox,
            'phone_number' => $this->receiver_phone,
            'email' => $this->receiver_email
        ];

        $response = $this->makeHttpRequest();

        if(isset($response['payload']) && $response['payload']) {
            $payload = $response['payload'];
            $this->source_id = $payload[0]['contact_inboxes'][0]['source_id'];
            $this->contact = $this->toObject($payload['contact']);
            
            $this->createConversation();
            return $this->contact;
        }

        return $response;
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

        $response = $this->makeHttpRequest();
        $conversations = $response['payload'];

        // filtra array de conversação e pega a conversação da inbox.
        $result = array_filter($conversations, fn($el) => (
            ($el['inbox_id'] == $this->inbox) && ($el['status'] == 'open')
        ));
        
        $conversation = reset($result);

        if($conversation) {
            $this->conversation = $this->toObject($conversation);

            return $this;
        }

        $this->createConversation();
        return $this;
    }

    /**
     * Cria uma nova conversação com status open.
     *
     * @return void
     */
    public function createConversation()
    {
        $this->method = 'POST';
        $this->end_point = "/accounts/{$this->account_id}/conversations";
        $this->url = $this->host . $this->api_version . $this->end_point;

        $this->body =
        [
            'source_id' => $this->source_id,
            "status" => "open"
        ];

        $response = $this->makeHttpRequest();

        if($response) {
            $this->conversation = $this->toObject($response);
            return $this->conversation;
        }

        return $response;
    }

    public function checkEnv()
    {
        $err = [];
        // Verifica se existe host definido no arquivo .env.
        if (!isset($_ENV['FIVEZAP_HOST'])) {
            $exception = new \Exception('FIVEZAP_HOST não encontado no arquivo .env');
            $err[] = $exception->getMessage();
        }

        // Verifica se existe a versão da api definida no arquivo .env.
        if (!isset($_ENV['FIVEZAP_API_VERSION'])) {
            $exception = new \Exception('FIVEZAP_API_VERSION não encontado no arquivo .env');
            $err[] = $exception->getMessage();
        }

        if ($err) {
            $this->errors = json_encode($err);
            return false;
        }

        return true;
    }

    /**
     * Retorna erros.
     *
     * @return string
     */
    public function getErrors()
    {
        return $this->errors;
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
