<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('api')
    ->prefix('api/notificacaowhatsapp')
    ->as('notificacaowhatsapp.')
    ->namespace('ForWebSystem\NotificationWhatsApp\Http\Controllers')
    ->group(function () {

        Route::post('webhook/delivery',         ['as' => 'webhook.delivery',        'uses' => 'WebhookController@delivery']);
        Route::post('webhook/disconnected',     ['as' => 'webhook.disconnected',    'uses' => 'WebhookController@disconnected']);
        Route::post('webhook/received',         ['as' => 'webhook.received',        'uses' => 'WebhookController@received']);
        Route::post('webhook/message-status',   ['as' => 'webhook.message-status',  'uses' => 'WebhookController@messageStatus']);
});
