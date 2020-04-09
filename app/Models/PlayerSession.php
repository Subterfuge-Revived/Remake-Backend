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
 * Class PlayerSession
 *
 * @property int $id
 * @property Carbon $created_at
 * @property Carbon $updated_at
 * @property int $player_id
 * @property string $token
 * @property Player $player
 * @package App\Models
 * @method static Builder|PlayerSession newModelQuery()
 * @method static Builder|PlayerSession newQuery()
 * @method static Builder|PlayerSession query()
 * @method static Builder|PlayerSession whereCreatedAt($value)
 * @method static Builder|PlayerSession whereId($value)
 * @method static Builder|PlayerSession wherePlayerId($value)
 * @method static Builder|PlayerSession whereToken($value)
 * @method static Builder|PlayerSession whereUpdatedAt($value)
 * @mixin Eloquent
 */
class PlayerSession extends Model
{

	protected $hidden = [
		'token'
	];

	protected $fillable = [
		'player_id',
		'token'
	];

    /**
     * @return BelongsTo
     */
	public function player()
	{
		return $this->belongsTo(Player::class);
	}
}
