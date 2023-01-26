<?php

namespace Wepa\Core\Http\Requests\Backend;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Arr;
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

    /**
     * @return array
     */
    public function messages(): array
    {
        $locale = app()->getLocale();

        return [
            'seo.image.required' => __('core::seo.image_required'),
            'seo.translations.*.title.required' => __('core::seo.title_required', ['locale' => $locale]),
            'seo.translations.*.description.required' => __('core::seo.description_required',
                ['locale' => $locale]),
            'seo.translations.*.slug.required' => __('core::seo.slug_required', ['locale' => $locale]),
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
        $locale = app()->getLocale();

        $rules = [
            'seo.image' => 'required|string|max:255',
            'seo.controller' => 'required|string|max:255',
            'seo.action' => 'required|string|max:255',
            "seo.translations.$locale.title" => 'required|string|max:255',
            "seo.translations.$locale.description" => 'required|string|max:255',
            "seo.translations.$locale.slug" => 'required',
            'seo.translations.*.title' => 'required|string|max:255',
            'seo.translations.*.description' => 'required|string|max:255',

        ];

        if (Arr::exists($this, 'seo_id')) {
            return array_merge($rules, [
                'seo.translations.*.slug' => [
                    'required',
                    Rule::unique('core_seo_translations')->ignore($this['seo_id'], 'seo_id'),
                ],
                'seo.translations.*.keyword' => [
                    'string',
                    'nullable',
                    Rule::unique('core_seo_translations')->ignore($this['seo_id'], 'seo_id'),
                ],
                'seo.cononical' => Rule::unique('core_seo')->where(function ($query) {
                    return $query->where('controller', $this['controller'])
                        ->where('action', $this['action'])
                        ->where('canonical', $this['canonical']);
                })->ignore($this['seo_id'], 'seo_id'),
            ]);
        } else {
            return array_merge($rules, [
                'seo.translations.*.slug' => 'required|unique:core_seo_translations',
                'seo.translations.*.keyword' => 'string|unique:core_seo_translations|nullable',
                'seo.cononical' => Rule::unique('core_seo')->where(function ($query) {
                    return $query->where('controller', $this['controller'])
                        ->where('action', $this['action'])
                        ->where('canonical', $this['canonical']);
                }),
            ]);
        }
    }
}
