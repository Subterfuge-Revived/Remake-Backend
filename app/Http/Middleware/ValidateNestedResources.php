<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Auth\Middleware\Authenticate as Middleware;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class ValidateNestedResources extends Middleware
{
    /**
     * Get the path the user should be redirected to when they are not authenticated.
     *
     * @param Request $request
     * @param Closure $next
     * @param array $guards
     * @return string|null
     */
    public function handle($request, Closure $next, ...$guards)
    {
        $parentModel = null;
        foreach ($request->route()->parameters() as $parameter) {
            if (is_null($parentModel)) {
                $parentModel = $parameter;
                continue;
            }

            if (!$parentModel->{$parameter->getTable()}->contains($parameter)) {
                // The parent model does not have a relation to its child model.
                // Note that we assume that the relationship is defined on the
                // parent model and has the same name as the table name of the child.
                return new Response('Could not resolve resource', 500);
            }
        }

        return $next($request);
    }
}
