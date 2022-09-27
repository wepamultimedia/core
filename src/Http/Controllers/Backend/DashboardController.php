<?php

namespace Wepa\Core\Http\Controllers\Backend;


use Inertia\Response;
use Wepa\Core\Http\Controllers\Mixed\InertiaController;
use Wepa\Core\Models\Backend\Menu;


class DashboardController extends InertiaController
{
	public string $packageName = 'core';
	
	/**
	 * @return Response
	 */
	public function index(): Response
	{
		return $this->render('backend/Dashboard', ['auth', 'backend/dashboard']);
	}
}