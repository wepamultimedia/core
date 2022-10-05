<?php

namespace Wepa\Core\Http\Interfaces\Auth;


use Wepa\Core\Http\Requests\LoginRequest;


interface LoginInterface
{
	public function create();
	
	public function store(LoginRequest $request);
}