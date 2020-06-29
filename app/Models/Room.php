<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

/**
 * @SWG\Definition(
 *  definition="Room",
 *  @SWG\Property(
 *      property="name",
 *      type="string"
 *  ),
 *  @SWG\Property(
 *      property="capacity",
 *      type="integer"
 *  ),
 *  @SWG\Property(
 *      property="category_id",
 *      type="integer"
 *  ),
 *  @SWG\Property(
 *      property="hotel_id",
 *      type="integer"
 *  )
 * )
 */
class Room extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'capacity',
    ];

    /**
     * Disable Eloquent timestamps
     *
     * @var boolean
     */
    public $timestamps = false;

    /**
     * Hotel relation
     *
     * @return BelongsTo
     */
    public function hotel()
    {
        return $this->belongsTo(Hotel::class, 'hotel_id');
    }

    /**
     * Hotel relation
     *
     * @return HasOne
     */
    public function category()
    {
        return $this->hasOne(RoomCategory::class);
    }
}
