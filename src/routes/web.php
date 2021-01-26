<?php

use App\Notifications\BemVindoNotification;
use App\User;
use ForWebSystem\NotificationWhatsApp\Services\NotificacaoZApiService;
use ForWebSystem\NotificationWhatsApp\Services\WebhooksZApi;
use ForWebSystem\NotificationWhatsApp\Services\InstanciaZApi;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/notificacaowhatsapp/send', function () {

    // User::create([
    //     'name' => 'João Paulo',
    //     'email'=> 'joaopaulo@fws.com.br',
    //     'password' => Crypt::encrypt('123456')
    // ]);
    $user = User::first();
    $config = new NotificacaoZApiService($user, config('notificationwhatsapp.instancia_id'), $user->license);

    $result = $config->sendLink('556298506431', '*Automatica:* Só me ignora blz.', 'https://z-api.io', 'https://firebasestorage.googleapis.com/v0/b/zaap-messenger-web.appspot.com/o/logo.png?alt=media', 'Bora ver se dar certo', 'Descrição');
    // $result = $config->sendText('556292619927','Teste ');

    return [
        'mensagem enviada',
        $user,
        $result
    ];
});


Route::get('/notificacaowhatsapp/webhook', function () {

    $user = User::first();
    $config = new WebhooksZApi($user, config('notificationwhatsapp.instancia_id'), $user->license);

    $webhookDelivery        = $config->updateWebhookDelivery('https://webhook.site/ad295589-4175-411d-94c2-dc0b2618ceda');
    $webhookDisconnected    = $config->updateWebhookDisconnected('https://webhook.site/7df9b8c0-674f-45c5-8a80-e36ff72dc69a');
    $webhookReceived        = $config->updateWebhookReceived('https://webhook.site/7fcf4ef8-d8f4-4303-9ff8-6e3528f5bb49');
    $webhookMessageStatus   = $config->updateWebhookMessageStatus('https://webhook.site/49ab69c5-8f56-47f5-bcd2-e6964ad4fb73');

    return [
        'mensagem enviada',
        $webhookDelivery,
        $webhookDisconnected ??  '',
        $webhookReceived ?? '',
        $webhookMessageStatus ?? '',
    ];
});

Route::get('/notificacaowhatsapp/instancia', function () {

    $user = User::first();
    $config = new InstanciaZApi($user, config('notificationwhatsapp.instancia_id'), $user->license);

    $imagens = $config->qrCode​Imagem();
    if (!empty($imagens['value'])) {
        die("<img src='{$imagens['value']}' />");
    }

    return [
        'status',
        $imagens
    ];
});

Route::get('/notificacaowhatsapp/instancia/disconnect', function () {

    $user = User::first();
    $config = new InstanciaZApi($user, config('notificationwhatsapp.instancia_id'), $user->license);

    $imagens = $config->disconnect();
    if (!empty($imagens['value'])) {
        die("<img src='{$imagens['value']}' />");
    }

    return [
        'status',
        $imagens
    ];
});
