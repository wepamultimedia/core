<?php

namespace Wepa\Core\Http\Traits\Backend;

trait SeoRequestTrait
{
    protected function addSeoRules($rules): array
    {
        $locale = config('app.locale');

        return array_merge([
            "seo.translations.$locale.slug" => 'string|unique:core_seo_translations|nullable|max:255',
            "seo.translations.$locale.title" => 'string|nullable',
            "seo.translations.$locale.description" => 'string|nullable',
            "seo.translations.$locale.keyword" => 'string|unique:core_seo_translations|nullable',
        ], $rules);
    }
}
