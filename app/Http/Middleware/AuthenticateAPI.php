<?php

namespace App\Http\Middleware;

use App\Models\PlayerSession;
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
        $session = PlayerSession::whereToken(hash('sha256', $request->input('token')))->first();
        if (!$session || !$session->isValid()) {
            return response('')->setStatusCode(401);
        }

        return $next($request);
    }

}
