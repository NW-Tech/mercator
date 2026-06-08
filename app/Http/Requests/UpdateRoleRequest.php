<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Http\Requests\BaseFormRequest;
use Illuminate\Validation\Rule;

class UpdateRoleRequest extends BaseFormRequest
{
    public function authorize(): bool
    {
        return $this->authorizeEdit();
    }

    public function rules()
    {
        return [
            'title' => [
                'min:3',
                'max:32',
                'required',
                Rule::unique('roles')
                    ->ignore($this->route('role')->id ?? $this->id)
                    ->whereNull('deleted_at'),
            ],
            'permissions.*' => [
                'integer',
            ],
            'permissions' => [
                'array',
            ],
        ];
    }
}
