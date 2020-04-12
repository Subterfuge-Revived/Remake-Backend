<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\Room;
use Illuminate\Contracts\Routing\ResponseFactory;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Validation\ValidationException;

class EventController extends Controller
{
    public function index(Request $request)
    {
        \Validator::make($request->all(), [
            'room_id' => 'required|int',
            'filter' => 'required|string', // Possible values: 'time', 'tick' but maybe others?
            'filter_arg' => 'required|string',
        ])->validate();
    }

    /**
     * @param Request $request
     * @return ResponseFactory|Response
     * @throws ValidationException
     */
    public function store(Request $request)
    {
        \Validator::make($request->all(), [
            'room_id' => 'required|int',
            'event_msg' => 'required|string',
            'occurs_at' => 'required|string',
        ])->validate();

        json_decode($request->input('event_msg'));
        if (json_last_error() !== JSON_ERROR_NONE) {
            throw ValidationException::withMessages(['Invalid event message: not valid JSON']);
        }

        if (!$room = Room::whereId($request->input('room_id'))->first()) {
            return response('', 404);
        }

        if (!$room->players->contains($this->session->player)) {
            throw ValidationException::withMessages(['Player is not in room']);
        }

        if (!$room->hasStarted() || $room->hasEnded()) {
            throw ValidationException::withMessages(['Room is not ongoing']);
        }

        $event = new Event([
            'occurs_at' => $request->input('occurs_at'),
            'event_json' => json_decode($request->input('event_msg')),
        ]);
        $event->player()->associate($this->session->player);
        $event->room()->associate($room);
        $event->save();

        return response([
            'success' => true,
            'room_id' => $room->id,
        ]);
    }

}
