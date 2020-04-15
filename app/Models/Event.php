<?php



namespace App\Models;

use App\Traits\SerializesTimestamps;
use Carbon\Carbon;
use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Class Event
 *
 * @property int $id
 * @property Carbon $created_at
 * @property Carbon $updated_at
 * @property Carbon $occurs_at
 * @property int $room_id
 * @property int $player_id
 * @property array $event_json
 * @property Player $player
 * @property Room $room
 * @package App\Models
 * @method static Builder|Event newModelQuery()
 * @method static Builder|Event newQuery()
 * @method static Builder|Event query()
 * @method static Builder|Event whereCreatedAt($value)
 * @method static Builder|Event whereEventJson($value)
 * @method static Builder|Event whereId($value)
 * @method static Builder|Event whereOccursAt($value)
 * @method static Builder|Event wherePlayerId($value)
 * @method static Builder|Event whereRoomId($value)
 * @method static Builder|Event whereUpdatedAt($value)
 * @mixin Eloquent
 */
class Event extends Model
{
    use SerializesTimestamps;

    protected $casts = [
        'event_json' => 'json',
    ];

    protected $dates = [
        'occurs_at',
    ];

    protected $fillable = [
        'occurs_at',
        'room_id',
        'player_id',
        'event_json',
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
