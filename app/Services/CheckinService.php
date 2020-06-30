<?php

namespace App\Services;

use App\Models\Checkin;
use App\Models\Room;
use Carbon\Carbon;

/**
 * Class HotelService
 */
class CheckinService
{
    /**
     * @param int $roomId
     * @param Carbon $checkin_start
     * @param Carbon $checkin_end
     * @return mixed
     */
    public static function isBusy(int $roomId, Carbon $checkin_start, Carbon $checkin_end)
    {
        return Checkin::where(['room_id' => $roomId])->where(function ($query) use ($checkin_start, $checkin_end) {
            $query->whereRaw("checkin_start between '{$checkin_start}' and '{$checkin_end}'")
                ->orWhereRaw("checkin_end between '{$checkin_start}' and '{$checkin_end}'");
        })->count();
    }

    /**
     * @param int $roomId
     * @param int $votedCapacity
     * @return mixed
     */
    public static function equalCapacity(int $roomId, int $votedCapacity)
    {
        return Room::whereId($roomId)->where('capacity', '>=', $votedCapacity)->count();
    }
}
