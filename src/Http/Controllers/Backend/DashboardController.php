<?php

namespace Wepa\Core\Http\Controllers\Backend;

use Inertia\Response;

class DashboardController extends InertiaController
{
    public string $packageName = 'core';

    public function index(): Response
    {
        return $this->render('Vendor/Core/Backend/Dashboard', ['auth', 'backend/dashboard']);
    }
}
