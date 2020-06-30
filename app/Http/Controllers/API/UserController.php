<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Validator;

class UserController extends Controller
{
    public $successStatus = 200;

    /**
     * @SWG\Post(
     *     path="/login",
     *     summary="Get auth token",
     *     tags={"Login"},
     *     @SWG\Parameter(
     *         name="email",
     *         in="body",
     *         description="Email",
     *         required=true,
     *         type="string",
     *         @SWG\Schema(),
     *     ),
     *     @SWG\Parameter(
     *         name="password",
     *         in="body",
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
     *     @SWG\Response(
     *         response="401",
     *         description="Unauthorized user",
     *     )
     * )
     */
    public function login()
    {
        if (Auth::attempt(['email' => request('email'), 'password' => request('password')])) {
            $user = Auth::user();
            $success['token'] = $user->createToken('MyApp')->accessToken;
            $user->remember_token = $success['token'];
            $user->save();
            return response()->json(['success' => $success], $this->successStatus);
        } else {
            return response()->json(['error' => 'Unauthorised'], 401);
        }
    }

    /**
     * @SWG\Post(
     *     path="/logout",
     *     summary="Remove auth token",
     *     tags={"Logout"},
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
    public function logout(Request $request)
    {
        if (Auth::check()) {
            $request->user()->token()->delete();
        }

        return response()->json(['message' => 'User logged out.'], 200);
    }

    /**
     * @SWG\Post(
     *     path="/register",
     *     summary="Create new user",
     *     tags={"Register"},
     *     @SWG\Parameter(
     *         name="name",
     *         in="body",
     *         description="Name",
     *         required=true,
     *         type="string",
     *         @SWG\Schema(),
     *     ),
     *     @SWG\Parameter(
     *         name="email",
     *         in="body",
     *         description="Email",
     *         required=true,
     *         type="string",
     *         @SWG\Schema(),
     *     ),
     *     @SWG\Parameter(
     *         name="password",
     *         in="body",
     *         description="Password",
     *         required=true,
     *         type="string",
     *         @SWG\Schema(),
     *     ),
     *     @SWG\Parameter(
     *         name="c_password",
     *         in="body",
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
            return response()->json(['error' => $validator->errors()], 401);
        }

        $input = $request->all();

        $alreadyRegisteredEmail = User::where('email', $input['email'])->first();
        if ($alreadyRegisteredEmail) {
            return response()->json(['error' => 'Email already registered'], 401);
        }

        $input['password'] = bcrypt($input['password']);
        $user = User::create($input);
        $success['token'] = $user->createToken('MyApp')->accessToken;
        $success['name'] = $user->name;

        return response()->json(['success' => $success], $this->successStatus);
    }
}
