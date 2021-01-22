<?php

namespace ForWebSystem\NotificationWhatsApp\Traits;

use Exception;
use ForWebSystem\NotificationWhatsApp\Model\NotificationAhatsAppMensagem;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Log;

trait RequestZApiTrait {

    private $user;

    public function __construct($user, string $idInstancia, string $tokenInstancia)
    {
        $this->user = $user;
        $this->url = "https://api.z-api.io/instances/{$idInstancia}/token/{$tokenInstancia}";
    }

    private function request(string $method, string $endPoint, array $datas)
    {

        $content = '';
        $status = '200';
        try {

            $cliente = new Client();
            $response = $cliente->request($method, "{$this->url}/{$endPoint}", ['form_params' => $datas]);
            $content = json_decode($response->getBody()->getContents(), true);

            if (!is_array($content)) {
                throw new Exception('The request could not be loaded');
            }

            return $content;

        } catch (\GuzzleHttp\Exception\ClientException $e) {

            $content = $e->getResponse()->getBody()->getContents();
            $result = json_decode($content);

            $status = $result->status ?? '500';
            return "{$result->error}: {$result->message}";

        } finally {
            $this->saveLog($status, $endPoint, $datas, $content);
        }
    }

    private function saveLog($status, $endPoint, $datas, $content)
    {

        try {

            NotificationAhatsAppMensagem::create([
                'user_type'         => get_class($this->user),
                'user_id'           => $this->user->id ?? '0',
                'service'           => 'zapi',
                'url'               => "{$this->url}/{$endPoint}",
                'type_mensagem'     => $endPoint,
                'phone_destination' => $datas['phone'],
                'context'           => json_encode($datas),
                'result'            => json_encode($content),
                'status'            => $status,
            ]);

        } catch (Exception $error) {

            Log::debug(json_encode([
                'error_save' => json_encode([
                    'message' => $error->getMessage(),
                ]),
                'endPoint'  => $endPoint,
                'dados'     => $datas,
                'content'   => $content
            ]));
        }
    }
}
