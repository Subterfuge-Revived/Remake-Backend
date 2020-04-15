<?php



namespace App\Models;

use App\Traits\SerializesTimestamps;
use Carbon\Carbon;
use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

/**
 * Class FailedJob
 *
 * @property int $id
 * @property string $connection
 * @property string $queue
 * @property string $payload
 * @property string $exception
 * @property Carbon $failed_at
 * @package App\Models
 * @method static Builder|FailedJob newModelQuery()
 * @method static Builder|FailedJob newQuery()
 * @method static Builder|FailedJob query()
 * @method static Builder|FailedJob whereConnection($value)
 * @method static Builder|FailedJob whereException($value)
 * @method static Builder|FailedJob whereFailedAt($value)
 * @method static Builder|FailedJob whereId($value)
 * @method static Builder|FailedJob wherePayload($value)
 * @method static Builder|FailedJob whereQueue($value)
 * @mixin Eloquent
 */
class FailedJob extends Model
{
    use SerializesTimestamps;

    public $timestamps = false;

    protected $dates = [
        'failed_at',
    ];

    protected $fillable = [
        'connection',
        'queue',
        'payload',
        'exception',
        'failed_at',
    ];
}
