<?php

namespace Tests\Feature;

use App\Models\Block;
use App\Models\Player;
use App\Models\PlayerSession;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class BlockCrudTest extends TestCase
{
    use RefreshDatabase;

    public function testBlockIndex()
    {
        $player1 = factory(Player::class)->make();
        $player2 = factory(Player::class)->make();

        $player1->save();
        $player2->save();

        $player1->refresh();
        $player2->refresh();

        $session = new PlayerSession();
        $session->player()->associate($player1);
        $session->token = 'foobar';
        $session->save();
        $session->refresh();

        $block = new Block();
        $block->player()->associate($player1);
        $block->blocked_player()->associate($player2);
        $block->save();

        $response = $this->get("/api/blocks?session_id=foobar");

        $response->assertStatus(200);
        $response->assertJson([[
            'sender_player_id' => $player1->id,
            'recipient_player_id' => $player2->id,
        ]]);
    }

    public function testBlockCreation()
    {
        $player1 = factory(Player::class)->make();
        $player1->save();
        $player1->refresh();

        $player2 = factory(Player::class)->make();
        $player2->save();
        $player2->refresh();

        $session = new PlayerSession();
        $session->player()->associate($player1);
        $session->token = 'foobar';
        $session->save();

        $response = $this->post('/api/blocks?session_id=foobar', ['other_player_id' => $player2->id]);
        $response->assertStatus(201);
        $response->assertJson([
            'sender_player_id' => $player1->id,
            'recipient_player_id' => $player2->id,
        ]);
    }

    public function testInvalidBlockCreationRequests()
    {
        $player1 = factory(Player::class)->make();
        $player1->save();
        $player1->refresh();

        $player2 = factory(Player::class)->make();
        $player2->save();
        $player2->refresh();

        $block = new Block();
        $block->player()->associate($player1);
        $block->blocked_player()->associate($player2);
        $block->save();

        $session = new PlayerSession();
        $session->player()->associate($player1);
        $session->token = 'foobar';
        $session->save();

        // A player should not be able to block themselves
        $response = $this->post('/api/blocks?session_id=foobar', ['other_player_id' => $player1->id]);
        $response->assertStatus(422);

        // A player should not be able to block a player they have already blocked
        $response = $this->post('/api/blocks?session_id=foobar', ['other_player_id' => $player2->id]);
        $response->assertStatus(422);
    }

    public function testUnblock()
    {
        $player1 = factory(Player::class)->make();
        $player1->save();

        $player2 = factory(Player::class)->make();
        $player2->save();

        $block = new Block();
        $block->player()->associate($player1);
        $block->blocked_player()->associate($player2);
        $block->save();
        $block->refresh();

        $session = new PlayerSession();
        $session->player()->associate($player1);
        $session->token = 'foobar';
        $session->save();

        $response = $this->delete("/api/blocks/{$block->id}?session_id=foobar");
        $response->assertStatus(204);
        $this->assertDatabaseMissing('blocks', ['id' => $block->id]);
    }

    public function testBlockIsNotShownToBlockedPlayer()
    {
        $player1 = factory(Player::class)->make();
        $player1->save();

        $player2 = factory(Player::class)->make();
        $player2->save();

        $block = new Block();
        $block->player()->associate($player2);
        $block->blocked_player()->associate($player1);
        $block->save();

        $session = new PlayerSession();
        $session->player()->associate($player1);
        $session->token = 'foobar';
        $session->save();

        // Note: The block is the other way around. A "blockee" cannot see that he is blocked!
        $response = $this->get("/api/blocks/{$block->id}?session_id=foobar");
        $response->assertStatus(401);
    }

    public function testBlockIsNotShownToUnrelatedPlayer()
    {
        $player1 = factory(Player::class)->make();
        $player1->save();
        $player1->refresh();

        $player2 = factory(Player::class)->make();
        $player2->save();
        $player2->refresh();

        $player3 = factory(Player::class)->make();
        $player3->save();
        $player3->refresh();

        $block = new Block();
        $block->player()->associate($player2);
        $block->blocked_player()->associate($player3);
        $block->save();

        $session = new PlayerSession();
        $session->player()->associate($player1);
        $session->token = 'foobar';
        $session->save();

        // This block concerns two completely separate players.
        $response = $this->get("/api/blocks/{$block->id}?session_id=foobar");
        $response->assertStatus(401);
    }

    // Note: there is an API to "update" a block (i.e. change the target player), but this is
    //  a weird and probably undesired feature and is thus probably best left undocumented.

}
