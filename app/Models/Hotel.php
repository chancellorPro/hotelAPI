<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @SWG\Definition(
 *  definition="Hotel",
 *  @SWG\Property(
 *      property="name",
 *      type="string"
 *  ),
 *  @SWG\Property(
 *      property="rating",
 *      type="integer"
 *  )
 * )
 */
class Hotel extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'rating',
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
     * @return HasMany
     */
    public function rooms()
    {
        return $this->hasMany(Room::class);
    }
}
