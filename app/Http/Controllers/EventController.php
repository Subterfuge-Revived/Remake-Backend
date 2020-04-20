<?php

namespace App\Http\Controllers;

use App\Http\Responses\DeletedResponse;
use App\Http\Responses\NotFoundResponse;
use App\Http\Responses\UpdatedResponse;
use App\Models\Event;
use App\Models\Room;
use App\Http\Responses\CreatedResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Validation\ValidationException;

class EventController extends Controller
{
    /**
     * Show a list of events.
     *
     * @param Request $request
     * @return Response
     * @throws ValidationException
     */
    public function index(Request $request)
    {
        $request->validate([
            'room_id' => 'required|int',
            'filter' => 'required|string', // Possible values: 'time', 'tick' but maybe others?
            'filter_arg' => 'required|string',
        ]);

        $room = Room::whereId($request->input('room_id'))->firstOrFail();

        if (!$room->players->contains($this->session->player)) {
            throw ValidationException::withMessages(['Player is not in the room']);
        }

        // TODO: We should change this API to accept multiple filters
        $eventsQuery = $room->events();
        if ($request->input('filter') === 'time') {
            $eventsQuery = $eventsQuery->where('created_at', '>=', $request->input('filter_arg'));
        }
        elseif ($request->input('filter') === 'tick') {
            $eventsQuery = $eventsQuery->where('occurs_at', '>=', $request->input('filter_arg'));
        }

        $events = $eventsQuery->get();

        return new Response($events->map(function (Event $event) {
            return [
                'event_id' => $event->id,
                'time_issued' => $event->created_at->unix(),
                'occurs_at' => $event->occurs_at->unix(),
                'player_id' => $event->player_id,
                'event_msg' => $event->event_json,
            ];
        }));
    }

    /**
     * Create an event.
     *
     * @param Request $request
     * @return Response
     * @throws ValidationException
     */
    public function store(Request $request)
    {
        $request->validate([
            'room_id' => 'required|int',
            'event_msg' => 'required|string',
            'occurs_at' => 'required|string',
        ]);

        json_decode($request->input('event_msg'));
        if (json_last_error() !== JSON_ERROR_NONE) {
            throw ValidationException::withMessages(['Invalid event message: not valid JSON']);
        }

        $room = Room::whereId($request->input('room_id'))->firstOrFail();

        if (!$room->players->contains($this->session->player)) {
            throw ValidationException::withMessages(['Player is not in room']);
        }

        if (!$room->isOngoing()) {
            throw ValidationException::withMessages(['Room is not ongoing']);
        }

        $event = new Event([
            'occurs_at' => $request->input('occurs_at'),
            'event_json' => json_decode($request->input('event_msg')),
        ]);
        $event->player()->associate($this->session->player);
        $event->room()->associate($room);
        $event->save();

        return new CreatedResponse($event);
    }

    /**
     * Delete an event.
     *
     * @param Request $request
     * @return Response
     * @throws ValidationException|\Exception
     */
    public function delete(Request $request)
    {
        $request->validate([
            'room_id' => 'required|int',
            'event_id' => 'required|int',
        ]);

        $event = $this->getEvent($request);

        if (!$event->occurs_at->isFuture()) {
            throw ValidationException::withMessages(['Event is not in the future']);
        }

        $event->delete();

        return new DeletedResponse($event);
    }

    /**
     * Update an event.
     *
     * @param Request $request
     * @return Response
     * @throws ValidationException
     */
    public function update(Request $request)
    {
        $request->validate([
            'room_id' => 'required|int',
            'event_id' => 'required|int',
            'event_msg' => 'required|string',
        ]);

        $event = $this->getEvent($request);
        if (!$event->occurs_at->isFuture()) {
            throw ValidationException::withMessages(['Event is not in the future']);
        }

        $event->event_json = json_decode($request->input('event_msg'));
        $event->save();

        return new UpdatedResponse($event);
    }

    /**
     * Validates whether the requester has access to an existing event.
     * If so, returns the event. Otherwise, throws an exception.
     *
     * @param Request $request
     * @return Event
     * @throws ValidationException
     */
    private function getEvent(Request $request)
    {
        $room = Room::whereId($request->input('room_id'))->firstOrFail();

        if (!$room->players->contains($this->session->player)) {
            throw ValidationException::withMessages(['Player is not in the room']);
        }
        if (!$room->isOngoing()) {
            throw ValidationException::withMessages(['Room is not ongoing']);
        }

        /** @var Event $event */
        if (!$event = $room->events->where('id', $request->input('event_id'))->first()) {
            throw ValidationException::withMessages(['Event does not exist or does not belong to the room']);
        }
        if (!$event->player != $this->session->player) {
            throw ValidationException::withMessages(['Event does not belong to the given player']);
        }

        return $event;
    }

}
