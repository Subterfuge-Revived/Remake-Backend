<?php

namespace Tests\Feature;

use App\Models\Player;
use App\Models\PlayerSession;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class UserRegistrationTest extends TestCase
{
    use RefreshDatabase;

    /**
     * A basic test example.
     *
     * @return void
     */
    public function testSuccessfulRegistration()
    {
        $response = $this->post('/api/register', [
            'username' => 'Username',
            'email' => 'test@example.com',
            'password' => 'foobar',
        ]);

        $response->assertStatus(200);
        $this->assertPassesValidation($response->json(), [
            'player' => 'required',
            'player.name' => 'required|string|in:Username',
            'player.id' => 'required|int',
            'token' => 'required|string',
        ]);
        $this->assertDatabaseHas('players', ['name' => 'Username', 'email' => 'test@example.com']);
        $this->assertTrue(Hash::check('foobar', Player::whereName('Username')->first()->password));
    }

    public function testRegistrationFailsWithDuplicatedData()
    {
        Player::create(['name' => 'Somebody', 'email' => 'test@example.com', 'password' => 'foobar', 'last_online_at' => new Carbon()]);
        $response = $this->post('/api/register', [
            'username' => 'Somebody',
            'email' => 'second_test@example.com',
            'password' => 'barbaz',
        ]);

        // Username already taken
        $response->assertStatus(422);

        $response = $this->post('/api/register', [
            'username' => 'Somebody else',
            'email' => 'test@example.com',
            'password' => 'barbaz',
        ]);

        // E-mail address already taken
        $response->assertStatus(422);
    }

    public function testRegistrationFailsWithProfaneUsername()
    {
        foreach (['fuck', 'shit', 'damn', '{xXx b!tch69 xXx}'] as $name) {
            $response = $this->post('/api/register', [
                'username' => $name,
                'email' => 'test@example.com',
                'password' => 'barbaz',
            ]);

            $response->assertStatus(422);
        }
    }

    public function testRegistrationCreatesASession()
    {
        $response = $this->post('/api/register', [
            'username' => 'Username',
            'email' => 'test@example.com',
            'password' => 'foobar',
        ]);

        $token = $response->json('token');

        $this->assertDatabaseHas('player_sessions', [
            'token' => PlayerSession::hash($token),
            'player_id' => Player::whereName('Username')->first()->id
        ]);
    }

    public function testRegistrationSucceedsWithDuplicatePassword()
    {
        Player::create(['name' => 'Somebody', 'email' => 'test@example.com', 'password' => 'foobar', 'last_online_at' => new Carbon()]);

        $response = $this->post('/api/register', [
            'username' => 'Somebody else',
            'email' => 'second_test@example.com',
            'password' => 'foobar',
        ]);

        // Using the same password as another player is, of course, allowed
        $response->assertStatus(200);
        $this->assertPassesValidation($response->json(), [
            'player' => 'required',
            'player.name' => 'required|string|in:Somebody else',
            'player.id' => 'required|int',
            'token' => 'required|string',
        ]);
        $this->assertDatabaseHas('players', ['name' => 'Somebody else', 'email' => 'second_test@example.com']);
        $this->assertTrue(Hash::check('foobar', Player::whereName('Somebody else')->first()->password));
    }
}
