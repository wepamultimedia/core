<?php

namespace Wepa\Core\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Wepa\Core\Models\Menu;

class Backend
{
    /**
     * Handle an incoming request.
     *
     * @param \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse) $next
     * @return \Illuminate\Http\JsonResponse
     */
    public function handle(Request $request, Closure $next)
    {
        if(!auth()->user()->hasRole('admin') and !auth()->user()->hasRole('super.admin')){
            return response()->json(['message' => 'You are not authorized to access this page'], 403);
        }

        return $next($request);
    }
}
