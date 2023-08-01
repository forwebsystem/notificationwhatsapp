<?php

namespace ForWebSystem\NotificationWhatsApp;

use ForWebSystem\NotificationWhatsApp\Contracts\FivezapInterface;
use ForWebSystem\NotificationWhatsApp\Traits\RequestTrait;
use ForWebSystem\NotificationWhatsApp\Contracts\SenderInterface as Sender;
use ForWebSystem\NotificationWhatsApp\Contracts\ReceiverInterface as Receiver;
use ForWebSystem\NotificationWhatsApp\Exceptions\FivezapException;

class Fivezap implements FivezapInterface
{
    use RequestTrait;

    /**
     * Url da API.
     *
     * @var string
     */
    private string $host = 'http://localhost:3000/';

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

    public function __construct(Sender $sender, Receiver $receiver)
    {
        // busca constantes do .env
        $this->host = $_ENV['FIVEZAP_HOST'];
        $this->api_version = $_ENV['FIVEZAP_API_VERSION'];

        // Preenche propriedades do remetente.
        $this->sender = $sender;
        $this->token = $sender->getToken();
        $this->account_id = $sender->getAccount();
        $this->inbox = $sender->getInbox();

        // Preenche propriedades do destinatário.
        $this->receiver = $receiver;
        $this->receiver_name = $receiver->getName();
        $this->receiver_email = $receiver->getEmail();
        $this->receiver_phone = $receiver->getPhone();

        // cabeçalho para todas as requisições da trait.
        $this->headers =
        [
            'Content-Type' => 'application/json',
            'api_access_token' => $this->token
        ];

        // busca ou cria contato em todas as requests.
        $this->searchContact();
    }

    /**
     * Envia mensagem de texto.
     *
     * @param string $message
     * @return string|object
     */
    public function message(string $message)
    {
        // Se ainda não existe conversação, busca aberta ou cria nova.
        if (!isset($this->conversation)) {
            $this->getContactConversation();
        }

        $this->method = 'POST';
        $this->end_point = "/accounts/$this->account_id/conversations/{$this->conversation->id}/messages";
        $this->url = $this->host . $this->api_version . $this->end_point;

        $this->body =
        [
            'content' => $message,
            'message_type' => 'outgoing'
        ];

        $response = $this->makeHttpRequest();
        return $response;
    }

    /**
     * Busca um contato pelo name, identifier, email ou phone number
     *
     * @param string $param
     * @return object
     */
    public function searchContact(string $param = null)
    {
        // O formato esperado é +55xx912345678 ou +55xx12345678.
        // formato E.164, por exemplo: +5511000000000
        $value = $param ?? $this->receiver_phone;
        $len = strlen($value);
        $country_code = substr($value, 0, 3);

        // pega os 3 primeiros caracteres do telefone
        if ($country_code != '+55') {
            throw new FivezapException('O contato não pertence ao Brasil. ', 400);
        }

        // se menor que 13 e menor que 14 ou naior que 15 gero exceção.
        if (($len < 13 && $len < 14) || $len > 14) {
            throw new FivezapException('Número de telefone inválido.', 400);
        }

        $this->method = 'GET';
        $this->end_point = "/accounts/$this->account_id/contacts/search?q={$value}";
        $this->url = $this->host . $this->api_version . $this->end_point;

        $response = $this->makeHttpRequest();

        // para mais de um resultado, filtra e compara um que seja igual ao recebido.
        if (isset($response['meta']) && $response['meta']['count']) {
            $meta = $response['meta'];
            $payload = $response['payload'];

            // filtra contato se houver mais de um resultado.
            if ($response['meta']['count'] > 1) {
                $payload = array_filter($payload, fn ($el) => ($el['phone_number'] == $value));
            }

            if (!$payload) {
                throw new FivezapException('Contato não encontrado na pesquisa.', 404);
            }

            // reseta ponteiro do array
            $payload = reset($payload);
            $this->contact = $this->toObject($payload);

            // se o contato não está vinculado a uma inbox, cria vinculo.
            if (!$payload['contact_inboxes']) {
                $contact_inbox = $this->createContactInboxes();
            }

            // atribui source_id
            $this->source_id = $contact_inbox['source_id'] ?? $payload['contact_inboxes'][0]['source_id'];

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

        // se contato existe, preebche atributos e retorna contato.
        if (isset($response['payload']) && $response['payload']) {
            $payload = $response['payload'];
            $this->source_id = $payload['contact']['contact_inboxes'][0]['source_id'];
            $this->contact = $this->toObject($payload['contact']);

            return $this->contact;
        }

        // retorna contato buscado.
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
        $result = array_filter($conversations, fn ($el) => (
            ($el['inbox_id'] == $this->inbox) && ($el['status'] == 'open')
        ));

        // remove primeiro ponteiro do array.
        $conversation = reset($result);

        // se o array nao for vazio atribui e retorna.
        if ($conversation) {
            $this->conversation = $this->toObject($conversation);

            return $this;
        }

        // se nao encontra uma conversação aberta, cria uma.
        $this->createConversation();
        return $this;
    }

    /**
     * Cria uma nova conversação com status pendig.
     *
     * @return object
     */
    public function createConversation()
    {
        $this->method = 'POST';
        $this->end_point = "/accounts/{$this->account_id}/conversations";
        $this->url = $this->host . $this->api_version . $this->end_point;

        $this->body =
        [
            'source_id' => $this->source_id,
            'status' => 'pending'
        ];

        $response = $this->makeHttpRequest();

        // se criou corretamente preenche atributo e retorna.
        if ($response) {
            $this->conversation = $this->toObject($response);
            return $this->conversation;
        }

        // se encontrou retorna.
        return $response;
    }

    /**
     * Cria vinculo do contato com uma inbox.
     *
     * @return void
     */
    public function createContactInboxes()
    {
        $this->method = 'POST';
        $this->end_point = "/accounts/{$this->account_id}/contacts/{$this->contact->id}/contact_inboxes";
        $this->url = $this->host . $this->api_version . $this->end_point;

        $this->body =
        [
            'inbox_id' => $this->inbox,
            'source_id' => $this->contact->phone_number
        ];

        $response = $this->makeHttpRequest();

        return $response;
    }

    /**
     * Cadastro de webhooks.
     * 
     * conversation_created
     * conversation_status_changed
     * conversation_updated
     * message_created
     * message_updated
     * webwidget_triggered
     *
     * @param string $url
     * @param array $subscriptions
     * @return void
     */
    public function webhookCreate(string $url, array $subscriptions = ['message_created'])
    {
        $this->method = 'POST';
        $this->end_point = "/accounts/{$this->account_id}/webhooks";
        $this->url = $this->host . $this->api_version . $this->end_point;

        $this->body =
        [
            'webhook' => [
                "url" => $url,
                "subscriptions" => $subscriptions
            ]
        ];

        $response = $this->makeHttpRequest();

        return $response;
    }

    /**
     * Sender.
     *
     * @return object
     */
    protected function sender()
    {
        return $this->sender;
    }

    /**
     * Receiver.
     *
     * @return object
     */
    protected function receiver()
    {
        return $this->receiver;
    }

    /**
     * Chama métodos da classe dinamicamente caso haja necessidade.
     *
     * @param string $method
     * @param array $params
     * @return void
     */
    public static function execMethod(Sender $sender, Receiver $receiver, string $method = '', array $params)
    {
        // Verifica se metodo passado existe.
        if (!method_exists(Self::class, $method)) {
            Throw new FivezapException("Método $method() não encontrado no contexto atual.");
        }
        
        // Objeto de da classe atual.
        $fivezap = (new self($sender, $receiver));

        // Executa metodo passando parâmetros.
        return call_user_func_array([$fivezap, $method], $params);
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

    /**
     * Recupera erros da requisição.
     *
     * @return void
     */
    public function getErrors()
    {
        return json_encode(
            [
                'status' => $this->erros['code'],
                'message' => $this->erros['message']
            ]
        );
    }
}
