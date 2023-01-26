<?php

namespace Wepa\Core\Http\Controllers\Frontend;


use Inertia\Response;
use Wepa\Core\Http\Controllers\InertiaController;


class DashboardController extends InertiaController
{
	public string $packageName = 'core';
	
	/**
	 * @return Response
	 */
	public function index(): Response
	{
		return $this->render('Dashboard', 'index');
	}
}
