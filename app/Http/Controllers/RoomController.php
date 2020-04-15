<?php

namespace App\Http\Controllers;

use App\Http\Responses\DeletedResponse;
use App\Http\Responses\NotFoundResponse;
use App\Http\Responses\UnauthorizedResponse;
use App\Models\Goal;
use App\Models\Player;
use App\Models\PlayerRoom;
use App\Models\Room;
use Carbon\Carbon;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;

class RoomController extends Controller
{

    /**
     * @param Request $request
     * @return Validator
     */
    public function validator(Request $request)
    {
        return \Validator::make($request->all(), [
            'max_players' => 'required|int|between:2,10',
            'goal' => 'required|string|' . Rule::in(Goal::pluck('identifier')),
            'description' => 'required|string',
            'map' => 'required|int|between:0,3',
            'min_rating' => 'required|int|min:0', // TODO: Make this optional for open games?
            'rated' => 'required|boolean',
            'anonymity' => 'required|boolean',
        ]);
    }

    /**
     * Get a collection of relevant rooms.
     *
     * @param Request $request
     * @return Response
     */
    public function index(Request $request)
    {
        $rooms = Room::query();

        if ($request->input('return_closed_rooms') !== 'true') {
            // Unless specifically requested to return closed rooms, filter them
            $rooms = $rooms->whereNull('closed_at');
        }

        if ($request->input('filter_by_player') === 'true' || $request->input('room_status') === 'ongoing') {

            // Get only the rooms that have the current player registered to it
            $rooms->whereHas('players', function (Builder $query) {
                $query->where('player_id', '=', $this->session->player_id);
            });
        }

        $rooms = $rooms->get()->map(function (Room $room) {
            return [
                'room_id' => $room->id,
                'status' => $room->hasStarted() ? 'ongoing' : 'open',
                'creator_id' => $room->creator_player_id,
                'rated' => $room->is_rated,
                'min_rating' => $room->min_rating,
                'description' => $room->description,
                'goal' => $room->goal_id,
                'anonymity' => $room->is_anonymous,
                'map' => $room->map,
                'seed' => $room->seed,
                'started_at' => $room->started_at->unix(),
                'max_players' => $room->max_players,
                'players' => $room->is_anonymous
                    ? $room->players->map(function (Player $player) {
                        return ['name' => 'Anonymous', 'id' => $player->id];
                    })
                    : $room->players->map(function (Player $player) {
                        return ['name' => $player->name, 'id' => $player->id];
                    }),
                'message_groups' => $room->message_groups()->whereHas('message_group_members', function (Builder $query) {
                    $query->where('player_id', '=', $this->session->player_id);
                })->get(),
            ];
        });

        return new Response($rooms);
    }


    /**
     * Create a new room.
     *
     * @param Request $request
     * @return Response
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
        $room = new Room([
            'description' => $request->get('description'),
            'is_rated' => $request->get('rated'),
            'is_anonymous' => $request->get('anonymity'),
            'max_players' => $request->get('max_players'),
            'min_rating' => $minRating,
            'map' => $request->get('map'),
            'seed' => Carbon::now()->unix(),
        ]);
        $room->goal()->associate(Goal::whereIdentifier($request->get('goal'))->first());
        $this->session->player->created_rooms()->save($room);

        // Add the creator to the room
        $playerRoom = new PlayerRoom();
        $playerRoom->player()->associate($this->session->player);
        $playerRoom->room()->associate($room);

        // TODO: In style with the other APIs we should return an empty 201 response
        // with a Location header to the room.
        return new Response([
            'created_room' => [
                'room_id' => $room->id,
                'creator' => $this->session->player->id,
                'description' => $room->description,
                'rated' => (bool)$room->is_rated,
                'max_players' => $room->max_players,
                'player_count' => 1, // TODO: This is always only the creator, do we really need to return this?
                'min_rating' => $room->min_rating,
                'goal' => $room->goal->identifier,
                'anonymity' => (bool)$room->is_anonymous,
                'map' => $room->map,
                'seed' => $room->seed,
            ],
        ]);
    }

    /**
     * Show a room.
     *
     * @param int $roomId
     * @return Response
     */
    public function show(int $roomId)
    {
        if (!$room = Room::whereId($roomId)->first()) {
            return new NotFoundResponse();
        }

        return new Response($room);
    }

    /**
     * Update a room.
     *
     * @param $roomId
     * @param Request $request
     * @return Response
     * @throws ValidationException
     */
    public function update($roomId, Request $request)
    {
        $this->validator($request)->validate();
        if (!$room = Room::whereId($roomId)->first()) {
            return new NotFoundResponse();
        }

        if ($room->hasStarted()) {
            throw ValidationException::withMessages(['Room has already started']);
        }

        $minRating = $request->get('rated')
            ? $request->get('min_rating')
            : 0;

        $room->goal()->associate(Goal::whereIdentifier($request->get('goal'))->first());
        $room->update([
            'description' => $request->get('description'),
            'is_rated' => $request->get('rated'),
            'is_anonymous' => $request->get('anonymity'),
            'max_players' => $request->get('max_players'),
            'min_rating' => $minRating,
            'map' => $request->get('map'),
            'seed' => Carbon::now()->unix(),
        ]);

        return new Response($room);
    }

    /**
     * Destroy a room.
     *
     * @param $roomId
     * @return Response
     * @throws \Exception
     */
    public function destroy($roomId)
    {
        if (!$room = Room::whereId($roomId)->first()) {
            return new NotFoundResponse();
        }

        // Only the creator of a room can destroy it. Return unauthorized otherwise.
        if ($room->creator_player != $this->session->player) {
            return new UnauthorizedResponse();
        }

        $room->delete();

        return new DeletedResponse($room);
    }

    /**
     * Join a room.
     *
     * @param Request $request
     * @return Response
     * @throws ValidationException
     */
    public function join(Request $request)
    {
        if (!$room = Room::whereId($request->input('room_id'))->first()) {
            return new NotFoundResponse();
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

        if ($room->hasStarted()) {
            throw ValidationException::withMessages(['Room has already started']);
        }

        $room->players()->save($this->session->player);

        $room->refresh();

        // If the room is full, start it
        if ($room->players->count() === $room->max_players) {
            $room->started_at = Carbon::now();
            $room->save();
        }

        // Since we have indirectly updated the room resource,
        // it makes sense to return it.
        return new Response($room->with('players'));
    }

    /**
     * Leave a room.
     *
     * @param Request $request
     * @return Response
     * @throws ValidationException|\Exception
     */
    public function leave(Request $request)
    {
        if (!$room = Room::whereId($request->input('room_id'))->first()) {
            return new NotFoundResponse();
        }

        if (!$room->players->contains($this->session->player)) {
            throw ValidationException::withMessages(['Player is not in the room']);
        }

        $room->players()->where('id', $this->session->player->id)->detach();

        // TODO: Ideally we should have a table of resignations (or better: game outcomes)
        // over which we could sum. This way we have no way to be sure of data integrity over time.
        $this->session->player->resignations += 1;
        $this->session->player->save();

        $room->refresh();

        // Since we have indirectly updated the room resource,
        // it makes sense to return it.
        return new Response($room->with('players'));
    }

    /**
     * Start a room early.
     *
     * @param Request $request
     * @return Response
     * @throws ValidationException
     */
    public function startEarly(Request $request)
    {
        if (!$room = Room::whereId($request->input('room_id'))->first()) {
            return new NotFoundResponse();
        }

        if ($room->creator_player != $this->session->player) {
            return new UnauthorizedResponse();
        }

        if ($room->hasStarted()) {
            throw ValidationException::withMessages(['Room has already started']);
        }

        if (count($room->players) < 2) {
            throw ValidationException::withMessages(['Room needs at least 2 players to start']);
        }

        $room->started_at = Carbon::now();
        $room->save();

        return new Response($room);
    }
}
