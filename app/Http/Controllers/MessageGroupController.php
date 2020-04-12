<?php

namespace App\Http\Controllers;

use App\Models\MessageGroup;
use App\Models\MessageGroupMember;
use App\Models\Player;
use App\Models\Room;
use Illuminate\Contracts\Routing\ResponseFactory;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;
use mofodojodino\ProfanityFilter\Check as ProfanityCheck;

class MessageGroupController extends Controller
{
    /**
     * Create a new message group.
     *
     * @param Request $request
     * @return ResponseFactory|Response
     * @throws ValidationException
     */
    public function store(Request $request)
    {
        \Validator::make($request->all(), [
            'room_id' => 'required|int',
            'participants' => 'required|array',
            'participants.*' => 'required|int|' . Rule::in(Player::pluck('id')),
        ])->validate();

        if (!$room = Room::whereId($request->input('room_id'))->first()) {
            throw ValidationException::withMessages(['Room does not exist']);
        }

        if (!$room->players->contains($this->session->player)) {
            throw ValidationException::withMessages(['You are not part of this room']);
        }

        // The participants are the creator and the other players that he invites
        $participants = Player::whereIn('id', $request->input('participants'))->get()->add($this->session->player);

        if ($this->session->player->blocked_players
            ->intersect($participants)
            ->isNotEmpty()
        ) {
            throw ValidationException::withMessages(['You cannot start a chat group with a player you blocked']);
        }

        $messageGroup = new MessageGroup();
        $messageGroup->room()->associate($room);

        // We save the group already so that we have an id to link to the members.
        $messageGroup->save();
        $messageGroup->refresh();

        // Translate players to group member records
        $members = $participants->map(function (Player $participant) use ($messageGroup) {
            $member = new MessageGroupMember();
            $member->player()->associate($participant);
            $member->message_group()->associate($messageGroup);
            return $member;
        });

        $messageGroup->message_group_members()->saveMany($members);

        return response('', 201);
    }
}
