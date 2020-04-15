<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEventsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('events', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->dateTime('occurs_at');
            $table->unsignedBigInteger('room_id');
            $table->unsignedBigInteger('player_id');
            $table->json('event_json');

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
        Schema::table('events', function (Blueprint $table) {
           $table->dropForeign('events_room_id_foreign');
           $table->dropForeign('events_player_id_foreign');
        });

        Schema::dropIfExists('events');
    }
}
