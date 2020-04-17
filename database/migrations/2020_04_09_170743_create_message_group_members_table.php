<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMessageGroupMembersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('message_group_members', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->unsignedBigInteger('message_group_id');
            $table->unsignedBigInteger('player_id');

            $table->foreign('message_group_id')->references('id')->on('message_groups')->cascadeOnDelete();
            $table->foreign('player_id')->references('id')->on('players')->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('message_group_members', function (Blueprint $table) {
            $table->dropForeign('message_group_members_message_group_id_foreign');
            $table->dropForeign('message_group_members_player_id_foreign');
        });

        Schema::dropIfExists('message_group_members');
    }
}
