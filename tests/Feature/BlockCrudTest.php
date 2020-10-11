<?php

namespace Tests\Feature;

use App\Models\Block;
use App\Models\Player;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class BlockCrudTest extends TestCase
{
    use RefreshDatabase;

    public function testBlockIndex()
    {
        list($player1, $token) = $this->createPlayerWithSession();

        $player2 = factory(Player::class)->make();
        $player2->save();

        $block = new Block();
        $block->player()->associate($player1);
        $block->blocked_player()->associate($player2);
        $block->save();

        $response = $this->get("/api/blocks?session_id=$token");

        $response->assertStatus(200);
        $response->assertJson([[
            'sender_player_id' => $player1->id,
            'recipient_player_id' => $player2->id,
        ]]);
    }

    public function testBlockCreation()
    {
        list($player1, $token) = $this->createPlayerWithSession();

        $player2 = factory(Player::class)->make();
        $player2->save();

        $response = $this->post("/api/blocks?session_id=$token", ['other_player_id' => $player2->id]);
        $response->assertStatus(201);
        $response->assertJson([
            'sender_player_id' => $player1->id,
            'recipient_player_id' => $player2->id,
        ]);
    }

    public function testInvalidBlockCreationRequests()
    {
        list($player1, $token) = $this->createPlayerWithSession();

        $player2 = factory(Player::class)->make();
        $player2->save();

        $block = new Block();
        $block->player()->associate($player1);
        $block->blocked_player()->associate($player2);
        $block->save();

        // A player should not be able to block themselves
        $response = $this->post("/api/blocks?session_id=$token", ['other_player_id' => $player1->id]);
        $response->assertStatus(422);

        // A player should not be able to block a player they have already blocked
        $response = $this->post("/api/blocks?session_id=$token", ['other_player_id' => $player2->id]);
        $response->assertStatus(422);
    }

    public function testUnblock()
    {
        list($player1, $token) = $this->createPlayerWithSession();

        $player2 = factory(Player::class)->make();
        $player2->save();

        $block = new Block();
        $block->player()->associate($player1);
        $block->blocked_player()->associate($player2);
        $block->save();

        $response = $this->delete("/api/blocks/{$block->id}?session_id=$token");
        $response->assertStatus(204);
        $this->assertDatabaseMissing('blocks', ['id' => $block->id]);
    }

    public function testBlockIsNotShownToBlockedPlayer()
    {
        list($player1, $token) = $this->createPlayerWithSession();

        $player2 = factory(Player::class)->make();
        $player2->save();

        $block = new Block();
        $block->player()->associate($player2);
        $block->blocked_player()->associate($player1);
        $block->save();

        // Note: The block is the other way around. A "blockee" cannot see that he is blocked!
        $response = $this->get("/api/blocks/{$block->id}?session_id=$token");
        $response->assertStatus(401);
    }

    public function testBlockIsNotShownToUnrelatedPlayer()
    {
        list($player1, $token) = $this->createPlayerWithSession();

        $player2 = factory(Player::class)->make();
        $player2->save();

        $player3 = factory(Player::class)->make();
        $player3->save();

        $block = new Block();
        $block->player()->associate($player2);
        $block->blocked_player()->associate($player3);
        $block->save();

        // This block concerns two completely separate players.
        $response = $this->get("/api/blocks/{$block->id}?session_id=$token");
        $response->assertStatus(401);
    }

    // Note: there is an API to "update" a block (i.e. change the target player), but this is
    //  a weird and probably undesired feature and is thus probably best left undocumented.

}
