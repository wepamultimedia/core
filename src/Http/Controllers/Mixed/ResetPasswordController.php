<?php

namespace Wepa\Core\Http\Controllers\Mixed;

use Inertia\Response;


class ResetPasswordController extends InertiaController
{
	public string $packageName = 'core';
	
	/**
	 * @param $email
	 * @param $token
	 *
	 * @return Response
	 */
	public function create($email, $token): Response
	{
		return $this->render('Core/Mixed/Auth/ResetPassword', 'auth', ['token' => $token, 'email' => $email]);
	}
}