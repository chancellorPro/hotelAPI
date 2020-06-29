<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @SWG\Definition(
 *  definition="RoomCategory",
 *  @SWG\Property(
 *      property="title",
 *      type="string"
 *  )
 * )
 */
class RoomCategory extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'room_categories';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
    ];

    /**
     * Disable Eloquent timestamps
     *
     * @var boolean
     */
    public $timestamps = false;

    /**
     * Room relation
     *
     * @return hasMany
     */
    public function hotel()
    {
        return $this->hasMany(Room::class, 'category_id');
    }
}
