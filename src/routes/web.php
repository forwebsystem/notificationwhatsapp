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

Route::middleware('auth')
    ->prefix('notificacaowhatsapp')
    ->as('notificacaowhatsapp.')
    ->namespace('ForWebSystem\NotificationWhatsApp\Http\Controllers')
    ->group(function () {

        Route::get('config/webhook',    ['as'   => 'config.webhook',    'uses'  => 'NotificationWhatsAppController@configuracao']);
        Route::get('config/instancia',  ['as'   => 'config.instancia',  'uses'  => 'NotificationWhatsAppController@instancia']);
        Route::get('config/disconnect', ['as'   => 'config.disconnect', 'uses'  => 'NotificationWhatsAppController@disconnect']);
});
