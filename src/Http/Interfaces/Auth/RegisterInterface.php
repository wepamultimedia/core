<?php

namespace Wepa\Core\Http\Interfaces\Auth;


use Wepa\Core\Http\Requests\RegisterRequest;


interface RegisterInterface
{
	public function create();
	
	public function store(RegisterRequest $request);
}