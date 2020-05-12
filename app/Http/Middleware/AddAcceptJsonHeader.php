<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class AddAcceptJsonHeader
{
    /**
     * The URIs that should be excluded from having an Accept header injected.
     *
     * @var array
     */
    protected $except = [
        //
    ];

    /**
     * @param Request $request
     * @param Closure $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $request->headers->add(['Accept' => 'application/json']);
        return $next($request);
    }
}
