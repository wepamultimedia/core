<?php

namespace Wepa\Core\Http\Requests\Backend;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Wepa\Core\Models\FileType;

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
     */
    public function authorize(): bool
    {
        return true;
    }

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
    public function rules(): array
    {
        $extension = $this->file('file')->extension();
        $isImage = ($extension === 'jpg' or $extension === 'jpeg' or $extension === 'png');

        $rules = [
            'alt_name' => ['string', 'nullable', Rule::when($isImage, ['required'])],
            'description' => 'string|nullable',
        ];

        $mimes = FileType::select(['extension'])
            ->whereNotNull('mime')
            ->where('extension', '<>', '.')
            ->get()
            ->map(function ($type) {
                return $type->extension;
            })->implode(',');

        switch(request()->method) {
            case 'POST':
                return array_merge($rules, [
                    'max_size' => [Rule::when($isImage, ['int'])],
                    'name' => [
                        'string',
                        'required',
                        Rule::unique('core_files')->where(function ($query) {
                            return $query->where('name', $this['name'])
                                ->where('parent_id', $this['parent_id']);
                        }),
                    ],
                    'file' => 'required|mimes:'.$mimes.'|max:4096',
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
