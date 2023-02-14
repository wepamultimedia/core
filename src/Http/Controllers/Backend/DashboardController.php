<?php

namespace Wepa\Core\Http\Controllers\Backend;

use Inertia\Response;

class DashboardController extends InertiaController
{
    public string $packageName = 'core';

    /**
     * @return Response
     */
    public function index(): Response
    {
        return $this->render('Core/Backend/Dashboard', ['auth', 'backend/dashboard']);
    }
}
