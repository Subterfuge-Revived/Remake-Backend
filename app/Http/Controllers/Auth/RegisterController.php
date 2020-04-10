<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Player;
use App\Models\PlayerSession;
use App\Providers\RouteServiceProvider;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Auth\Events\Registered;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;
use mofodojodino\ProfanityFilter\Check as ProfanityCheck;

class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    use RegistersUsers;

    /**
     * Where to redirect users after registration.
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
        $this->middleware('guest');
    }

    /**
     * @param Request $request
     * @return Response|mixed
     * @throws ValidationException
     */
    public function register(Request $request)
    {
        $this->validator($request)->validate();

        $profanityCheck = new ProfanityCheck();
        if ($profanityCheck->hasProfanity($request->get('username'))) {
            throw ValidationException::withMessages([
                'Username contains profanity',
            ]);
        }

        list($token, $player) = $this->create($request->all());

        event(new Registered($player));

        $this->guard()->login($player);

        return response([
            'success' => true,
            'player' => [
                'id' => $player->id,
                'name' => $player->name,
            ],
            'token' => $token,
        ]);
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param Request $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(Request $data)
    {
        return Validator::make($data->all(), [
            'username' => ['required', 'string', 'between:4,255', Rule::unique('players', 'name')],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:players'],
            'password' => ['required', 'string', 'min:2'],
        ]);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param array $data
     * @return array
     */
    protected function create(array $data)
    {
        $token = \Str::random(80);
        $user = Player::create([
            'name' => $data['username'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
            'last_online_at' => Carbon::now(),
        ]);

        $user->player_sessions()->save(new PlayerSession([
            'token' => $token,
        ]));

        return [$token, $user];
    }
}
