<?php

namespace Wepa\Core\Http\Controllers\Backend;

use Illuminate\Http\Request;
use Inertia\Response;
use Laravel\Fortify\Features;
use Wepa\Core\Http\Traits\InertiaControllerTrait;

class UserProfileController extends \Laravel\Jetstream\Http\Controllers\Inertia\UserProfileController
{
    use InertiaControllerTrait;

    public function __construct()
    {
        $this->packageName = 'core';
    }

    public function show(Request $request): Response
    {
        $this->validateTwoFactorAuthenticationState($request);

        return $this->jetrender($request,
            'Vendor/Core/Backend/User/Profile/Show',
            'backend/user',
            [
                'confirmsTwoFactorAuthentication' => Features::optionEnabled(Features::twoFactorAuthentication(),
                    'confirm'),
                'sessions' => $this->sessions($request)->all(),
            ]);
    }
}
