<?php

namespace App\Http\Controllers;

use App\Http\Responses\CreatedResponse;
use App\Http\Responses\DeletedResponse;
use App\Http\Responses\NotFoundResponse;
use App\Http\Responses\UnauthorizedResponse;
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
     * Unblock another player.
     *
     * @param Request $request
     * @return Response
     * @throws \Exception
     */
    public function delete(Request $request)
    {
        $this->validate($request, ['other_player_id' => 'required|int']);

        /** @var Block $block */
        $block = Block
            ::where('sender_player_id', '=', $this->session->player_id)
            ->where('recipient_player_id', '=', $request->input('other_player_id'))
            ->firstOrFail();

        return $this->destroy($block);
    }
}
