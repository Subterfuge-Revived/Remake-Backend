<?php

namespace App\Http\Controllers;

use App\Http\Responses\CreatedResponse;
use App\Http\Responses\UnauthorizedResponse;
use App\Models\MessageGroup;
use App\Models\MessageGroupMember;
use App\Models\Player;
use App\Models\Room;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;

class MessageGroupController extends Controller
{

    /**
     * Return a list of message groups that the player is in.
     * @param Request $request
     * @return Response
     */
    public function index(Request $request)
    {
        return new Response($this->session->player->message_groups->load('message_group_members.player'));
    }

    /**
     * Create a new message group.
     *
     * @param Request $request
     * @return Response
     * @throws ValidationException
     */
    public function store(Request $request)
    {
        $request->validate([
            'room_id' => 'required|int',
            'participants' => 'required|array',
            'participants.*' => 'required|int|' . Rule::in(Player::pluck('id')),
        ]);

        $room = Room::whereId($request->input('room_id'))->firstOrFail();

        if (!$room->players->contains($this->session->player)) {
            throw ValidationException::withMessages(['You are not part of this room']);
        }

        if (collect($request->get('participants'))->diff($room->players->pluck('id'))->isNotEmpty()) {
            throw ValidationException::withMessages(['Not all given participants are in the room']);
        }

        // The participants are the creator and the other players that he invites
        $participants = Player::whereIn('id', $request->input('participants'))
            ->get()->add($this->session->player);

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

        return new CreatedResponse($messageGroup);
    }

    /**
     * @param MessageGroup $group
     * @return Response|UnauthorizedResponse
     */
    public function show(MessageGroup $group)
    {
        if (!$this->session->player->message_groups->contains($group)) {
            return new UnauthorizedResponse();
        }

        return new Response($group->load('message_group_members.player'));
    }

    /**
     * @param MessageGroup $group
     * @param Request $request
     * @return UnauthorizedResponse
     */
    public function update(MessageGroup $group, Request $request)
    {
        // It is nonsensical to update a MessageGroup as it has no real properties on its own.
        return new UnauthorizedResponse();
    }

    /**
     * @param MessageGroup $group
     * @return UnauthorizedResponse
     */
    public function destroy(MessageGroup $group)
    {
        // Perhaps an admin should be allowed to delete a chat group?
        // But it makes no sense for any of the group members to delete the whole chat.
        return new UnauthorizedResponse();
    }
}
