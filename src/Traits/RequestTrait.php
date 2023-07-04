<?php

namespace ForWebSystem\NotificationWhatsApp\Traits;

use GuzzleHttp\Client;

trait RequestTrait
{
    private function request(string $method, string $url, array $data)
    {
        $content = '';
        $status = http_response_code(200);
        try {
            $options = [
                'body' => json_encode($data),
                'headers' => [
                    'Content-Type' => 'application/json'
                ]
            ];

            $cliente = new Client();
            $response = $cliente->request($method, $url, $options);

            return json_decode($response->getBody()->getContents(), true);
        } catch (\GuzzleHttp\Exception\ClientException $e) {
            $content = $e->getResponse()->getBody()->getContents();
            $result = json_decode($content);
            $status = $result->status ?? http_response_code(500);

            throw new ClientException($content, $e->getCode(), $e);
        } /*finally {
            $this->saveLog($method, $status, $url, $data, $content);
        }*/
    }
}
