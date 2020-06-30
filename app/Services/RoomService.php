<?php

namespace App\Services;

use App\Models\Checkin;
use App\Models\Room;
use Illuminate\Database\Eloquent\Builder;

/**
 * Class RoomService
 */
class RoomService
{

    /**
     * @param array $input
     * @return Builder
     */
    public static function queryBuilder(array $input)
    {
        $roomsBuilder = Room::query();

        if (isset($input['capacity'])) {
            $roomsBuilder->where('capacity', '>=', $input['capacity']);
        }

        if (isset($input['hotel'])) {
            $roomsBuilder->where(['hotel_id' => $input['hotel']]);
        }

        if (isset($input['category'])) {
            $roomsBuilder->where(['category_id' => $input['category']]);
        }

        if (isset($input['checkin_start']) && isset($input['checkin_end'])) {
            $checkin_start = $input['checkin_start'];
            $checkin_end = $input['checkin_end'];

            $busyRooms = Checkin::select('room_id')->where(function ($query) use ($checkin_start, $checkin_end) {
                $query->whereRaw("checkin_start between '{$checkin_start}' and '{$checkin_end}'")
                    ->orWhereRaw("checkin_end between '{$checkin_start}' and '{$checkin_end}'");
            })
                ->get()
                ->pluck('room_id');

            $roomsBuilder->whereNotIn('id', $busyRooms);
        }

        return $roomsBuilder;
    }
}
