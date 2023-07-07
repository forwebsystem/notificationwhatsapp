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
    protected string $method;

    /**
     * Endpoint a ser acessado.
     *
     * @var string
     */
    protected string $end_point;

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

    public function makeHttpRequest($method, $url, $headers = [], $body = null)
    {
        $client = new Client();

        try {
            $response = $client->request($method, $url, [
                'headers' => $headers,
                'body' => $body,
            ]);

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
