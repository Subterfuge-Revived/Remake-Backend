<?php

namespace App\Http\Controllers;

use App\Http\Responses\DeletedResponse;
use App\Http\Responses\UnauthorizedResponse;
use App\Http\Responses\UpdatedResponse;
use App\Models\Message;
use App\Models\MessageGroup;
use App\Http\Responses\CreatedResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Validation\ValidationException;
use mofodojodino\ProfanityFilter\Check as ProfanityCheck;

class MessageController extends Controller
{
    /**
     * Get the messages from the given chat group.
     *
     * @param Request $request
     * @return Response
     * @throws ValidationException
     */
    public function index(Request $request)
    {
        $request->validate(['group_id' => 'required|int']);
        $group = MessageGroup::whereId($request->input('group_id'))->firstOrFail();

        if (!$group->message_group_members->pluck('player_id')->contains($this->session->player_id)) {
            throw ValidationException::withMessages(['Player is not in the message group']);
        }

        $messages = Message::whereMessageGroupId($request->input('group_id'))->get();
        return new Response($messages);
    }

    /**
     * Create a new message.
     *
     * @param Request $request
     * @return CreatedResponse
     * @throws ValidationException
     */
    public function store(Request $request)
    {
        $request->validate([
            'message' => 'required|string',
            'group_id' => 'required|int',
        ]);

        $profanityCheck = new ProfanityCheck();
        if ($profanityCheck->hasProfanity($request->input('message'))) {
            throw ValidationException::withMessages(['Diplomacy is not the place for profanity']);
        }

        $group = MessageGroup::whereId($request->input('group_id'))->firstOrFail();

        if (!$group->message_group_members->pluck('player_id')->contains($this->session->player->id)) {
            throw ValidationException::withMessages(['Player is not in the message group']);
        }

        $message = new Message();
        $message->message = $request->input('message');
        $message->player()->associate($this->session->player);
        $group->messages()->save($message);

        return new CreatedResponse($message);
    }

    /**
     * Show a message.
     *
     * @param Message $message
     * @return Response
     */
    public function show(Message $message)
    {
        return new Response($message);
    }

    /**
     * Edit a message.
     * TODO: Determine under which circumstances we should allow this (if at all).
     *  Right now we allow all players to edit their own messages.
     *
     * @param Message $message
     * @param Request $request
     * @return UnauthorizedResponse|UpdatedResponse
     * @throws ValidationException
     */
    public function update(Message $message, Request $request)
    {
        $request->validate([
            'message' => 'required|string',
        ]);

        if ($message->player != $this->session->player) {
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
     * @param Message $message
     * @return DeletedResponse|UnauthorizedResponse
     * @throws \Exception
     */
    public function destroy(Message $message)
    {
        if ($message->player != $this->session->player) {
            return new UnauthorizedResponse();
        }

        $message->delete();
        return new DeletedResponse($message);
    }
}
