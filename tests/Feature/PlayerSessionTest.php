<?php

namespace Tests\Feature;

use App\Models\Player;
use App\Models\PlayerSession;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Str;
use Tests\TestCase;

class PlayerSessionTest extends TestCase
{
    use RefreshDatabase;

    /**
     * A basic test example.
     *
     * @return void
     */
    public function testSessionTokensAreHashed()
    {
        $player = factory(Player::class)->make();
        $player->save();

        $session = new PlayerSession();
        $session->player()->associate($player);
        $session->token = 'foobar';

        $session->save();

        $this->assertDatabaseHas('player_sessions', [
            'player_id' => $player->id,
            'token' => PlayerSession::hash('foobar'),
        ]);

        $session->refresh();

        $this->assertTrue(Carbon::now()->isBefore($session->expires_at));
    }

    public function testDifferentTokensHaveDifferentHashes()
    {
        $string1 = Str::random(80);
        $string2 = Str::random(80);

        // Odds of identical strings is astronomically small,
        // but let's guarantee that we have no collision anyways
        while ($string2 === $string1) {
            $string2 = Str::random(80);
        }

        // Session hashing should not use salt, i.e. re-hashing should produce the same results...
        $this->assertEquals(PlayerSession::hash($string1), PlayerSession::hash($string1));
        $this->assertEquals(PlayerSession::hash($string2), PlayerSession::hash($string2));

        // ... but different strings should, of course, produce different hashes.
        $this->assertNotEquals(PlayerSession::hash($string1), PlayerSession::hash($string2));
    }
}
