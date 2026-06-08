<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Http\Requests\BaseFormRequest;
use Illuminate\Validation\Rule;

class UpdateManRequest extends BaseFormRequest
{
    public function authorize(): bool
    {
        return $this->authorizeEdit();
    }

    public function rules()
    {
        return [
            'name' => [
                'min:3',
                'max:32',
                'required',
                Rule::unique('mans')
                    ->ignore($this->route('man')->id ?? $this->id)
                    ->whereNull('deleted_at'),
            ],
            'lans.*' => [
                'integer',
            ],
            'lans' => [
                'array',
            ],
        ];
    }
}
