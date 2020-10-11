<?php

namespace Tests\Feature;

use App\Models\Room;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RoomCrudTest extends TestCase
{
    use RefreshDatabase;

    public function testRoomIndex()
    {
        list($player, $token) = $this->createPlayerWithSession();
        list($room, $goal) = $this->createRoomWithGoal($player);

        $response = $this->get("/api/rooms?session_id=$token");
        $response->assertStatus(200);
        $this->assertPassesValidation($response->json(), [
            '*.room_id' => 'required|int|in:' . $room->id,
            '*.status' => 'required|string|in:open',
            '*.creator_id' => 'required|int|in:' . $player->id,
            '*.rated' => 'required|int|in:0,1', // TODO: I think this should be a boolean
            '*.min_rating' => 'required|int',
            '*.description' => 'required|string',
            '*.goal' => 'required|int|in:' . $goal->id, // TODO: I think this should be explicitly called "goal_id"
            '*.anonymity' => 'required|int|in:0,1',   // TODO: I think this should be a boolean (called "is_anonymous")
            '*.map' => 'required|int',
            '*.seed' => 'required|int',
            '*.started_at' => 'nullable',
            '*.max_players' => 'required|int|between:' . Room::MINIMUM_NUM_PLAYERS . ',' . Room::MAXIMUM_NUM_PLAYERS,
            '*.players' => 'nullable|array',  // TODO: Validation on the entries in this array
            '*.message_groups' => 'nullable|array', // TODO: Validation on the entries in this array

        ]);
    }
}
