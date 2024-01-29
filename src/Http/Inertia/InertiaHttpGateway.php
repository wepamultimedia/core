<?php

namespace Wepa\Core\Http\Inertia;

use Inertia\Ssr\HttpGateway;
use Inertia\Ssr\Response;
use Illuminate\Support\Str;

class InertiaHttpGateway extends HttpGateway
{
    /**
     * Dispatch the Inertia page to the Server Side Rendering engine.
     *
     * @param array $page
     * @return Response|null
     */
    public function dispatch(array $page): ?Response
    {
        if (isset($page['url']) && Str::is('/admin*', $page['url'])) {
            return null;
        }

        return parent::dispatch($page);
    }
}
