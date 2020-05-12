<?php

namespace App\Http\Middleware;

use Illuminate\Http\Request;
use function GuzzleHttp\Psr7\parse_query;

class AddGetParametersToRequestBody
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
        if ($request->method() === 'GET') {
            $queryParameters = parse_query($request->getContent());
            foreach ($queryParameters as $key => $value) {
                $request->request->set($key, $value);
            }
        }

        return $next($request);
    }

}
