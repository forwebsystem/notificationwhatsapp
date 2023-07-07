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

            $response = $response->getBody()->getContents();
            return json_decode($response, true);
        } catch (RequestException $e) {
            if ($e->hasResponse()) {
                return $e->getResponse()->getBody()->getContents();
            }

            return $e->getMessage();
        }
    }
}
