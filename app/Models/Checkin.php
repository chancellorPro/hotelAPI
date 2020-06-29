<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @SWG\Definition(
 *  definition="Checkin",
 *  @SWG\Property(
 *      property="client_name",
 *      type="string"
 *  ),
 *  @SWG\Property(
 *      property="phone",
 *      type="string"
 *  ),
 *  @SWG\Property(
 *      property="voted_capacity",
 *      type="integer"
 *  ),
 *  @SWG\Property(
 *      property="checkin_start",
 *      type="date-time"
 *  ),
 *  @SWG\Property(
 *      property="checkin_end",
 *      type="date-time"
 *  ),
 *  @SWG\Property(
 *      property="room_id",
 *      type="integer"
 *  )
 * )
 */
class Checkin extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'checkin';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'client_name', 'phone', 'voted_capacity', 'checkin_start', 'checkin_end', 'room_id',
    ];

    /**
     * Room relation
     *
     * @return BelongsTo
     */
    public function hotel()
    {
        return $this->belongsTo(Room::class, 'room_id');
    }
}
