<?php

namespace App\Http\Middleware;

use App\Http\Responses\NotFoundResponse;
use Closure;
use Illuminate\Http\Request;

class ValidateNestedResources
{
    /**
     * Validate that the bound models from the URI template have a possessive relationship.
     *
     * @param Request $request
     * @param Closure $next
     * @param array $guards
     * @return string|null
     */
    public function handle($request, Closure $next, ...$guards)
    {
        $parent = null;
        $segments = $request->segments();

        // We will begin validating resources after the 'api' segment.
        $segmentOffset = array_search('api', $segments) + 1;
        $parameterIndex = -1;

        foreach ($request->route()->parameters() as $parameter) {
            $parameterIndex++;

            if (is_null($parent)) {
                $parent = $parameter;
                continue;
            }

            if (!$parent->{$segments[$segmentOffset + 2*$parameterIndex]}->contains($parameter)) {
                // The parent model does not have a relation to its child model.
                // Note that we assume that the relationship name to be used is
                // equal to the name of the segment used in the URI.
                return new NotFoundResponse();
            }

            $parent = $parameter;
        }

        return $next($request);
    }
}
