<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Goal
 *
 * @property int $id
 * @property Carbon $created_at
 * @property Carbon $updated_at
 * @property string $identifier
 * @property string $description
 * @package App\Models
 * @method static Builder|Goal newModelQuery()
 * @method static Builder|Goal newQuery()
 * @method static Builder|Goal query()
 * @method static Builder|Goal whereCreatedAt($value)
 * @method static Builder|Goal whereDescription($value)
 * @method static Builder|Goal whereId($value)
 * @method static Builder|Goal whereIdentifier($value)
 * @method static Builder|Goal whereUpdatedAt($value)
 * @mixin Eloquent
 */
class Goal extends Model
{
    protected $fillable = [
        'identifier',
        'description',
    ];
}
