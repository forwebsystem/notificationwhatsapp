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

Route::get('notificacaowhatsapp/config/webhook',         [
    'as'        => 'notificacaowhatsapp.config.webhook',
    'namespace' => 'ForWebSystem\NotificationWhatsApp\Http\Controllers',
    'uses'      => 'NotificationWhatsAppController@webhook'
]);

Route::get('notificacaowhatsapp/config/instancia',         [
    'as'        => 'notificacaowhatsapp.config.instancia',
    'namespace' => 'ForWebSystem\NotificationWhatsApp\Http\Controllers',
    'uses'      => 'NotificationWhatsAppController@instancia'
]);
Route::get('notificacaowhatsapp/config/disconnect',         [
    'as'        => 'notificacaowhatsapp.config.disconnect',
    'namespace' => 'ForWebSystem\NotificationWhatsApp\Http\Controllers',
    'uses'      => 'NotificationWhatsAppController@disconnect'
]);
