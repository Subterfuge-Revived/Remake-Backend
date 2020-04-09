<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = RouteServiceProvider::HOME;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    /**
     * Log in a user and obtain an API token.
     *
     * @param Request $request
     * @return Response
     * @throws ValidationException
     */
    public function login(Request $request)
    {
        $this->clearLoginAttempts($request);

        if (Auth::attempt([
            'name' => $request->get('username'),
            'password' => $request->get('password'),
        ])) {
            $user = User::whereName($request->get('username'))->first();
            $token = $user->refresh_token();
            return new Response(['token' => $token]);
        }

        throw ValidationException::withMessages([
            'Authentication failed',
        ]);
    }
}
