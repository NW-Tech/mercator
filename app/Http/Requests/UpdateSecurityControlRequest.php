<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Http\Requests\BaseFormRequest;
use Illuminate\Validation\Rule;

class UpdateSecurityControlRequest extends BaseFormRequest
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
                'max:255',
                'required',
                Rule::unique('security_controls')
                    ->ignore($this->route('security_control')->id ?? $this->id)
                    ->whereNull('deleted_at'),
            ],
        ];
    }
}
