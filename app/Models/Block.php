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
 * Class Block
 *
 * @property int $id
 * @property Carbon $created_at
 * @property Carbon $updated_at
 * @property int $sender_player_id
 * @property int $recipient_player_id
 * @property Player $player
 * @package App\Models
 * @method static Builder|Block newModelQuery()
 * @method static Builder|Block newQuery()
 * @method static Builder|Block query()
 * @method static Builder|Block whereCreatedAt($value)
 * @method static Builder|Block whereId($value)
 * @method static Builder|Block whereRecipientPlayerId($value)
 * @method static Builder|Block whereSenderPlayerId($value)
 * @method static Builder|Block whereUpdatedAt($value)
 * @mixin Eloquent
 */
class Block extends Model
{

    protected $fillable = [
        'sender_player_id',
        'recipient_player_id',
    ];

    protected $visible = [
        'sender_player_id',
        'recipient_player_id',
    ];

    /**
     * @return BelongsTo
     */
    public function player()
    {
        return $this->belongsTo(Player::class, 'sender_player_id');
    }

    /**
     * @return BelongsTo
     */
    public function blocked_player()
    {
        return $this->belongsTo(Player::class, 'recipient_player_id');
    }
}
