<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBlocksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('blocks', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->unsignedBigInteger('sender_player_id');
            $table->unsignedBigInteger('recipient_player_id');

            $table->foreign('sender_player_id')->references('id')->on('players');
            $table->foreign('recipient_player_id')->references('id')->on('players');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('blocks', function (Blueprint $table) {
            $table->dropForeign('blocks_sender_player_id_foreign');
            $table->dropForeign('blocks_recipient_player_id_foreign');
        });

        Schema::dropIfExists('blocks');
    }
}
