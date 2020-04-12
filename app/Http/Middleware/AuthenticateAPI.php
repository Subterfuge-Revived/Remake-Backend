<?php

namespace App\Http\Middleware;

use App\Models\PlayerSession;
use Carbon\Carbon;
use Illuminate\Http\Request;

class AuthenticateAPI extends Authenticate
{
    /**
     * Handle an incoming request.
     *
     * @param Request $request
     * @param \Closure $next
     * @param array $guards
     * @return mixed
     */
    public function handle($request, \Closure $next, ...$guards)
    {
        $session = PlayerSession::findByToken($request->input('session_id') ?? '');

        if (!$session || !$session->isValid()) {
            return response('')->setStatusCode(401);
        }

        return $next($request);
    }

}
