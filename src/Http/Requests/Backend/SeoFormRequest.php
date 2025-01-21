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
class SeoFormRequest extends FormRequest
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
            'translations.*.title.required' => __('core::seo.title_required', ['locale' => $locale]),
            'translations.*.description.required' => __('core::seo.description_required', ['locale' => $locale]),
            'translations.*.slug_suffix.required' => __('core::seo.slug_required', ['locale' => $locale]),
            'translations.*.slug_suffix.slug' => __('core::seo.slug_invalid_format'),
            'translations.*.slug_suffix.unique' => __('core::seo.unique_slug'),
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
            $slugs = Str::of($value)->explode('/');

            foreach ($slugs as $slug) {
                if(Str::slug($slug, '-') !== $slug){
                    return false;
                }
            }

            return true;
        });

        $locale = app()->getLocale();

        if (!Arr::exists($this->input('translations'), $locale)) {
            return [
                'alias' => 'nullable|string|max:255|unique:core_seo',
                "translations.$locale.slug_suffix" => [
                    'required', 'slug',
                    Rule::unique('core_seo_translations', 'slug_suffix')->where(function ($query) {
                        if($this->input('slug_prefix')) {
                            return $query->whereJsonContains('slug_prefix', $this->input('slug_prefix'));
                        }
                        return null;
                    })
                ],
                "translations.$locale.slug_suffix" => 'required|slug|unique:core_seo_translations',
                "translations.$locale.title" => 'required|string|max:255',
                "translations.$locale.description" => 'required|string|max:255',
            ];
        }

        $rules = [
            'alias' => 'nullable|string|max:255|unique:core_seo',
            'controller' => 'nullable|string|max:255',
            'action' => 'nullable|string|max:255',

            'translations.*.slug_suffix' => [
                'required', 'slug',
                Rule::unique('core_seo_translations', 'slug_suffix')->where(function ($query) {
                    if($this->input('slug_prefix')) {
                        return $query->whereJsonContains('slug_prefix', $this->input('slug_prefix'));
                    }
                    return null;
                })
            ],
            'translations.*.slug_redirect' => 'nullable|slug',
            'translations.*.keyword' => 'nullable|string|unique:core_seo_translations',
            'translations.*.title' => 'required|string|max:255',
            'translations.*.description' => 'required|string|max:255',
        ];

        if ($this->alias === 'home') {
            $rules = array_merge($rules, [
                'translations.*.slug' => 'nullable|slug|unique:core_seo_translations',
            ]);
        }

        switch (request()->method) {
            case 'PUT':
                $rules = array_merge($rules, [
                    'alias' => [
                        'nullable', 'string', 'max:255',
                        Rule::unique('core_seo')->ignore($this['id'], 'id'),
                    ],
                    'translations.*.slug_suffix' => [
                        'required', 'slug',
                        Rule::unique('core_seo_translations', 'slug_suffix')->where(function ($query) {
                            if($this->input('slug_prefix')) {
                                return $query->whereJsonContains('slug_prefix', $this->input('slug_prefix'));
                            }
                            return null;
                        })->ignore($this->input('id'), 'seo_id'),
                    ],
                    'translations.*.keyword' => [
                        'nullable', 'string',
                        Rule::unique('core_seo_translations')->ignore($this->get('id'), 'seo_id'),
                    ],
                ]);

                if ($this->alias === 'home') {
                    $rules = array_merge($rules, [
                        'translations.*.slug_suffix' => [
                            'nullable',
                            'slug',
                            Rule::unique('core_seo_translations', 'slug_suffix')->where(function ($query) {
                                if($this->input('slug_prefix')) {
                                    return $query->whereJsonContains('slug_prefix', $this->input('slug_prefix'));
                                }
                                return null;
                            })->ignore($this->input('id'), 'seo_id'),
                        ],
                    ]);
                }
        }

        return $rules;
    }
}
