<?php

namespace ForWebSystem\NotificationWhatsApp\Services;

use ForWebSystem\NotificationWhatsApp\Model\NotificationWhatsAppMensagem;
use Illuminate\Support\Facades\Log;


class FivezapMessageService
{

    public function save($method, $url, $message_type, array $data)
    {
        $phone = explode('@', $data['conversation']['meta']['sender']['identifier'])[0];
        
        try {
            return NotificationWhatsAppMensagem::create(array_merge(
                [
                    'user_type'         => 'Webhook',
                    'user_id'           => '0',
                    'service'           => 'fivezap',
                    'method'            => $method,
                    'url'               => $url,
                    'type'              => $message_type,
                    'type_mensagem'     => $data['content_type'],
                    'phone_destination' => '--',
                    'phone_participant' => $phone,
                    'context'           => $data['content'],
                    'result'            => json_encode($data),
                    'sender_name'       => $data['conversation']['meta']['sender']['name'] ?? '',
                    'chat_name'         => $data['conversation']['id'] ?? '',
                    'message_id'        => $data['conversation']['messages'][0]['id'] ?? '',
                    'from_me'           => '',
                    'status'            => $data['conversation']['status'] ?? ''
                ], $data)
            );

        } catch (\Exception $error) {

            Log::debug(json_encode([
                'error_save' => json_encode([
                    'message' => $error->getMessage(),
                ]),
                'method'            => $method,
                'url'               => $url,
                'type_mensagem'     => $message_type,
                'phone_participant' => $phone,
                'context'           => $data['content'],
                'result'            => json_encode($data),
                'sender_name'       => $data['conversation']['meta']['sender']['name'] ?? '',
                'chat_name'         => $data['conversation']['id'] ?? '',
                'message_id'        => $data['conversation']['messages'][0]['id'] ?? '',
                'from_me'           => '',
                'status'            => $data['conversation']['status'] ?? ''
            ]));
        }
    }
}
