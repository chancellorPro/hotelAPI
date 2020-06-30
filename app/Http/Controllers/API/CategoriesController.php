<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\RoomCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

/**
 * Class CategoriesController
 * @package App\Http\Controllers\API
 */
class CategoriesController extends Controller
{
    /**
     * @SWG\Get(
     *     path="/categories",
     *     summary="Get categories list",
     *     tags={"Room categories"},
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
     *     @SWG\Response(
     *         response=200,
     *         description="Categories list",
     *         @SWG\Schema(
     *             type="array",
     *             @SWG\Items(ref="#/definitions/RoomCategory")
     *         ),
     *     ),
     *     @SWG\Response(
     *         response="401",
     *         description="Unauthorized user",
     *     )
     * )
     */
    public function categories(Request $request)
    {
        if (Auth::user()) {
            $categories = RoomCategory::query()->get()->toArray();

            return response()->json(['categories' => $categories], $this->successStatus);
        } else {
            return response()->json(['error' => 'Unauthorised'], $this->unauthorisedStatus);
        }
    }
}
