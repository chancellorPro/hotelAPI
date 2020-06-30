<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Validator;

/**
 * Class UserController
 * @package App\Http\Controllers\API
 */
class UserController extends Controller
{

    /**
     * @SWG\Post(
     *     path="/api/login",
     *     summary="Get auth token",
     *     tags={"Login"},
     *     @SWG\Parameter(
     *         name="email",
     *         in="formData",
     *         description="Email",
     *         required=true,
     *         type="string",
     *         @SWG\Schema(),
     *     ),
     *     @SWG\Parameter(
     *         name="password",
     *         in="formData",
     *         description="Password",
     *         required=true,
     *         type="string",
     *         @SWG\Schema(),
     *     ),
     *     @SWG\Response(
     *         response=200,
     *         description="Room list",
     *         @SWG\Schema(
     *           @SWG\Property(
     *             property="success",
     *             type="object",
     *             @SWG\Property(
     *               property="token",
     *               type="string",
     *             ),
     *           )
     *          )
     *         ),
     *     ),
     * @SWG\Response(
     *         response="401",
     *         description="Unauthorized user",
     *     )
     * )
     */
    public function login()
    {
        if (Auth::attempt(['email'    => request('email'),
                           'password' => request('password')])) {
            $user = Auth::user();
            $success['token'] = $user->createToken('MyApp')->accessToken;
            $user->remember_token = $success['token'];
            $user->save();
            return response()->json(['success' => $success], $this->successStatus);
        } else {
            return response()->json(['error' => 'Unauthorised'], $this->unauthorisedStatus);
        }
    }

    /**
     * @SWG\Post(
     *     path="/api/logout",
     *     summary="Remove auth token",
     *     tags={"Logout"},
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
     *         description="Logout",
     *         @SWG\Property(
     *             property="message",
     *             type="string",
     *         ),
     *     )
     * )
     */
    public function logout()
    {
        if (Auth::check()) {
            $user = Auth::user();
            $user->remember_token = '';
            $user->save();
        }

        return response()->json(['message' => 'User logged out.'], $this->successStatus);
    }

    /**
     * @SWG\Post(
     *     path="/api/register",
     *     summary="Create new user",
     *     tags={"Register"},
     *     @SWG\Parameter(
     *         name="name",
     *         in="formData",
     *         description="Name",
     *         required=true,
     *         type="string",
     *         @SWG\Schema(),
     *     ),
     *     @SWG\Parameter(
     *         name="email",
     *         in="formData",
     *         description="Email",
     *         required=true,
     *         type="string",
     *         @SWG\Schema(),
     *     ),
     *     @SWG\Parameter(
     *         name="password",
     *         in="formData",
     *         description="Password",
     *         required=true,
     *         type="string",
     *         @SWG\Schema(),
     *     ),
     *     @SWG\Parameter(
     *         name="c_password",
     *         in="formData",
     *         description="Compare password",
     *         required=true,
     *         type="string",
     *         @SWG\Schema(),
     *     ),
     *     @SWG\Response(
     *         response=200,
     *         description="Register",
     *         @SWG\Schema(
     *             @SWG\Property(
     *                 property="success",
     *                 type="object",
     *                 @SWG\Property(
     *                   property="token",
     *                   type="string",
     *                 ),
     *                 @SWG\Property(
     *                   property="name",
     *                   type="string",
     *                 ),
     *             ),
     *         ),
     *     ),
     *     @SWG\Response(
     *         response="401",
     *         description="Email already registered | Validation error",
     *     )
     * )
     */
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name'       => 'required|unique:users,name',
            'email'      => 'required|email',
            'password'   => 'required',
            'c_password' => 'required|same:password'
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], $this->unauthorisedStatus);
        }

        $input = $request->all();

        $alreadyRegisteredEmail = User::where('email', $input['email'])->first();
        if ($alreadyRegisteredEmail) {
            return response()->json(['error' => 'Email already registered'], $this->unauthorisedStatus);
        }

        $input['password'] = bcrypt($input['password']);
        $user = User::create($input);
        $success['token'] = $user->createToken('MyApp')->accessToken;
        $success['name'] = $user->name;

        return response()->json(['success' => $success], $this->successStatus);
    }
}
