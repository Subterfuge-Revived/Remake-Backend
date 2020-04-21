<?php

namespace App\Http\Controllers;

use App\Http\Responses\CreatedResponse;
use App\Http\Responses\DeletedResponse;
use App\Http\Responses\UnauthorizedResponse;
use App\Http\Responses\UpdatedResponse;
use App\Models\Block;
use App\Models\Player;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Validation\ValidationException;

class BlockController extends Controller
{
    /**
     * Get the list of blocked players.
     *
     * @param Request $request
     * @return Response
     */
    public function index(Request $request)
    {
        $blocks = Block::whereSenderPlayerId($this->session->player_id)->get();
        return new Response($blocks);
    }

    /**
     * Block another player.
     *
     * @param Request $request
     * @return Response
     * @throws ValidationException
     */
    public function store(Request $request)
    {
        \Validator::make($request->all(), [
            'other_player_id' => 'required|int',
        ])->validate();

        $otherPlayer = Player::whereId($request->input('other_player_id'))->firstOrFail();

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
     * Delete a block (effectively unblocking another player).
     *
     * @param Block $block
     * @return DeletedResponse|UnauthorizedResponse
     * @throws \Exception
     */
    public function destroy(Block $block)
    {
        if (!$block->sender_player_id == $this->session->player_id) {
            return new UnauthorizedResponse();
        }

        $block->delete();

        return new DeletedResponse($block);
    }

    /**
     * Show a block.
     *
     * @param Block $block
     * @return Response|UnauthorizedResponse
     */
    public function show(Block $block)
    {
        if (!$block->sender_player_id === $this->session->player_id) {
            return new UnauthorizedResponse();
        }

        return new Response($block);
    }

    /**
     * It currently makes little to no sense to update a block.
     * However, in the RESTful API we will allow a player to change
     *
     * @param Block $block
     * @param Request $request
     * @return UpdatedResponse|UnauthorizedResponse
     */
    public function update(Block $block, Request $request)
    {
        $request->validate(['other_player_id' => 'required|int']);
        $otherPlayer = Player::whereId($request->get('other_player_id'))->firstOrFail();

        if (!$block->sender_player_id === $this->session->player_id) {
            return new UnauthorizedResponse();
        }

        $block->blocked_player()->associate($otherPlayer);
        $block->save();

        return new UpdatedResponse($block);
    }

}
