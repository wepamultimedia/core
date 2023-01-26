<?php

namespace Wepa\Core\Http\Requests\Backend;


use Illuminate\Foundation\Http\FormRequest;


/**
 * @property string $name
 * @property string $email
 * @property string $password
 * @property string $selectedRoles
 */
class UserCreateRequest extends FormRequest
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
		return [
			'name'                  => 'required|max:255',
			'email'                 => 'required|unique:users|max:255|email',
			'password'              => 'required|min:8|confirmed',
			'password_confirmation' => 'required|min:8',
			'selectedRoles'         => 'required',
		];
	}
}
