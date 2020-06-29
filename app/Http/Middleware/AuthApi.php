<?php

namespace App\Http\Middleware;

use App\Exceptions\User\UserNotFoundException;
use App\Models\User;
use Closure;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

/**
 * AuthApi Middleware
 * Prepare POST raw data
 */
class AuthApi
{

    /**
     * Handle an incoming request.
     *
     * @param Request $request Request
     * @param Closure $next Closure
     *
     * @return mixed
     *
     * @throws UserNotFoundException
     */
    public function handle(Request $request, Closure $next)
    {
        $token = str_replace(['Bearer', ' '], [''], $request->header('authorization'));

        if (!$token) {
            return redirect('/');
        }

        try {
            $user = User::where(['remember_token' => $token])->first();

            if($user) {
                Auth::loginUsingId($user->id, TRUE);
            } else {
                    return redirect('/');
            }
        } catch (ModelNotFoundException $exception) {
            return back()->withError($exception->getMessage())->withInput();
        }

        return $next($request);
    }
}
