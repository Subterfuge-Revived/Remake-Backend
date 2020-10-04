<?php

namespace Tests\Unit;

use App\Models\Goal;
use App\Models\Player;
use App\Models\Room;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RoomTest extends TestCase
{
    use RefreshDatabase;

    public function testARoomIsOngoingIfItHasStartedButNotEnded()
    {
        $goal = factory(Goal::class)->make();
        $goal->save();

        $creatorPlayer = factory(Player::class)->make();
        $creatorPlayer->save();

        /** @var Room $room */
        $room = factory(Room::class)->make();
        $room->goal()->associate($goal);
        $room->player()->associate($creatorPlayer);
        $room->save();

        // Hasn't started yet
        $this->assertFalse($room->isOngoing());

        $room->started_at = Carbon::now();

        // Room has started
        $this->assertTrue($room->isOngoing());

        $room->closed_at = Carbon::now();

        // Room has finished
        $this->assertFalse($room->isOngoing());
    }
}
