<?php



namespace App\Models;

use App\Traits\SerializesTimestamps;
use Carbon\Carbon;
use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * Class MessageGroup
 *
 * @property int $id
 * @property Carbon $created_at
 * @property Carbon $updated_at
 * @property int $room_id
 * @property Room $room
 * @property Collection|MessageGroupMember[] $message_group_members
 * @property Collection|Message[] $messages
 * @package App\Models
 * @property-read int|null $message_group_members_count
 * @property-read int|null $messages_count
 * @method static Builder|MessageGroup newModelQuery()
 * @method static Builder|MessageGroup newQuery()
 * @method static Builder|MessageGroup query()
 * @method static Builder|MessageGroup whereCreatedAt($value)
 * @method static Builder|MessageGroup whereId($value)
 * @method static Builder|MessageGroup whereRoomId($value)
 * @method static Builder|MessageGroup whereUpdatedAt($value)
 * @mixin Eloquent
 */
class MessageGroup extends Model
{
    use SerializesTimestamps;

	protected $fillable = [
		'room_id'
	];

	protected $visible = [
	    'id',
	    'room_id',
        'message_group_members',
    ];

    /**
     * @return BelongsTo
     */
	public function room()
	{
		return $this->belongsTo(Room::class);
	}

    /**
     * @return HasMany
     */
	public function message_group_members()
	{
		return $this->hasMany(MessageGroupMember::class);
	}

    /**
     * @return HasMany
     */
	public function messages()
	{
		return $this->hasMany(Message::class);
	}
}
