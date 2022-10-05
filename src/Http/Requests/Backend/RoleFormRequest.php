<?php

namespace Wepa\Core\Http\Requests\Backend;


use Illuminate\Foundation\Http\FormRequest;


/**
 * @property string $name
 * @property string $email
 * @property string $password
 * @property string selectedPermissions
 */
class RoleFormRequest extends FormRequest
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
					'name'                => 'required|unique:roles|max:255',
					'selectedPermissions' => 'required',
				];
			case 'PUT':
				$id = $this->segment(3);
				return [
					'name'                => 'required|unique:roles,name,' . $id . '|max:255',
					'selectedPermissions' => 'required',
				];
		}
	}
}
