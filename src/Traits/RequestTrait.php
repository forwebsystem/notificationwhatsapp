<?php

namespace ForWebSystem\NotificationWhatsApp\Traits;


use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;

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

            return json_decode($response, true);
        } catch (RequestException $e) {
            // para qualquer codigo diferente de 200;
            if ($e->hasResponse()) {
                $this->erros['code'] = $e->getResponse()->getStatusCode();
                $this->erros['message'] = $e->getResponse()->getBody()->getContents();

                return $e->getResponse()->getBody()->getContents();
            }

            return $e->getMessage();
        }
    }
}
