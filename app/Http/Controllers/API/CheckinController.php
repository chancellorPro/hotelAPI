<?php

namespace App\Http\Controllers\API;

use App\Http\Requests\CheckinRequest;
use App\Models\Checkin;
use App\Models\Room;
use Carbon\Carbon;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class CheckinController extends Controller
{

    /**
     * @SWG\Post(
     *     path="/checkin",
     *     summary="Checkin api method",
     *     tags={"Checkin"},
     *     @SWG\Parameter(
     *         name="client_name",
     *         in="body",
     *         description="Client name",
     *         required=true,
     *         type="string",
     *         @SWG\Schema(),
     *     ),
     *     @SWG\Parameter(
     *         name="phone",
     *         in="body",
     *         description="Client phone",
     *         required=true,
     *         type="string",
     *         @SWG\Schema(),
     *     ),
     *     @SWG\Parameter(
     *         name="voted_capacity",
     *         in="body",
     *         description="Voted room capacity",
     *         required=false,
     *         type="number",
     *         @SWG\Schema(),
     *     ),
     *     @SWG\Parameter(
     *         name="checkin_start",
     *         in="body",
     *         description="Checkin start date",
     *         required=true,
     *         type="date-time",
     *         @SWG\Schema(),
     *     ),
     *     @SWG\Parameter(
     *         name="checkin_end",
     *         in="body",
     *         description="Checkin end date",
     *         required=true,
     *         type="date-time",
     *         @SWG\Schema(),
     *     ),
     *     @SWG\Parameter(
     *         name="room_id",
     *         in="body",
     *         description="Room id",
     *         required=true,
     *         type="number",
     *         @SWG\Schema(),
     *     ),
     *     @SWG\Response(
     *         response=200,
     *         description="Checkin successfully saved",
     *         @SWG\Schema(
     *             type="application/json",
     *         ),
     *     ),
     *     @SWG\Response(
     *         response="201",
     *         description="Validation error",
     *     ),
     *     @SWG\Response(
     *         response="401",
     *         description="Unauthorized user",
     *     )
     * )
     */
    public function checkin(CheckinRequest $request)
    {
        if (Auth::user()) {
            $input = $request->all();

            $checkin_start = Carbon::createFromFormat('Y-m-d', $input['checkin_start']);
            $checkin_end = Carbon::createFromFormat('Y-m-d', $input['checkin_end']);

            if ($this->isBusy($input['room_id'], $checkin_start, $checkin_end)) {
                return response()->json(['error' => 'Room is busy'], 201);
            }

            if (!$this->equalCapacity($input['room_id'], $input['voted_capacity'])) {
                return response()->json(['error' => 'Selected room is too small for you'], 201);
            }

            Checkin::create($input);

            return response()->json(['success' => 'Checkin successfully saved'], 200);
        } else {
            return response()->json(['error' => 'Unauthorised'], 401);
        }
    }

    /**
     * @param $roomId
     * @param $checkin_start
     * @param $checkin_end
     * @return mixed
     */
    public function isBusy($roomId, $checkin_start, $checkin_end)
    {
        return Checkin::where(['room_id' => $roomId])->where(function ($query) use ($checkin_start, $checkin_end) {
            $query->whereRaw("checkin_start between '{$checkin_start}' and '{$checkin_end}'")
                ->orWhereRaw("checkin_end between '{$checkin_start}' and '{$checkin_end}'");
        })->count();
    }

    /**
     * @param $roomId
     * @param $votedCapacity
     * @return mixed
     */
    public function equalCapacity($roomId, $votedCapacity)
    {
        return Room::whereId($roomId)->where('capacity', '>=', $votedCapacity)->count();
    }
}
