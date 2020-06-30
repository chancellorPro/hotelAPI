<?php

namespace App\Http\Controllers\API;

use App\Http\Requests\RoomRequest;
use App\Http\Controllers\Controller;
use App\Services\RoomService;
use Illuminate\Support\Facades\Auth;

/**
 * Class RoomController
 * @package App\Http\Controllers\API
 */
class RoomController extends Controller
{
    /**
     * @SWG\Get(
     *     path="/api/rooms?capacity={capacity}&hotel={hotel}&category={category}&checkin_start={checkin_start}&checkin_end={checkin_end}",
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

            $rooms = RoomService::queryBuilder($input)->get()->toArray();

            return response()->json(['rooms' => $rooms], $this->successStatus);
        } else {
            return response()->json(['error' => 'Unauthorised'], $this->unauthorisedStatus);
        }
    }
}
