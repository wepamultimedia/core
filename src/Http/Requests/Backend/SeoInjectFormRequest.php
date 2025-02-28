<?php

namespace Wepa\Core\Http\Requests\Backend;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Wepa\Core\Models\SeoTranslation;

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
            'seo.translations.*.description.required' => __('core::seo.description_required',  ['locale' => $locale]),
            'seo.translations.*.slug_suffix.required' => __('core::seo.slug_required', ['locale' => $locale]),
            'seo.translations.*.slug_suffix.slug' => __('core::seo.slug_invalid_format', ['locale' => $locale]),
            'seo.translations.*.slug_suffix.unique' => __('core::seo.unique_slug'),
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
        $isHome = $this->input('seo.alias') === 'home';
        $id = $this->input('seo.id');
        $defaultLocale = config('core.default_locale', 'es');

        Validator::extend('slug', function ($attribute, $value) {
            $slugs = Str::of($value)->explode('/');

            foreach ($slugs as $slug) {
                if(Str::slug($slug, '-') !== $slug){
                    return false;
                }
            }

            return true;
        });

        if (!Arr::exists($this->input('seo.translations'), $defaultLocale)) {
            return [
                'seo.alias' => [
                    'required',
                    'string',
                    'max:255',
                    Rule::unique('core_seo', 'alias')->ignore($id),
                ],
                "seo.translations.$defaultLocale.slug_suffix" => [
                    $isHome ? 'nullable' : 'required',
                    'slug',
                    Rule::unique('core_seo_translations', '>')->where(function ($query) use ($defaultLocale) {
                        if($this->input('seo.slug_prefix')) {
                            return $query->whereJsonContains('slug_prefix', $this->input('seo.slug_prefix'));
                        }
                        return null;
                    })
                ],
                "seo.translations.$defaultLocale.title" => 'required|string|max:255',
                "seo.translations.$defaultLocale.description" => 'required|string|max:255',
            ];
        }

        $rules = [
            'seo.alias' => [
                'nullable', 'string', 'max:255',
                Rule::unique('core_seo', 'alias')->ignore($this->input('seo.id')),
            ],
            'seo.controller' => 'nullable|string|max:255',
            'seo.action' => 'nullable|string|max:255',

            "seo.translations.*.slug_suffix" => [
                'required', 'slug',
                Rule::unique('core_seo_translations')->where(function ($query) {
                    if($this->input('seo.slug_prefix')) {
                        return $query->whereJsonContains('slug_prefix', $this->input('seo.slug_prefix'));
                    }
                    return null;
                })
            ],
            //'seo.translations.*.slug_suffix' => 'required|slug|unique:core_seo_translations',
            'seo.translations.*.slug_redirect' => 'nullable|slug',
            'seo.translations.*.keyword' => 'nullable|string|unique:core_seo_translations',

            'seo.translations.*.title' => 'required|string|max:255',
            'seo.translations.*.description' => 'required|string|max:255',
        ];

        if ($id) {
            $rules = array_merge($rules, [
                'seo.alias' => [
                    'nullable', 'string', 'max:255',
                    Rule::unique('core_seo', 'alias')->ignore($this->input('seo.id')),
                ],
                'seo.translations.*.slug_suffix' => [
                    'required', 'slug',
                    Rule::unique('core_seo_translations', 'slug_suffix')->where(function ($query) {
                        if($this->input('seo.slug_prefix')) {
                            return $query->whereJsonContains('slug_prefix', $this->input('seo.slug_prefix'));
                        }
                        return null;
                    })->ignore($this->input('seo.id'), 'seo_id'),
                ],
                'seo.translations.*.keyword' => [
                    'string',
                    'nullable',
                    Rule::unique('core_seo_translations', 'keyword')->ignore($this->input('seo.id'), 'seo_id'),
                ],
            ]);

            if ($this->input('seo.alias') === 'home') {
                $rules = array_merge($rules, [
                    'seo.translations.*.slug' => [
                        'nullable',
                        'slug',
                        Rule::unique('core_seo_translations', 'slug_suffix')->where(function ($query) {
                            if($this->input('seo.slug_prefix')) {
                                return $query->whereJsonContains('slug_prefix', $this->input('seo.slug_prefix'));
                            }
                            return null;
                        })->ignore($this->input('seo.id'), 'seo_id'),
                    ],
                ]);
            }
        }

        if ($isHome) {
            $rules = array_merge($rules, [
                'seo.translations.*.slug_suffix' => [
                    'nullable', 'slug',
                    Rule::unique('core_seo_translations', 'slug_suffix')->where(function ($query) {
                        if($this->input('seo.slug_prefix')) {
                            return $query->whereJsonContains('slug_prefix', $this->input('seo.slug_prefix'));
                        }
                        return null;
                    }),
                ],
            ]);
        }

        return $rules;
    }
}
