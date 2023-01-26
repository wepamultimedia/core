<?php

namespace Wepa\Core\Http\Traits;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Wepa\Core\Http\Requests\RegisterRequest;


trait RegisterTrait
{
	/**
	 * @param RegisterRequest $request
	 *
	 * @return void
	 */
	public function register(RegisterRequest $request): void
	{
		$user = User::create([
			'name'     => $request->name,
			'email'    => $request->email,
			'password' => Hash::make($request->password),
		]);
		
		Auth::login($user);
	}
}
