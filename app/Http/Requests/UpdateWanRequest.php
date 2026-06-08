<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Http\Requests\BaseFormRequest;
use Illuminate\Validation\Rule;

class UpdateWanRequest extends BaseFormRequest
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
                Rule::unique('wans')
                    ->ignore($this->route('wan')->id ?? $this->id)
                    ->whereNull('deleted_at'),
            ],
            'mans' => [
                'array',
            ],
            'mans.*' => [
                'integer', 'exists:mans,id'
            ],
            'lans' => [
                'array',
            ],
            'lans.*' => [
                'integer', 'exists:lans,id'
            ],
        ];
    }
}
