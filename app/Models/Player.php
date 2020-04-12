<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Notifications\DatabaseNotification;
use Illuminate\Notifications\DatabaseNotificationCollection;
use Illuminate\Notifications\Notifiable;
use Str;

/**
 * Class Player
 *
 * @property int $id
 * @property Carbon $created_at
 * @property Carbon $updated_at
 * @property string $name
 * @property string $password
 * @property string $email
 * @property int $rating
 * @property int $wins
 * @property int $resignations
 * @property Carbon $last_online_at
 * @property Collection|Block[] $blocks
 * @property Collection|Event[] $events
 * @property Collection|Message[] $messages
 * @property Collection|Room[] $rooms
 * @property Collection|PlayerSession[] $player_sessions
 * @property Collection|Player[] $blocked_players
 * @package App\Models
 * @property-read int|null $blocks_count
 * @property-read int|null $events_count
 * @property-read int|null $messages_count
 * @property-read int|null $player_sessions_count
 * @property-read int|null $rooms_count
 * @method static Builder|Player newModelQuery()
 * @method static Builder|Player newQuery()
 * @method static Builder|Player query()
 * @method static Builder|Player whereCreatedAt($value)
 * @method static Builder|Player whereEmail($value)
 * @method static Builder|Player whereId($value)
 * @method static Builder|Player whereLastOnlineAt($value)
 * @method static Builder|Player whereName($value)
 * @method static Builder|Player wherePassword($value)
 * @method static Builder|Player whereRating($value)
 * @method static Builder|Player whereResignations($value)
 * @method static Builder|Player whereUpdatedAt($value)
 * @method static Builder|Player whereWins($value)
 * @mixin Eloquent
 * @property-read Collection|Room[] $created_rooms
 * @property-read int|null $created_rooms_count
 * @property-read DatabaseNotificationCollection|DatabaseNotification[] $notifications
 * @property-read int|null $notifications_count
 * @property-read int|null $blocked_players_count
 */
class Player extends Authenticatable
{

    use Notifiable;

    protected $dates = [
        'last_online_at',
    ];

    protected $hidden = [
        'password',
    ];

    protected $fillable = [
        'name',
        'password',
        'email',
        'rating',
        'wins',
        'resignations',
        'last_online_at',
    ];

    /**
     * @return HasMany
     */
    public function blocks()
    {
        return $this->hasMany(Block::class, 'sender_player_id');
    }

    /**
     * @return HasManyThrough
     */
    public function blocked_players()
    {
        return $this->hasManyThrough(
            Player::class,
            Block::class,
            'sender_player_id',
            'id',
            'id',
            'recipient_player_id');
    }

    /**
     * @return HasMany
     */
    public function created_rooms()
    {
        return $this->hasMany(Room::class, 'creator_player_id', 'id');
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
    public function messages()
    {
        return $this->hasMany(Message::class, 'sender_player_id');
    }

    /**
     * @return HasMany
     */
    public function rooms()
    {
        return $this->hasMany(Room::class, 'creator_player_id');
    }

    /**
     * @return HasMany
     */
    public function player_sessions()
    {
        return $this->hasMany(PlayerSession::class);
    }

    /**
     * Add an API token.
     *
     * @return string
     */
    public function new_token()
    {
        $token = Str::random(80);
        $this->player_sessions()->save(new PlayerSession([
            'token' => $token,
        ]));

        return $token;
    }

}
