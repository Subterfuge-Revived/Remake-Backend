<?php

namespace App\Http\Controllers;

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
     * Create a new message.
     *
     * @param Request $request
     * @return CreatedResponse
     * @throws ValidationException
     */
    public function store(Request $request)
    {
        \Validator::make($request->all(), [
            'message' => 'required|string',
            'group_id' => 'required|int',
        ])->validate();

        $profanityCheck = new ProfanityCheck();
        if ($profanityCheck->hasProfanity($request->input('message'))) {
            throw ValidationException::withMessages(['Diplomacy is not the place for profanity']);
        }

        if (!$group = MessageGroup::whereId($request->input('group_id'))->first()) {
            throw ValidationException::withMessages(['Message group does not exist']);
        }

        if (!$group->message_group_members->pluck('player_id')->contains($this->session->player->id)) {
            throw ValidationException::withMessages(['Player is not in the message group']);
        }

        $message = new Message();
        $message->message = $request->input('message');
        $message->player()->associate($this->session->player);
        $group->messages()->save($message);

        // Return Created response
        return new CreatedResponse($message);
    }

    /**
     * Get the messages from the given chat group.
     *
     * @param Request $request
     * @return Response
     * @throws ValidationException
     */
    public function index(Request $request)
    {
        \Validator::make($request->all(), [
            'group_id' => 'required|int',
        ])->validate();

        if (!$group = MessageGroup::whereId($request->input('group_id'))->first()) {
            throw ValidationException::withMessages(['Message group does not exist']);
        }

        if (!$group->message_group_members->pluck('player_id')->contains($this->session->player_id)) {
            throw ValidationException::withMessages(['Player is not in the message group']);
        }

        $messages = Message::whereMessageGroupId($request->input('group_id'))->get();

        return new Response($messages);
    }
}
