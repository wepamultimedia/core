<?php

namespace Wepa\Core\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use Inertia\Inertia;


class AuthController extends Controller
{
    public function login()
    {
		return Inertia::render('Index');
    }
}
