<?php

namespace Wepa\Core\Http\Requests\Backend;


use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Arr;
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
			"seo.translations.*.title.required" => __('core::seo.title_required', ['locale' => $locale]),
			"seo.translations.*.description.required" => __('core::seo.description_required',
				['locale' => $locale]),
			"seo.translations.*.slug.required" => __('core::seo.slug_required', ['locale' => $locale]),
			"seo.translations.*.slug.slug" => __('core::seo.slug_invalid_format', ['locale' => $locale]),
			"seo.translations.*.slug.unique" => __('core::seo.unique_slug'),
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
		
		Validator::extend('slug', function($attribute, $value, $parameters, $validator) {
			return Str::slug($value, '-') === $value;
		});
		
		$rules = [
			"seo.controller" => 'nullable|string|max:255',
			"seo.action" => 'nullable|string|max:255',
			"seo.translations.$locale.title" => 'required|string|max:255',
			"seo.translations.$locale.description" => 'required|string|max:255',
			"seo.translations.$locale.slug" => 'nullable|slug',
			'seo.translations.*.title' => 'required|string|max:255',
			'seo.translations.*.description' => 'required|string|max:255',
		];
		
		if(Arr::exists($this, 'seo_id')) {
			return array_merge($rules, [
				"alias" => 'nullable|string|max:255',
				'seo.translations.*.slug' => [
					Rule::requiredIf($this['seo']['alias'] !== 'home'),
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
				"alias" => 'string|max:255',
				'seo.translations.*.slug' => 'required|unique:core_seo_translations',
				'seo.translations.*.keyword' => 'string|unique:core_seo_translations|nullable',
			]);
		}
	}
}
