<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMessagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('messages', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->unsignedBigInteger('sender_player_id');
            $table->unsignedBigInteger('message_group_id');
            $table->text('message');

            $table->foreign('sender_player_id')->references('id')->on('players');
            $table->foreign('message_group_id')->references('id')->on('message_groups');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('messages', function (Blueprint $table) {
           $table->dropForeign('messages_message_group_id_foreign');
           $table->dropForeign('messages_sender_player_id_foreign');
        });

        Schema::dropIfExists('messages');
    }
}
