<?php

namespace App\Http\Controllers;

use App\Http\Responses\CreatedResponse;
use App\Http\Responses\DeletedResponse;
use App\Http\Responses\NotFoundResponse;
use App\Models\Block;
use App\Models\Player;
use Illuminate\Contracts\Routing\ResponseFactory;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Validation\ValidationException;

class BlockController extends Controller
{
    /**
     * Block another player.
     *
     * @param Request $request
     * @return ResponseFactory|Response
     * @throws ValidationException
     */
    public function store(Request $request)
    {
        \Validator::make($request->all(), [
            'other_player_id' => 'required|int',
        ])->validate();

        if (!$otherPlayer = Player::whereId($request->input('other_player_id'))->first()) {
            throw ValidationException::withMessages(['Player does not exist']);
        }
        if ($otherPlayer == $this->session->player) {
            throw ValidationException::withMessages(['You cannot block yourself']);
        }

        if ($this->session->player->blocked_players->contains($otherPlayer)) {
            throw ValidationException::withMessages(['You have already blocked this player']);
        }

        $block = new Block();
        $block->player()->associate($this->session->player);
        $block->blocked_player()->associate($otherPlayer);
        $block->save();

        // Return Created
        return new CreatedResponse($block);
    }

    /**
     * Unblock another player.
     *
     * @param Request $request
     * @return ResponseFactory|Response
     * @throws \Exception
     */
    public function delete(Request $request)
    {
        \Validator::make($request->all(), [
            'other_player_id' => 'required|int',
        ])->validate();

        if (!$block = Block
            ::where('sender_player_id', '=', $this->session->player_id)
            ->where('recipient_player_id', '=', $request->input('other_player_id'))
            ->first()
        ) {
            // No such block found
            return new NotFoundResponse();
        }

        $block->delete();

        return new DeletedResponse($block);
    }

    /**
     * Get the list of blocked players.
     *
     * @param Request $request
     * @return ResponseFactory|Response
     */
    public function index(Request $request)
    {
        $blocks = Block::whereSenderPlayerId($this->session->player_id)->get();
        return new Response($blocks);
    }
}
