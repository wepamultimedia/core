<?php

namespace Wepa\Core\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use Wepa\Core\Models\Site;

class SiteController extends Controller
{
    public function site()
    {
        return Site::first();
    }
}
