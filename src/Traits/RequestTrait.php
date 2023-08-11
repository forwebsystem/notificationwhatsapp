<?php

namespace ForWebSystem\NotificationWhatsApp\Traits;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use ForWebSystem\NotificationWhatsApp\Exceptions\FivezapException;
use ForWebSystem\NotificationWhatsApp\Model\NotificationWhatsAppMensagem;

trait RequestTrait
{
    /**
     * Metodo para a requisição atual.
     *
     * @var string
     */
    protected string $method = '';

    /**
     * Endpoint.
     *
     * @var string
     */
    protected string $end_point = '';

    /**
     * Url.
     *
     * @var string
     */
    protected string $url = '';

    /**
     * Headers da requisição atual.
     *
     * @var array
     */
    protected array $headers = [];

    /**
     * Body da requisição atual.
     *
     */
    protected $body = null;

    /**
     * Multipart form data para requisições com envio de arquivos.
     *
     * @var array
     */
    protected array $form_data = [];

    /**
     * Resposta da requisição atual;
     *
     */
    private $response;

    /**
     * Guarda status code da requisição.
     *
     * @var integer
     */
    public int $status_code;

    /**
     * Array contendo mensagem e codigo de erro.
     *
     * @var array
     */
    public array $erros = [];

    /**
     * Sender para preenchimento de logs.
     *
     */
    protected abstract function sender();

    /**
     * Receiver para preenchimento de logs.
     *
     */
    protected abstract function receiver();

    public function makeHttpRequest()
    {
        $client = new Client();

        try {
            // Envia requisição.
            $response = $client->request(
                $this->method,
                $this->url,
                [
                    'headers' => $this->headers,
                    'multipart' => $this->form_data,
                    // 'body' => json_encode($this->body)
                ]
            );
            
            // Guarda status code
            $this->status_code = $response->getStatusCode();
            
            // Conteúdo da requisição.
            $response = $response->getBody()->getContents();

            // Converte para ojeto.
            $result = json_decode($response, true);

            // Guarda resposta.
            $this->response = $result;

            // Salva logs da request.
            $this->saveLog($response);

            return $result;
        } catch (RequestException $e) {
            // para qualquer codigo diferente de 200;
            if ($e->hasResponse()) {

                // Guarda erros.
                $this->erros['code'] = $e->getResponse()->getStatusCode();
                $this->erros['message'] = $e->getResponse()->getBody()->getContents();
                $this->saveLog(json_encode($this->erros));

                return $e->getResponse()->getBody()->getContents();
            }

            $this->saveLog($e->getMessage());
            return $e->getMessage();
        }
    }

    /**
     * Log de requisições.
     *
     * @param mixed $data
     * @return void
     */
    public function saveLog($data)
    {
        try {
            NotificationWhatsAppMensagem::create(
                [
                    'user_type' => get_class($this->sender),
                    'user_id' => $this->sender->id ?? 0,
                    'service' => 'fivezap',
                    'method' => $this->method,
                    'url' => $this->url,
                    'type_mensagem'     => $this->response['content_type'] ?? '',
                    'phone_destination' => '--',
                    'phone_participant' => $this->receiver()->telefone ?? '',
                    'sender_name'       => $this->sender->name ?? '',
                    'type' => 'outgoing',
                    'context' => json_encode($this->body) ?? null,
                    'result' => $data,
                    'status' => $this->response['status'] ?? '',
                ]
            );
        } catch (FivezapException $e) {
            Log::debug(
                json_encode(
                    [
                        'error_save' => json_encode(['message' => $e->getMessage()]),
                        'user_type' => get_class($this->sender),
                        'user_id' => $this->sender->id ?? 0,
                        'service' => 'fivezap',
                        'method' => $this->method,
                        'url' => $this->url,
                        'type_mensagem'     => $this->response['content_type'] ?? '',
                        'phone_destination' => '--',
                        'phone_participant' => $this->receiver()->telefone,
                        'sender_name'       => $this->sender->name ?? '',
                        'type' => 'outgoing',
                        'context' => json_encode($this->body),
                        'result' => $data,
                        'status' => $this->response['status'] ?? '',
                    ]
                )
            );
        }
    }
}
