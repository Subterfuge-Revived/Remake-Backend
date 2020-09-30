<?php

namespace Tests\Feature;

use App\Models\Player;
use Carbon\Carbon;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class UserRegistrationTest extends TestCase
{
//    use RefreshDatabase;

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
        $response->assertJson(['player' => ['name' => 'Username', 'id' => 1]]);
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

        $response = $this->post('/api/register', [
            'username' => 'Somebody else',
            'email' => 'second_test@example.com',
            'password' => 'foobar',
        ]);

        // Using the same password as another player is, of course, allowed
        $response->assertStatus(200);
        $response->assertJson(['player' => ['name' => 'Somebody else', 'id' => 2]]);
        $this->assertDatabaseHas('players', ['name' => 'Somebody else', 'email' => 'second_test@example.com']);
        $this->assertTrue(Hash::check('foobar', Player::whereName('Somebody else')->first()->password));
    }
}
