<?php

namespace Wepa\Core\Http\Controllers\Mixed;


use Illuminate\Auth\Events\Registered;
use Illuminate\Http\Request;
use Inertia\Response;
use Laravel\Fortify\Contracts\CreatesNewUsers;
use Laravel\Fortify\Contracts\RegisterResponse;
use Laravel\Fortify\Features;
use Wepa\Core\Http\Interfaces\Auth\RegisterInterface;
use Wepa\Core\Http\Traits\Auth\RegisterTrait;


class RegisterController extends InertiaController
{
	use RegisterTrait;
	
	
	public string $packageName = 'core';
	
	/**
	 * @return Response
	 */
	public function create(): Response
	{
		if (! Features::enabled(Features::registration())) {
			abort(404);
		}
		return $this->render('Core/Mixed/Auth/Register', 'auth');
	}
}