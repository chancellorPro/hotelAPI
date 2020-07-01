<?php

namespace App\Http\Controllers\API;

use App\Http\Requests\HotelRequest;
use App\Models\Hotel;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

/**
 * Class HotelController
 * @package App\Http\Controllers\API
 */
class HotelController extends Controller
{
    /**
     * @SWG\Get(
     *     path="/api/hotels?rating={rating}",
     *     summary="Get filtered hotels list",
     *     tags={"Hotels"},
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
     *         name="rating",
     *         in="path",
     *         description="Hotel rating 1-5",
     *         required=false,
     *         type="number",
     *     ),
     *     @SWG\Response(
     *         response=200,
     *         description="Hotel list",
     *         @SWG\Schema(
     *             type="array",
     *             @SWG\Items(ref="#/definitions/Hotel")
     *         ),
     *     ),
     *     @SWG\Response(
     *         response="401",
     *         description="Unauthorized user",
     *     )
     * )
     */
    public function hotels(HotelRequest $request)
    {
        if (Auth::user()) {
            $input = $request->all();

            $hotelsBuilder = Hotel::query();

            if (isset($input['rating'])) {
                $hotelsBuilder->where(['rating' => $input['rating']]);
            }

            $hotels = $hotelsBuilder->get()->toArray();

            return response()->json(['hotels' => $hotels], $this->successStatus);
        } else {
            return response()->json(['error' => 'Unauthorised'], $this->unauthorisedStatus);
        }
    }
}
