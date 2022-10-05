<?php

namespace Wepa\Core\Http\Controllers\Mixed;


use Illuminate\Http\Request;
use Inertia\Response;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;


class ForgotPasswordController extends InertiaController
{
	public string $packageName = 'core';
	
	/**
	 * @return Response
	 * @throws ContainerExceptionInterface
	 * @throws NotFoundExceptionInterface
	 */
	public function create(): Response
	{
		$status = session()->get('status');
		return $this->render('Core/Mixed/Auth/ForgotPassword', 'auth', compact(['status']));
	}
}