<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePlayerSessionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('player_sessions', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->unsignedBigInteger('player_id');
            $table->string('token')->unique();

            $table->foreign('player_id')
                ->references('id')
                ->on('players')
                ->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('player_sessions', function (Blueprint $table) {
            $table->dropForeign('player_sessions_player_id_foreign');
        });

        Schema::dropIfExists('player_sessions');
    }
}
