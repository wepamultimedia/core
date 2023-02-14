<?php

namespace Wepa\Core\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;

class Locale
{
    /**
     * @param  Request  $request
     * @param  Closure  $next
     * @return mixed
     *
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function handle(Request $request, Closure $next): mixed
    {
        if ($request->session()->has('applocale')) {
            app()->setLocale(session()->get('applocale'));
        } else {
            $locales = config('core.locales', []);
            $user_locale = preg_split('/,|;/', $request->server('HTTP_ACCEPT_LANGUAGE'));
            $user_iso = preg_replace('/-/', '_', $user_locale[0]);
            foreach ($locales as $locale) {
                if ($locale['iso'] === $user_iso) {
                    app()->setLocale($locale['code']);
                    $request->session()->put('applocale', $locale['code']);
                    break;
                }
            }
        }

        return $next($request);
    }
}
