<?php

namespace App\Http\Controllers\API;

use App\Http\Requests\RoomRequest;
use App\Models\Checkin;
use App\Models\Room;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class RoomController extends Controller
{
    /**
     * @SWG\Get(
     *     path="/rooms/{?capacity}/{?hotel}/{?category}/{?checkin_start}/{?checkin_end}",
     *     summary="Get room list",
     *     tags={"Rooms"},
     *     @SWG\Parameter(
     *         type="string",
     *         name="Authorization",
     *         in="header",
     *         description="Bearer eyJ0eXAi...",
     *         required=true,
     *     ),
     *     @SWG\Parameter(
     *         name="Content-Type",
     *         in="header",
     *         description="application/x-www-form-urlencoded",
     *         required=true,
     *         type="string",
     *     ),
     *     @SWG\Parameter(
     *         name="capacity",
     *         in="path",
     *         description="Room capacity",
     *         required=false,
     *         type="number",
     *     ),
     *     @SWG\Parameter(
     *         name="hotel",
     *         in="path",
     *         description="Hotel ID",
     *         required=false,
     *         type="number",
     *     ),
     *     @SWG\Parameter(
     *         name="category",
     *         in="path",
     *         description="Room category",
     *         required=false,
     *         type="number",
     *     ),
     *     @SWG\Parameter(
     *         name="checkin_start",
     *         in="path",
     *         description="Checkin start date with Y-m-d format",
     *         required=false,
     *         type="string",
     *     ),
     *     @SWG\Parameter(
     *         name="checkin_end",
     *         in="path",
     *         description="Checkin end date with Y-m-d format",
     *         required=false,
     *         type="string",
     *     ),
     *     @SWG\Response(
     *         response=200,
     *         description="Room list",
     *         @SWG\Schema(
     *             type="array",
     *             @SWG\Items(ref="#/definitions/Room")
     *         ),
     *     ),
     *     @SWG\Response(
     *         response="401",
     *         description="Unauthorised | Validation error",
     *     )
     * )
     */
    public function rooms(RoomRequest $request)
    {
        if (Auth::user()) {
            $input = $request->all();

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

            $rooms = $roomsBuilder->get()->toArray();

            return response()->json(['rooms' => $rooms], 200);
        } else {
            return response()->json(['error' => 'Unauthorised'], 401);
        }
    }
}
