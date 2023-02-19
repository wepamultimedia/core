<?php

namespace Wepa\Core\Http\Controllers\Backend;

class TranslationController extends InertiaController
{
    public string $packageName = 'core';

    public function index()
    {
        return $this->render('Vendor/Core/Backend/Translation/Index');
    }
}
