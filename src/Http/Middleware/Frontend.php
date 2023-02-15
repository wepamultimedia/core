<?php

namespace Wepa\Core\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Wepa\Core\Models\Menu;

class Frontend
{
    /**
     * Handle an incoming request.
     *
     * @param \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        //		Inertia::share([
        //			'menu' => [],
        //		]);

        return $next($request);
    }
}
