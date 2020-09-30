<?php

namespace Tests\Feature;

use App\Models\Player;
use App\Models\PlayerSession;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UserLoginTest extends TestCase
{
    use RefreshDatabase;

    /**
     * A basic test example.
     *
     * @return void
     */
    public function testSuccessfulLogin()
    {
        /** @var Player $player */
        $player = factory(Player::class)->make();
        $player->save();
        $player->refresh();

        $response = $this->post('/api/login', [
            'username' => $player->name,
            'password' => 'password',
        ]);

        $response->assertStatus(200);
        $this->assertPassesValidation($response->json(), [
            'user' => 'required',
            'user.name' => 'required|string|in:' . $player->name,
            'user.id' => 'required|int',
            'token' => 'required|string',
        ]);
        $this->assertEquals(PlayerSession::hash($response->json('token')), PlayerSession::wherePlayerId($player->id)->first()->token);
    }

    public function testLoginFailsWithInvalidCredentials()
    {
        /** @var Player $player */
        $player = factory(Player::class)->make();
        $player->save();
        $player->refresh();

        $response = $this->post('/api/login', [
            'username' => $player->name,
            'password' => 'wrong_password',
        ]);

        $response->assertStatus(422);
        $this->assertDatabaseMissing('player_sessions', ['player_id' => $player->id]);

        $response = $this->post('/api/login', [
            'username' => $player->name . '_wrong',
            'password' => 'password',
        ]);

        $response->assertStatus(422);
        $this->assertDatabaseMissing('player_sessions', ['player_id' => $player->id]);
    }

}
