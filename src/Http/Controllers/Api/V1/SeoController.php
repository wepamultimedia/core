<?php

namespace Wepa\Core\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use Illuminate\Support\Str;
use Wepa\Core\Http\Resources\SeoResource;
use Wepa\Core\Models\Seo;

class SeoController extends Controller
{
    public function slug(string $text, string $locale = null): string
    {
        $language = $locale ?? app()->getLocale();

        return Str::slug($text, '-', $language);
    }

    public function byAlias(string $alias): SeoResource
    {
        return SeoResource::make(Seo::where('alias', $alias)->first());
    }
}
