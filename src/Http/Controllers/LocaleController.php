<?php

namespace Wepa\Core\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class LocaleController extends \App\Http\Controllers\Controller
{
    public function switchLocale(Request $request, string $locale): RedirectResponse
    {
        $exist = false;
        foreach (config('core.locales') as $l) {
            if ($locale === $l['code']) {
                $exist = true;
                break;
            }
        }

        if ($exist) {
            $request->session()->put('applocale', $locale);
            $request->session()->flash('switchLocale');
        }

        return redirect()->back();
    }
}
