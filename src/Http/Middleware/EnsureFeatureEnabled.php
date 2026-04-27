<?php

namespace Imran\LaravelRuntimeFeature\Http\Middleware;

use Imran\LaravelRuntimeFeature\Facades\Feature;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureFeatureEnabled
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     * @param  string  $feature
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function handle(Request $request, Closure $next, string $feature): Response
    {
        if (!Feature::enabled($feature)) {
            abort(404);
        }

        return $next($request);
    }
}
