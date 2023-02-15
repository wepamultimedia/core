<?php

namespace Wepa\Core\Http\Requests\Backend;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Validator;

/**
 * @property string $name
 * @property string $email
 * @property string $password
 * @property array $translations
 * @property string $selectedRoles
 */
class SeoFormRequest extends FormRequest
{
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
            'translations.*.title.required' => __('core::seo.title_required', ['locale' => $locale]),
            'translations.*.description.required' => __('core::seo.description_required',
                ['locale' => $locale]),
            'translations.*.slug.required' => __('core::seo.slug_required', ['locale' => $locale]),
            'translations.*.slug.slug' => __('core::seo.slug_invalid_format', ['locale' => $locale]),
            'translations.*.slug.unique' => __('core::seo.unique_slug'),
            'translations.*.keyword.unique' => __('core::seo.unique_keyword'),
        ];
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        $locale = app()->getLocale();

        Validator::extend('slug', function ($attribute, $value, $parameters, $validator) {
            return Str::slug($value, '-') === $value;
        });

        $rules = [
            'controller' => 'nullable|string|max:255',
            'action' => 'nullable|string|max:255',
            "translations.$locale.title" => 'required|string|max:255',
            "translations.$locale.description" => 'required|string|max:255',
            "translations.$locale.slug" => 'nullable|slug',
            'translations.*.title' => 'required|string|max:255',
            'translations.*.description' => 'required|string|max:255',
        ];

        switch(request()->method) {
            case 'POST':
                return array_merge($rules, [
                    'alias' => 'required|string|max:255',
                    'translations.*.slug' => 'unique:core_seo_translations',
                    'translations.*.keyword' => 'string|unique:core_seo_translations|nullable',
                ]);
            case 'PUT':
                return array_merge($rules, [
                    'alias' => 'nullable|string|max:255',
                    'translations.*.slug' => [
                        Rule::unique('core_seo_translations')->ignore($this['id'], 'seo_id'),
                    ],
                    'translations.*.keyword' => [
                        'string',
                        'nullable',
                        Rule::unique('core_seo_translations')->ignore($this['id'], 'seo_id'),
                    ],
                ]);
        }
    }
}
