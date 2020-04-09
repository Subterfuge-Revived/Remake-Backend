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
 * @property Carbon $expires_at
 * @property int $player_id
 * @property string $token
 * @property Player $player
 * @package App\Models
 * @method static Builder|PlayerSession newModelQuery()
 * @method static Builder|PlayerSession newQuery()
 * @method static Builder|PlayerSession query()
 * @method static Builder|PlayerSession whereCreatedAt($value)
 * @method static Builder|PlayerSession whereExpiresAt($value)
 * @method static Builder|PlayerSession whereId($value)
 * @method static Builder|PlayerSession wherePlayerId($value)
 * @method static Builder|PlayerSession whereToken($value)
 * @method static Builder|PlayerSession whereUpdatedAt($value)
 * @mixin Eloquent
 */
class PlayerSession extends Model
{

    /**
     * @var array
     */
	protected $hidden = [
		'token'
	];

    /**
     * @var array
     */
	protected $fillable = [
	    'expires_at',
		'player_id',
		'token'
	];

    /**
     * @var bool
     */
	private $tokenIsHashed = false;

    /**
     * @return BelongsTo
     */
	public function player()
	{
		return $this->belongsTo(Player::class);
	}

    /**
     * Save the session.
     *
     * @param array $options
     * @return bool
     */
	public function save(array $options = [])
    {
        if (!$this->tokenIsHashed) {
            $this->token = hash('sha256', $this->token);
            $this->tokenIsHashed = true;
        }

        $this->expires_at = $this->expires_at ?? Carbon::now()->addHour();

        return parent::save($options);
    }
}
