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

Route::middleware('api', 'requestLog:whatsapp-api', 'requestValidation:whatsapp-api')
    ->prefix('api/notificacaowhatsapp')
    ->as('notificacaowhatsapp.')
    ->namespace('ForWebSystem\NotificationWhatsApp\Http\Controllers')
    ->group(function () {

        // Z-Api
        Route::post('webhook/{token}/delivery',         ['as' => 'webhook.delivery',        'uses' => 'WebhookController@delivery']);
        Route::post('webhook/{token}/disconnected',     ['as' => 'webhook.disconnected',    'uses' => 'WebhookController@disconnected']);
        Route::post('webhook/{token}/received',         ['as' => 'webhook.received',        'uses' => 'WebhookController@received']);
        Route::post('webhook/{token}/message-status',   ['as' => 'webhook.message-status',  'uses' => 'WebhookController@messageStatus']);

        // Fivezap
        Route::post('webhook/fivezap', 'WebhookController@fivezapMessage');
});