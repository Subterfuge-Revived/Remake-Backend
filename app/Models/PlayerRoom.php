<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Class PlayerRoom
 *
 * @property int $id
 * @property Carbon $created_at
 * @property Carbon $updated_at
 * @property int $room_id
 * @property int $player_id
 * @property Player $player
 * @property Room $room
 * @package App\Models
 * @method static Builder|PlayerRoom newModelQuery()
 * @method static Builder|PlayerRoom newQuery()
 * @method static Builder|PlayerRoom query()
 * @method static Builder|PlayerRoom whereCreatedAt($value)
 * @method static Builder|PlayerRoom whereId($value)
 * @method static Builder|PlayerRoom wherePlayerId($value)
 * @method static Builder|PlayerRoom whereRoomId($value)
 * @method static Builder|PlayerRoom whereUpdatedAt($value)
 * @mixin Eloquent
 */
class PlayerRoom extends Model
{

	protected $fillable = [
		'room_id',
		'player_id'
	];

    /**
     * @return BelongsTo
     */
	public function player()
	{
		return $this->belongsTo(Player::class);
	}

    /**
     * @return BelongsTo
     */
	public function room()
	{
		return $this->belongsTo(Room::class);
	}
}
