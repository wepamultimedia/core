<?php

namespace Wepa\Core\Http\Middleware;


use Closure;
use Illuminate\Http\Request;


class HttpsMiddleware
{
	/**
	 * Handle an incoming request.
	 *
	 * @param Request $request
	 * @param Closure $next
	 *
	 * @return mixed
	 */
	public function handle(Request $request, Closure $next): mixed
	{
		if(!$request->secure() && app()->environment('production')) {
			return redirect()->secure($request->getRequestUri());
		}
		
		return $next($request);
	}
}
