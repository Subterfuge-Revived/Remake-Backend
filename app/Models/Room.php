<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

/**
 * Class Room
 *
 * @property int $id
 * @property Carbon $created_at
 * @property Carbon $updated_at
 * @property Carbon $started_at
 * @property Carbon $closed_at
 * @property int $creator_player_id
 * @property int $goal_id
 * @property string $description
 * @property bool $is_rated
 * @property bool $is_anonymous
 * @property int $map
 * @property int $seed
 * @property Player $player
 * @property Collection|Event[] $events
 * @property Collection|MessageGroup[] $message_groups
 * @property Collection|Player[] $players
 * @package App\Models
 * @property-read int|null $events_count
 * @property-read int|null $message_groups_count
 * @property-read int|null $players_count
 * @method static Builder|Room newModelQuery()
 * @method static Builder|Room newQuery()
 * @method static Builder|Room query()
 * @method static Builder|Room whereClosedAt($value)
 * @method static Builder|Room whereCreatedAt($value)
 * @method static Builder|Room whereCreatorPlayerId($value)
 * @method static Builder|Room whereDescription($value)
 * @method static Builder|Room whereGoalId($value)
 * @method static Builder|Room whereId($value)
 * @method static Builder|Room whereIsAnonymous($value)
 * @method static Builder|Room whereIsRated($value)
 * @method static Builder|Room whereMap($value)
 * @method static Builder|Room whereSeed($value)
 * @method static Builder|Room whereStartedAt($value)
 * @method static Builder|Room whereUpdatedAt($value)
 * @mixin Eloquent
 * @property int $min_rating
 * @property int $max_players
 * @property-read Player $creator_player
 * @method static Builder|Room whereMaxPlayers($value)
 * @method static Builder|Room whereMinRating($value)
 */
class Room extends Model
{

    protected $dates = [
        'started_at',
        'closed_at',
    ];

    protected $fillable = [
        'started_at',
        'closed_at',
        'creator_player_id',
        'goal_id',
        'description',
        'is_rated',
        'is_anonymous',
        'min_rating',
        'max_players',
        'map',
        'seed',
    ];

    /**
     * @return BelongsTo
     */
    public function player()
    {
        return $this->belongsTo(Player::class, 'creator_player_id');
    }

    /**
     * @return HasOne
     */
    public function creator_player()
    {
        return $this->hasOne(Player::class, 'id', 'creator_player_id');

    }

    /**
     * @return HasMany
     */
    public function events()
    {
        return $this->hasMany(Event::class);
    }

    /**
     * @return HasMany
     */
    public function message_groups()
    {
        return $this->hasMany(MessageGroup::class);
    }

    /**
     * @return BelongsToMany
     */
    public function players()
    {
        return $this->belongsToMany(Player::class, 'player_rooms')
            ->withPivot('id')
            ->withTimestamps();
    }

    /**
     * Whether the game has started.
     *
     * @return bool
     */
    public function hasStarted() {
        return $this->started_at !== null;
    }

    public function hasEnded() {
        return $this->closed_at !== null;
    }
}
