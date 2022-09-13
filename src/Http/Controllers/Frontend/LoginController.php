<?php

namespace Wepa\Core\Http\Controllers\Frontend;


use Illuminate\Http\RedirectResponse;
use Inertia\Response;
use Wepa\Core\Http\Controllers\Mixed\InertiaController;
use Wepa\Core\Http\Interfaces\Auth\LoginInterface;
use Wepa\Core\Http\Requests\LoginRequest;
use Wepa\Core\Http\Traits\Auth\LoginTrait;


class LoginController extends InertiaController implements LoginInterface
{
	use LoginTrait;
	
	public string $packageName = 'core';
	
	/**
	 * @return Response
	 */
	public function create(): Response
	{
		return $this->render('Vendor/Core/Mixed/Auth/Login', 'auth', [
			'canResetPassword' => true,
		]);
	}
	
	/**
	 * @param LoginRequest $request
	 *
	 * @return RedirectResponse
	 */
	public function store(LoginRequest $request): RedirectResponse
	{
		if($this->login($request)) {
			return redirect()->intended('dashboard');
		}
		
		return back()->withErrors([
			'email' => 'The provided credentials do not match our records.',
		])->onlyInput('email');
	}
}
