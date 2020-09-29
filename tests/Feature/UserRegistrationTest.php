<?php

namespace Tests\Feature;

use Tests\TestCase;

class UserRegistrationTest extends TestCase
{
//    use RefreshDatabase;

    /**
     * A basic test example.
     *
     * @return void
     */
    public function testBasicTest()
    {
        $response = $this->post('/api/register', [
            'username' => 'Username',
            'email' => 'test@example.com',
            'password' => 'foobar',
        ]);

        $response->assertStatus(200);
        $response->assertJson(['player' => ['name' => 'Username']]);
    }
}
