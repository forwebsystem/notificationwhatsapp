<?php

namespace ForWebSystem\NotificationWhatsApp\Traits;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;

trait RequestTrait
{
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
