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
 * Class Message
 *
 * @property int $id
 * @property Carbon $created_at
 * @property Carbon $updated_at
 * @property int $sender_player_id
 * @property int $message_group_id
 * @property string $message
 * @property MessageGroup $message_group
 * @property Player $player
 * @package App\Models
 * @method static Builder|Message newModelQuery()
 * @method static Builder|Message newQuery()
 * @method static Builder|Message query()
 * @method static Builder|Message whereCreatedAt($value)
 * @method static Builder|Message whereId($value)
 * @method static Builder|Message whereMessage($value)
 * @method static Builder|Message whereMessageGroupId($value)
 * @method static Builder|Message whereSenderPlayerId($value)
 * @method static Builder|Message whereUpdatedAt($value)
 * @mixin Eloquent
 */
class Message extends Model
{
    protected $fillable = [
        'sender_player_id',
        'message_group_id',
        'message',
    ];

    /**
     * @return BelongsTo
     */
    public function message_group()
    {
        return $this->belongsTo(MessageGroup::class);
    }

    /**
     * @return BelongsTo
     */
    public function player()
    {
        return $this->belongsTo(Player::class, 'sender_player_id');
    }
}
