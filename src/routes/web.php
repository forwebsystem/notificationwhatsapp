<?php

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

Route::middleware(['web', 'auth'])
    ->prefix('notificacaowhatsapp')
    ->as('notificacaowhatsapp.')
    ->namespace('ForWebSystem\NotificationWhatsApp\Http\Controllers')
    ->group(function () {

        Route::get('config/{user}/webhook',        ['as'   => 'config.user.webhook',        'uses'  => 'NotificationWhatsAppController@configuracao']);
        Route::get('config/{user}/instancia',      ['as'   => 'config.user.instancia',      'uses'  => 'NotificationWhatsAppController@instancia']);
        Route::get('config/{user}/disconnect',     ['as'   => 'config.user.disconnect',     'uses'  => 'NotificationWhatsAppController@disconnect']);
        Route::get('config/{user}/message-status', ['as'   => 'config.user.message-status', 'uses'  => 'NotificationWhatsAppController@messageStatus']);
});
