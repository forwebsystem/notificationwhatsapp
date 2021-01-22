<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateNotificationWhatsAppMensagensTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('notificationwhatsapp_mensagens', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->morphs('user');
            $table->string('service');
            $table->string('url');
            $table->string('type_mensagem');
            $table->string('phone_destination');
            $table->string('context');
            $table->text('result');
            $table->string('status');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('notificationwhatsapp_mensagens');
    }
}
