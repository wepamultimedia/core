<?php

namespace Wepa\Core\Http\Requests\Backend;


use Illuminate\Foundation\Http\FormRequest;


/**
 * @property string $name
 * @property string $email
 * @property string $password
 * @property array $translations
 * @property string $selectedRoles
 */
class PermissionFormRequest extends FormRequest
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
	
	public function messages()
	{
		return [
			'translations.' . config('app.locale') . '.description.required' => __('core::backend/role.description_required_default_lang',
				['locale' => config('app.locale')]),
		];
	}
	
	/**
	 * Get the validation rules that apply to the request.
	 *
	 * @return array<string, mixed>
	 */
	public function rules()
	{
		switch($this->method()) {
			case 'POST':
				return [
					'name'                                                  => 'required|unique:permissions|max:255',
					'translations.' . config('app.locale') . '.description' => 'required|string',
				];
			case 'PUT':
				$id = $this->segment(3);
				
				return [
					'name'                                                  => 'required|unique:permissions,name,' . $id . '|max:255',
					'translations.' . config('app.locale') . '.description' => 'required|string',
				];
		}
	}
}
