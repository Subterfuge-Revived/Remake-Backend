<?php

namespace App\Http\Controllers;

use App\Events\ChatMessageEvent;
use App\Http\Responses\DeletedResponse;
use App\Http\Responses\UnauthorizedResponse;
use App\Http\Responses\UpdatedResponse;
use App\Models\Message;
use App\Models\MessageGroup;
use App\Http\Responses\CreatedResponse;
use App\Models\Room;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Validation\ValidationException;
use mofodojodino\ProfanityFilter\Check as ProfanityCheck;

class MessageController extends Controller
{
    /**
     * Get the messages from the given chat group.
     *
     * @param Room $room
     * @param MessageGroup $group
     * @return Response|UnauthorizedResponse
     */
    public function index(Room $room, MessageGroup $group)
    {
        if (!$group->message_group_members->pluck('player_id')->contains($this->session->player_id)) {
            return new UnauthorizedResponse();
        }

        return new Response($group->messages);
    }

    /**
     * Create a new message.
     *
     * @param Room $room
     * @param MessageGroup $group
     * @param Request $request
     * @return CreatedResponse
     * @throws ValidationException
     */
    public function store(Room $room, MessageGroup $group, Request $request)
    {
        $request->validate([
            'message' => 'required|string',
        ]);

        $profanityCheck = new ProfanityCheck();
        if ($profanityCheck->hasProfanity($request->input('message'))) {
            throw ValidationException::withMessages(['Diplomacy is not the place for profanity']);
        }

        if (!$group->message_group_members->pluck('player_id')->contains($this->session->player->id)) {
            throw ValidationException::withMessages(['Player is not in the message group']);
        }

        $message = new Message();
        $message->message = $request->input('message');
        $message->player()->associate($this->session->player);
        $group->messages()->save($message);

        event(new ChatMessageEvent($message));

        return new CreatedResponse($message);
    }

    /**
     * Show a message.
     *
     * @param Room $room
     * @param MessageGroup $group
     * @param Message $message
     * @return Response|UnauthorizedResponse
     */
    public function show(Room $room, MessageGroup $group, Message $message)
    {
        if (!$group->message_group_members->pluck('player_id')->contains($this->session->player_id)) {
            return new UnauthorizedResponse();
        }

        return new Response($message);
    }

    /**
     * Edit a message.
     * TODO: Determine under which circumstances we should allow this (if at all).
     *  Right now we allow all players to edit their own messages.
     *
     * @param Room $room
     * @param MessageGroup $group
     * @param Message $message
     * @param Request $request
     * @return UnauthorizedResponse|UpdatedResponse
     */
    public function update(Room $room, MessageGroup $group, Message $message, Request $request)
    {
        $request->validate([
            'message' => 'required|string',
        ]);

        if (
            !$group->message_group_members->pluck('player_id')->contains($this->session->player_id) ||
            $message->player != $this->session->player
        ) {
            return new UnauthorizedResponse();
        }

        $message->message = $request->get('message');
        $message->save();

        return new UpdatedResponse($message);
    }

    /**
     * Delete a message.
     * TODO: Determine under which circumstances we should allow this (if at all).
     *  Right now we allow all players to delete their own messages.
     * @param Room $room
     * @param MessageGroup $group
     * @param Message $message
     * @return DeletedResponse|UnauthorizedResponse
     * @throws \Exception
     */
    public function destroy(Room $room, MessageGroup $group, Message $message)
    {
        if (
            !$group->message_group_members->pluck('player_id')->contains($this->session->player_id) ||
            $message->player != $this->session->player
        ) {
            return new UnauthorizedResponse();
        }

        $message->delete();
        return new DeletedResponse($message);
    }
}
