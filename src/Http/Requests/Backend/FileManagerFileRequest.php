<?php

namespace Wepa\Core\Http\Requests\Backend;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

/**
 * @property string $name
 * @property string $alt_name
 * @property string $description
 * @property string $file
 * @property string $parent_id
 */
class FileManagerFileRequest extends FormRequest
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
     * @return array
     */
    public function messages(): array
    {
        return collect(parent::messages())
            ->merge([
                'name.unique' => 'The name has already been taken.',
            ])
            ->toArray();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        $rules = [
            'alt_name' => 'string|required',
            'description' => 'string|nullable',
        ];

        switch(request()->method) {
            case 'POST':
                return array_merge($rules, [
                    'name' => [
                        'string',
                        'required',
                        Rule::unique('core_files')->where(function ($query) {
                            return $query->where('name', $this['name'])
                                ->where('parent_id', $this['parent_id']);
                        }),
                    ],
                    'file' => 'required|mimes:png,jpg,jpeg,pdf|max:4096',
                ]);
            case 'PUT':
                return array_merge($rules, [
                    'name' => [
                        'string',
                        'required',
                        Rule::unique('core_files')->where(function ($query) {
                            return $query->where('name', $this['name'])
                                ->where('parent_id', $this['parent_id']);
                        })->ignore($this['id']),
                    ],
                ]);
        }
    }
}
