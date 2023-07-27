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

    public string $user_type;

    public function makeHttpRequest()
    {
        $client = new Client();

        try {
            $response = $client->request(
                $this->method,
                $this->url,
                [
                    'headers' => $this->headers,
                    'body' => json_encode($this->body),
                ]
            );

            $this->status_code = $response->getStatusCode();
            $response = $response->getBody()->getContents();
            $this->saveLog($response);
            $result = json_decode($response, true);

            return $result;
        } catch (RequestException $e) {
            // para qualquer codigo diferente de 200;
            if ($e->hasResponse()) {
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
                    'user_type' => $this->user_type,
                    'user_id' => '0',
                    'service' => 'fivezap',
                    'method' => $this->method,
                    'url' => $this->url,
                    'type' => 'outgoing',
                    'context' => json_encode($this->body),
                    'result' => $data
                ]
            );
        } catch (FivezapException $e) {
            Log::debug(
                json_encode(
                    [
                        'error_save' => json_encode(['message' => $e->getMessage()]),
                        'user_type' => $this->user_type,
                        'user_id' => '0',
                        'service' => 'fivezap',
                        'method' => $this->method,
                        'url' => $this->url,
                        'type' => 'outgoing',
                        'context' => json_encode($this->body),
                        'result' => $data
                    ]
                )
            );
        }
    }
}
