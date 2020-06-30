<?php

namespace App\Http\Controllers\API;

use App\Http\Requests\CheckinRequest;
use App\Models\Checkin;
use App\Services\CheckinService;
use Carbon\Carbon;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

/**
 * Class CheckinController
 * @package App\Http\Controllers\API
 */
class CheckinController extends Controller
{

    /**
     * @SWG\Post(
     *     path="/api/checkin",
     *     summary="Checkin api method",
     *     tags={"Checkin"},
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
     *         name="client_name",
     *         in="formData",
     *         description="Client name",
     *         required=true,
     *         type="string",
     *         @SWG\Schema(),
     *     ),
     *     @SWG\Parameter(
     *         name="phone",
     *         in="formData",
     *         description="Client phone",
     *         required=true,
     *         type="string",
     *         @SWG\Schema(),
     *     ),
     *     @SWG\Parameter(
     *         name="voted_capacity",
     *         in="formData",
     *         description="Voted room capacity",
     *         required=false,
     *         type="number",
     *         @SWG\Schema(),
     *     ),
     *     @SWG\Parameter(
     *         name="checkin_start",
     *         in="formData",
     *         description="Checkin start date",
     *         required=true,
     *         type="string",
     *         @SWG\Schema(),
     *     ),
     *     @SWG\Parameter(
     *         name="checkin_end",
     *         in="formData",
     *         description="Checkin end date",
     *         required=true,
     *         type="string",
     *         @SWG\Schema(),
     *     ),
     *     @SWG\Parameter(
     *         name="room_id",
     *         in="formData",
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

            if (CheckinService::isBusy($input['room_id'], $checkin_start, $checkin_end)) {
                return response()->json(['error' => 'Room is busy'], $this->successStatus);
            }

            if (!CheckinService::equalCapacity($input['room_id'], $input['voted_capacity'])) {
                return response()->json(['error' => 'Selected room is too small for you'], $this->successStatus);
            }

            Checkin::create($input);

            return response()->json(['success' => 'Checkin successfully saved'], $this->createdStatus);
        } else {
            return response()->json(['error' => 'Unauthorised'], $this->unauthorisedStatus);
        }
    }
}
