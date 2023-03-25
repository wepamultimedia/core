<?php

namespace Wepa\Core\Http\Requests\Backend;

use Illuminate\Foundation\Http\FormRequest;
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
        Validator::extend('slug', function ($attribute, $value) {
            return Str::slug($value, '-') === $value;
        });

        $locale = app()->getLocale();

        $rules = [
            'controller' => 'nullable|string|max:255',
            'action' => 'nullable|string|max:255',
            "translations.$locale.title" => 'required|string|max:255',
            "translations.$locale.description" => 'required|string|max:255',
            'translations.*.title' => 'required|string|max:255',
            'translations.*.description' => 'required|string|max:255',
            'alias' => 'nullable|string|max:255',
        ];
        switch(request()->method) {
            case 'POST':
                return array_merge($rules, [
                    'alias' => 'required|string|max:255|unique:core_seo',
                    'translations.*.slug' => 'nullable|slug|unique:core_seo_translations',
                    'translations.*.keyword' => 'string|unique:core_seo_translations|nullable',
                ]);
            case 'PUT':
                return array_merge($rules, [
                    'alias' => [
                        'required',
                        'string',
                        'max:255',
                        Rule::unique('core_seo')->ignore($this['id'], 'id'),
                    ],
                    'translations.*.slug' => [
                        'nullable',
                        'slug',
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
