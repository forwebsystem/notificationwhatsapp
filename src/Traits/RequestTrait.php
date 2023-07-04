<?php

namespace ForWebSystem\NotificationWhatsApp\Traits;

use GuzzleHttp\Client;
use Illuminate\Support\Facades\Log;

trait RequestTrait
{
    private function __construct()
    {
    }

    private function request(string $method, string $endPoint, array $data)
    {
        $content = '';
        $status = '200';
        try {
            $options = [
                'body' => json_encode($data),
                'headers' => [
                    'Content-Type' => 'application/json'
                ]
            ];

            $cliente = new Client();
            $response = $cliente->request($method, "{$this->url}/{$endPoint}", $options);
            $content = json_decode($response->getBody()->getContents(), true);

            return $content;
        } catch (\GuzzleHttp\Exception\ClientException $e) {
            $content = $e->getResponse()->getBody()->getContents();
            $content = !empty($content) ? $content : $e->getMessage();
            $result = json_decode($content);

            $status = $result->status ?? '500';
            $message = $result->message ?? '';
            $error = $result->error ?? '';

            throw new ClientException($content, $e->getCode(), $e);
        } finally {
            $this->saveLog($method, $status, $endPoint, $data, $content);
        }
    }
}
