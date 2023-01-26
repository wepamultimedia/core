<?php

namespace Wepa\Core\Http\Requests;


use Illuminate\Foundation\Http\FormRequest;


/**
 * @property string $email
 * @property string $password
 */
class LoginRequest extends FormRequest
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
			'email'    => 'required|max:255|email',
			'password' => 'required|min:8',
		];
	}
}
