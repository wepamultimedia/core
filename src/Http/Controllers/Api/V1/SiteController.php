<?php

namespace Wepa\Core\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use Wepa\Core\Models\Site;

class SiteController extends Controller
{
    public function site()
    {
        return cache()->remember('site', config('core.cache_ttl',  3600), function () {
            return Site::first();
        });
    }
}
