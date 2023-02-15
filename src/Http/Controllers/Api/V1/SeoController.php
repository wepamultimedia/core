<?php

namespace Wepa\Core\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use Illuminate\Support\Str;

class SeoController extends Controller
{
    public function slug(string $text, string $locale = null): string
    {
        $language = $locale ?? app()->getLocale();

        return Str::slug($text, '-', $language);
    }
}
