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

Route::middleware('api')
    ->prefix('notificacaowhatsapp')
    ->as('notificacaowhatsapp.')
    ->namespace('ForWebSystem\NotificationWhatsApp\Http\Controllers')
    ->group(function () {

        Route::get('config/webhook',    ['as'   => 'config.webhook',    'uses'  => 'NotificationWhatsAppController@webhook']);
        Route::get('config/instancia',  ['as'   => 'config.instancia',  'uses'  => 'NotificationWhatsAppController@instancia']);
        Route::get('config/disconnect', ['as'   => 'config.disconnect', 'uses'  => 'NotificationWhatsAppController@disconnect']);
});
