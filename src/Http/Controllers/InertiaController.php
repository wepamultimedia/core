<?php

namespace Wepa\Core\Http\Controllers;

use App\Http\Controllers\Controller;
use Wepa\Core\Http\Traits\InertiaControllerTrait;

class InertiaController extends Controller
{
    use InertiaControllerTrait;

    protected function beforeRender()
    {
    }
}
