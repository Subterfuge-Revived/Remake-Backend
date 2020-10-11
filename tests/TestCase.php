<?php

namespace Tests;

use App\Models\Goal;
use App\Models\Player;
use App\Models\PlayerSession;
use App\Models\Room;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Illuminate\Support\Facades\Validator;
use PHPUnit\Framework\ExpectationFailedException;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication, RefreshDatabase;

    public function assertPassesValidation($data, array $validation) {
        $validator = Validator::make($data, $validation);

        if ($validator->fails()) {
            throw new ExpectationFailedException($validator->errors()->toJson());
        }
    }

    /**
     * Creates a player and conveniently starts a session with the given parameter as its API token.
     * If no token is given explicitly, generates a random one.
     *
     * Returns an array [Player, string] where the string is the (generated) API token.
     *
     * @param string $token
     * @return Player
     */
    public function createPlayerWithSession(?string $token = null): array
    {
        $player = factory(Player::class)->make();
        $player->save();

        if (is_null($token)) {
            $token = \Str::random(80);
        }
        $session = new PlayerSession();
        $session->player()->associate($player);
        $session->token = $token;
        $session->save();

        return [$player, $token];
    }

    /**
     * Creates a player and conveniently also associates to it the given goal.
     * If no goal was given, generates one on the fly and associate that.
     *
     * Returns an array [Room, Goal].
     *
     * @param Player $creator
     * @param Goal|null $goal
     * @return array
     */
    public function createRoomWithGoal(Player $creator, ?Goal $goal = null): array
    {
        /** @var Room $room */
        $room = factory(Room::class)->make();
        $room->player()->associate($creator);

        if (is_null($goal)) {
            $goal = factory(Goal::class)->make();
            $goal->save();
        }

        $room->goal()->associate($goal);
        $room->save();

        return [$room, $goal];
    }
}
