<?php

namespace ForWebSystem\NotificationWhatsApp\Traits;

use Exception;
use ForWebSystem\NotificationWhatsApp\Exceptions\ClientException;
use ForWebSystem\NotificationWhatsApp\Model\NotificationWhatsAppMensagem;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Log;

trait RequestZApiTrait
{

    private $user;
    private static $instancias=[];

    public static function getInstancia($user)
    {
        if (isset(self::$instancias[__CLASS__])) {
            return self::$instancias[__CLASS__];
        }
        self::$instancias[__CLASS__] = new self($user, config('notificationwhatsapp.instancia_id'), $user->license );

        return self::$instancias[__CLASS__];
    }

    private function __construct($user, string $idInstancia, string $tokenInstancia)
    {
        $this->user = $user;
        $this->url = "https://api.z-api.io/instances/{$idInstancia}/token/{$tokenInstancia}";
    }

    private function request(string $method, string $endPoint, array $datas)
    {

        $content = '';
        $status = '200';
        try {

            $options = [
                'body' => json_encode($datas),
                'headers' => [
                    'Content-Type' => 'application/json'
                ]
            ];

            $cliente = new Client();
            $response = $cliente->request($method, "{$this->url}/{$endPoint}", $options);
            $content = json_decode($response->getBody()->getContents(), true);

            return $content;
        } catch (\GuzzleHttp\Exception\ClientException $e) {

            $content    = $e->getResponse()->getBody()->getContents();
            $content    = !empty($content) ? $content : $e->getMessage();
            $result     = json_decode($content);

            $status     = $result->status ?? '500';
            $message    = $result->message ?? '';
            $error      = $result->error ?? '';

            throw new ClientException($content, $e->getCode(), $e);
        } finally {
            $this->saveLog($method, $status, $endPoint, $datas, $content);
        }
    }

    private function saveLog($method, $status, $endPoint, $datas, $content)
    {

        try {

            NotificationWhatsAppMensagem::create([
                'user_type'         => get_class($this->user) ?? '--',
                'user_id'           => $this->user->id ?? '0',
                'service'           => 'zapi',
                'method'            => $method,
                'url'               => "{$this->url}/{$endPoint}",
                'type_mensagem'     => $endPoint,
                'phone_destination' => $datas['phone'] ?? 'service',
                'context'           => json_encode($datas),
                'result'            => json_encode($content),
                'status'            => $status,
            ]);
        } catch (Exception $error) {

            Log::debug(json_encode([
                'error_save' => json_encode([
                    'message' => $error->getMessage(),
                ]),
                'method'    => $method,
                'endPoint'  => $endPoint,
                'dados'     => $datas,
                'content'   => $content,
                'status'    => $status,
            ]));
        }
    }
}
