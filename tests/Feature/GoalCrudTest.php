<?php

namespace Tests\Feature;

use App\Models\Block;
use App\Models\Goal;
use App\Models\Player;
use App\Models\PlayerSession;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class GoalCrudTest extends TestCase
{
    use RefreshDatabase;

    public function testGoalIndex()
    {
        /** @var Goal $goal */
        $goal = factory(Goal::class)->make();
        $goal->save();
        $player = factory(Player::class)->make();
        $player->save();

        $session = new PlayerSession();
        $session->player()->associate($player);
        $session->token = 'foobar';
        $session->save();
        $session->refresh();

        $response = $this->get("/api/goals?session_id=foobar");

        $response->assertStatus(200);
        $response->assertJson([[
            'identifier' => $goal->identifier,
            'description' => $goal->description,
        ]]);
    }
}
