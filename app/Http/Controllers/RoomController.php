<?php

namespace App\Http\Controllers;

use App\Models\PlayerRoom;
use App\Models\PlayerSession;
use App\Models\Room;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Validation\ValidationException;

class RoomController extends Controller
{
    /**
     * Create a new room.
     *
     * @param Request $request
     * @return JsonResponse|Response
     * @throws ValidationException
     */
    public function create(Request $request)
    {
        // FIXME: We cannot put this in a middleware (yet) because the same API endpoint
        // (the root endpoint) is used both for authenticated and non-authenticated purposes.
        $session = PlayerSession::whereToken(hash('sha256', $request->get('token')))->first();
        if (!$session || !$session->isValid()) {
            return response()->setStatusCode(401);
        }

        $validator = \Validator::make($request->all(), [
            'max_players' => 'required|int|between:2,10',
            'goal' => 'required|int', // FIXME: We should not require the client to send goal IDs but rather identifiers
            'description' => 'required|string',
            'map' => 'required|int|between:0,3',
            'min_rating' => 'required|int|min:0', // TODO: Make this optional for open games?
            'rated' => 'required|boolean',
            'anonymity' => 'required|boolean',
        ]);

        if ($validator->fails()) {
            throw new ValidationException($validator);
        }

        $minRating = $request->get('rated')
            ? $request->get('min_rating')
            : 0;

        if ($session->player->rating < $minRating) {
            throw ValidationException::withMessages(['Invalid minimum rating']);
        }

        // Create the room and associate it with its creator
        /** @var Room $room */
        $room = $session->player->created_rooms()->save(new Room([
            'goal_id' => $request->get('goal'),
            'description' => $request->get('description'),
            'is_rated' => $request->get('rated'),
            'is_anonymous' => $request->get('anonymity'),
            'max_players' => $request->get('max_players'),
            'min_rating' => $minRating,
            'map' => $request->get('map'),
            'seed' => Carbon::now()->unix(),
        ]));

        // Add the creator to the room
        $playerRoom = new PlayerRoom();
        $playerRoom->player()->associate($session->player);
        $playerRoom->room()->associate($room);

        return response()->json([
            'success' => true,
            'created_room' => [
                'room_id' => $room->id,
                'creator' => $session->player->id,
                'description' => $room->description,
                'rated' => (bool)$room->is_rated,
                'max_players' => $room->max_players,
                'player_count' => 1, // TODO: This is always only the creator, do we really need to return this?
                'min_rating' => $room->min_rating,
                'goal' => $room->goal_id,
                'anonymity' => (bool)$room->is_anonymous,
                'map' => $room->map,
                'seed' => $room->seed,
            ],
        ]);
    }
}
