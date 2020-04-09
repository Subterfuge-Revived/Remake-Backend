<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRoomsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('rooms', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->dateTime('started_at')->nullable();
            $table->dateTime('closed_at')->nullable(); // NOTE: New column
            $table->unsignedBigInteger('creator_player_id');
            $table->unsignedBigInteger('goal_id'); // NOTE: rename from "goal" and now foreign key
            $table->string('description')->nullable(); // NOTE: made nullable
            $table->boolean('is_rated')->default(0); // NOTE: rename from "rated"
            $table->boolean('is_anonymous')->default(0); // NOTE: rename from "anonymity"
            $table->integer('map'); // What does this mean?
            $table->integer('seed');

            $table->foreign('creator_player_id')->references('id')->on('players');
            // NOTE: removed columns: player_count, min_rating

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('rooms', function (Blueprint $table) {
           $table->dropForeign('rooms_creator_player_id_foreign');
        });
        Schema::dropIfExists('rooms');
    }
}
