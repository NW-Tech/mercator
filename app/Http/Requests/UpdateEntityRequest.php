<?php

namespace App\Http\Requests;

use Illuminate\Validation\Rule;

class UpdateEntityRequest extends BaseFormRequest
{
    protected array $htmlFields = ['description', 'security_level', 'contact_point'];

    public function authorize(): bool
    {
        return $this->authorizeEdit();
    }

    public function rules(): array
    {
        return [
            'name' => [
                'min:3',
                'max:64',
                'required',
                Rule::unique('entities')
                    ->ignore($this->route('entity')->id ?? $this->id)
                    ->whereNull('deleted_at'),
            ],
            'iconFile' => ['nullable', 'file', 'mimes:png', 'max:65535'],
            'seurity_level' => [
                'nullable',
                'integer',
                'min:0',
                'max:5',
            ],
        ];
    }
}
