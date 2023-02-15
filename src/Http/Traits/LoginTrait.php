<?php

namespace Wepa\Core\Http\Traits;

use Illuminate\Support\Facades\Auth;
use Wepa\Core\Http\Requests\LoginRequest;

trait LoginTrait
{
    /**
     * @param  string  $redirect
     */
    public function login(LoginRequest $request): bool
    {
        if (Auth::attempt($request->only(['email', 'password']))) {
            $request->session()->regenerate();

            return true;
        }

        return false;
    }
}
