<?php

namespace ForWebSystem\NotificationWhatsApp\Traits;

use DateTime;
use Exception;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;
use Ramsey\Uuid\Uuid;
use function app;
use function config;
use function GuzzleHttp\json_decode;
use function GuzzleHttp\json_encode;

/**
 * Description of Sms
 *
 * @author joaopaulo
 */
trait EnviarNotificationWhatsAppTrait
{

    private $key = null;
    private $url = null;
    private $license = null;

    public function __construct()
    {
        $this->url = config('notificationwhats.url');
        $this->key = config('notificationwhats.key');
        $this->license = config('notificationwhats.license');
    }

    protected function enviarMensagem(string $numero, string $mensagem)
    {

        $id = Uuid::uuid1()->toString();

        $envio = $this->request("send_message", [
            'phone_no' => $numero,
            'message' => $mensagem
        ]);

        \Log::channel('debug')->info("SMS enviado com o id {$id} - {$numero} - {$mensagem}");
        \Log::channel('debug')->info($envio);

        $dados = [
            'id' => $id,
            'servidor' => 'gtisms',
            'numero' => $numero,
            'texto' => $mensagem,
            'status' => $envio['reason'] ?? '',
            'resposta_envio' => json_encode($envio)
        ];

        return $id;
    }

    public function verificarStatus(string $tipo, string $id)
    {

        $status = $this->request("{$tipo}/VerificaStatus", [
            'id' => $id
        ]);

        $model = app(SmsEnviadoRepository::class)->find($id);

        $model->status = $status['reason'] ?? $status['result'];
        $model->resposta_consulta = json_encode($status);

        $model->save();

        return $status;
    }



    private function request($endpoint, array $parans = [])
    {
        $key = $this->key; //this is demo key please change with your own key
        $url = $this->url . '/' . $endpoint;

        $data = array_merge($parans, array(
            "key"        => $key,
            "skip_link"    => True // This optional for skip snapshot of link in message
        ));
        $data_string = json_encode($data);

        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_VERBOSE, 0);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 0);
        curl_setopt($ch, CURLOPT_TIMEOUT, 360);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt(
            $ch,
            CURLOPT_HTTPHEADER,
            array(
                'Content-Type: application/json',
                'Content-Length: ' . strlen($data_string)
            )
        );
        $res = curl_exec($ch);
        curl_close($ch);

        return $res;
    }

    public function setWebHook()
    {
        $data["license"] = $this->license;
        $data["url"]    = "https://webhook.site/04cff972-e2bf-4e8b-810c-01a74136cb67"; // message data will push to this url
        $data["no_wa"]  = "+5562992563967";    //sender number registered in notificationwhats
        $data["action"] = "set";

        $url = "http://api.woo-wa.com/v2.0/webhook";
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $result = curl_exec($ch);
        $err = curl_error($ch);
        curl_close($ch);
        if ($err) {
            echo "cURL Error #:" . $err;
        } else {
            echo $result;
        }
    }
    private function dd()
    {

        $formParams = array_merge($parans, [
            'key' => $this->key,
        ]);

        $multipart = [];
        array_walk($formParams, function ($valor, $chave) use (&$multipart) {

            array_push($multipart, [
                'name' => $chave,
                'contents' => $valor,
            ]);
        });

        try {

            $client = new Client();
            $response = $client->request('POST', "{$this->url}/{$endpoint}", ['multipart' => $multipart]);


            $content = json_decode($response->getBody()->getContents(), true);

            if (!is_array($content)) {
                throw new Exception('Dados não foi possivel carregar');
            }

            if (isset($content['reason']) && $content['reason'] == 'error') {
                throw new Exception($content['message']);
            }

            return $content;
        } catch (ClientException $error) {
            die($error->getMessage());
        }
    }
}
