<?php

namespace App\Http\Controllers;

use App\Models\Message;
use App\Models\MessageGroup;
use Illuminate\Contracts\Routing\ResponseFactory;
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
     * @return ResponseFactory|Response
     * @throws ValidationException
     */
    public function store(Request $request)
    {
        \Validator::make($request->all(), [
            'message' => 'required|string',
            'group_id' => 'required|int',   // TODO: Implement a call that returns chat groups
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
        return response('', 201);
    }
}
