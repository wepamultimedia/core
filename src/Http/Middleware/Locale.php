<?php

namespace Wepa\Core\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;

class Locale
{
    /**
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function handle(Request $request, Closure $next): mixed
    {
        if ($request->session()->has('applocale')) {
            app()->setLocale(session()->get('applocale'));
        }

        return $next($request);
    }
}
