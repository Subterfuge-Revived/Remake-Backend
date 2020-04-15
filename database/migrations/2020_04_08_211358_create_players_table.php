<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePlayersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('players', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->string('name')->unique(); // NOTE: Renamed from player_name
            $table->string('password');
            $table->string('email')->unique()->nullable();
            $table->integer('rating')->default(1200); // TODO: Is this a good default value?
            $table->unsignedInteger('wins')->default(0);
            $table->unsignedInteger('resignations')->default(0); // NOTE: renamed from "resigned"
            $table->dateTime('last_online_at');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('players');
    }
}
