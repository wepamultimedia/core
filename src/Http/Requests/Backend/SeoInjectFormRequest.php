<?php

namespace Wepa\Core\Http\Requests\Backend;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

/**
 * @property string $name
 * @property string $email
 * @property string $password
 * @property array $translations
 * @property string $selectedRoles
 */
class SeoInjectFormRequest extends FormRequest
{
    protected $errorBag = 'seo';

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    public function messages(): array
    {
        $locale = app()->getLocale();

        return [
            'seo.translations.*.title.required' => __('core::seo.title_required', ['locale' => $locale]),
            'seo.translations.*.description.required' => __('core::seo.description_required',
                ['locale' => $locale]),
            'seo.translations.*.slug.required' => __('core::seo.slug_required', ['locale' => $locale]),
            'seo.translations.*.slug.slug' => __('core::seo.slug_invalid_format', ['locale' => $locale]),
            'seo.translations.*.slug.unique' => __('core::seo.unique_slug'),
            'seo.translations.*.keyword.unique' => __('core::seo.unique_keyword'),
        ];
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        Validator::extend('slug', function ($attribute, $value) {
            return Str::slug($value, '-') === $value;
        });

        $locale = app()->getLocale();

        $rules = [
            'seo.controller' => 'nullable|string|max:255',
            'seo.action' => 'nullable|string|max:255',
            'seo.translations.*.title' => 'nullable|string|max:255',
            'seo.translations.*.description' => 'nullable|string|max:255',
            'seo.alias' => 'nullable|string|max:255',
        ];

        if (Arr::exists($this, 'seo_id')) {
            return array_merge($rules, [
                'seo.translations.*.slug' => [
                    'nullable',
                    'slug',
                    Rule::unique('core_seo_translations')->ignore($this['seo_id'], 'seo_id'),
                ],
                'seo.translations.*.keyword' => [
                    'string',
                    'nullable',
                    Rule::unique('core_seo_translations')->ignore($this['seo_id'], 'seo_id'),
                ],
            ]);
        } else {
            return array_merge($rules, [
                'seo.translations.*.slug' => [
                    'nullable',
                    'slug',
                    Rule::unique('core_seo_translations'),
                ],
                'seo.translations.*.keyword' => 'string|unique:core_seo_translations|nullable',
            ]);
        }
    }
}
