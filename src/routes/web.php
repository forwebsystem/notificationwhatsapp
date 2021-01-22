<?php

use App\Notifications\BemVindoNotification;
use App\User;
use ForWebSystem\NotificationWhatsApp\Services\NotificacaoZApiService;
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
    $user->notify(new BemVindoNotification());

    return [
        'mensagem enviada',
        $user
    ];
});
