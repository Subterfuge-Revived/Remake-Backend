<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePlayerRoomsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('player_rooms', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->unsignedBigInteger('room_id');
            $table->unsignedBigInteger('player_id');

            $table->foreign('room_id')->references('id')->on('rooms');
            $table->foreign('player_id')->references('id')->on('players');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('player_rooms', function (Blueprint $table) {
            $table->dropForeign('player_rooms_room_id_foreign');
            $table->dropForeign('player_rooms_player_id_foreign');
        });

        Schema::dropIfExists('player_rooms');
    }
}
