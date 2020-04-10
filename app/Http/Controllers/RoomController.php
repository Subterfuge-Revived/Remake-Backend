<?php

namespace App\Http\Controllers;

use App\Models\PlayerRoom;
use App\Models\Room;
use Carbon\Carbon;
use Illuminate\Contracts\Routing\ResponseFactory;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Validation\ValidationException;

class RoomController extends Controller
{

    /**
     * RoomController constructor.
     */
    public function __construct()
    {
        parent::__construct();

        // Enforce API authentication for each request.
        $this->middleware('auth.api');
    }

    /**
     * @param Request $request
     * @return Validator
     */
    public function validator(Request $request)
    {
        return \Validator::make($request->all(), [
            'max_players' => 'required|int|between:2,10',
            'goal' => 'required|int', // FIXME: We should not require the client to send goal IDs but rather identifiers
            'description' => 'required|string',
            'map' => 'required|int|between:0,3',
            'min_rating' => 'required|int|min:0', // TODO: Make this optional for open games?
            'rated' => 'required|boolean',
            'anonymity' => 'required|boolean',
        ]);
    }

    /**
     * Show a room.
     *
     * @param int $roomId
     * @return ResponseFactory|Response|object
     */
    public function show(int $roomId)
    {
        // TODO: Determine whether a user must be authenticated to use this API.

        if (!$room = Room::whereId($roomId)->first()) {
            return response('')->setStatusCode(404);
        }

        return response($room);
    }

    /**
     * Create a new room.
     *
     * @param Request $request
     * @return JsonResponse|Response
     * @throws ValidationException
     */
    public function store(Request $request)
    {
        $this->validator($request)->validate();

        $minRating = $request->get('rated')
            ? $request->get('min_rating')
            : 0;

        if ($this->session->player->rating < $minRating) {
            throw ValidationException::withMessages(['Invalid minimum rating']);
        }

        // Create the room and associate it with its creator
        /** @var Room $room */
        $room = $this->session->player->created_rooms()->save(new Room([
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
        $playerRoom->player()->associate($this->session->player);
        $playerRoom->room()->associate($room);

        return response([
            'success' => true,
            'created_room' => [
                'room_id' => $room->id,
                'creator' => $this->session->player->id,
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

    /**
     * Update a room.
     *
     * @param $roomId
     * @param Request $request
     * @return ResponseFactory|Response|object
     */
    public function update($roomId, Request $request)
    {
        $this->validator($request)->validate();
        if (!$room = Room::whereId($roomId)->first()) {
            return response('')->setStatusCode(404);
        }

        $minRating = $request->get('rated')
            ? $request->get('min_rating')
            : 0;

        $room->update([
            'goal_id' => $request->get('goal'),
            'description' => $request->get('description'),
            'is_rated' => $request->get('rated'),
            'is_anonymous' => $request->get('anonymity'),
            'max_players' => $request->get('max_players'),
            'min_rating' => $minRating,
            'map' => $request->get('map'),
            'seed' => Carbon::now()->unix(),
        ]);

        return response($room);
    }

    /**
     * Destroy a room.
     *
     * @param $roomId
     * @return ResponseFactory|Response|object
     * @throws \Exception
     */
    public function destroy($roomId)
    {
        if (!$room = Room::whereId($roomId)->first()) {
            return response('')->setStatusCode(404);
        }

        // Only the creator of a room can destroy it. Return unauthorized otherwise.
        if ($room->creator_player != $this->session->player) {
            return response('')->setStatusCode(401);
        }

        $room->delete();

        return response('')->setStatusCode(204);
    }

    /**
     * Join a room.
     *
     * @param Request $request
     * @return ResponseFactory|Response
     * @throws ValidationException
     */
    public function join(Request $request)
    {
        if (!$room = Room::whereId($request->input('room_id'))->first()) {
            return response('', 404);
        }

        if ($this->session->player->rating < $room->min_rating) {
            throw ValidationException::withMessages(['Insufficient rating']);
        }

        if (count($room->players) == $room->max_players) {
            throw ValidationException::withMessages(['Room is already full']);
        }

        if ($room->players->contains($this->session->player)) {
            throw ValidationException::withMessages(['Player is already in the room']);
        }

        $room->players()->save($this->session->player);

        return response([
            'success' => true,
            'room' => $room->id,
        ]);
    }

    /**
     * Leave a room.
     *
     * @param Request $request
     * @return ResponseFactory|Response
     * @throws ValidationException|\Exception
     */
    public function leave(Request $request)
    {
        if (!$room = Room::whereId($request->input('room_id'))->first()) {
            return response('', 404);
        }

        if (!$room->players->contains($this->session->player)) {
            throw ValidationException::withMessages(['Player is not in the room']);
        }

        // TODO: Should we allow a player to leave a room even after the game has started?
        // As long as we retain messages and events, it should not cause any problems.
        // This would be an effective way of resigning the game since a re-join will be impossible.
        // However, it also refuses the resigned player access to spectate the game.
        if ($room->hasStarted()) {
            throw ValidationException::withMessages(['Cannot leave a room after it has started']);
        }

        $room->players()->where('id', $this->session->player->id)->detach();

        $room->refresh();
        if ($room->players->isEmpty()) {
            $room->delete();
        }

        return response([
            'success' => true,
            'room' => $room->id, // FIXME: The room may have been deleted! Why are we returning its id?
        ]);
    }

    /**
     * Start a room early.
     *
     * @param Request $request
     * @return ResponseFactory|Response
     * @throws ValidationException
     */
    public function startEarly(Request $request)
    {
        if (!$room = Room::whereId($request->input('room_id'))->first()) {
            return response('', 404);
        }

        if ($room->creator_player != $this->session->player) {
            return response('', 401);
        }

        if ($room->hasStarted()) {
            throw ValidationException::withMessages(['Room has already started']);
        }

        if (count($room->players) < 2) {
            throw ValidationException::withMessages(['Room needs at least 2 players to start']);
        }

        $room->started_at = Carbon::now();

        return response([
            'success' => true,
            'room' => $room->id,
        ]);
    }
}
