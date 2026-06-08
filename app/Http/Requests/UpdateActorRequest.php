<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Http\Requests\BaseFormRequest;
use Illuminate\Validation\Rule;

class UpdateActorRequest extends BaseFormRequest
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
                'max:128',
                'required',
                Rule::unique('actors')
                    ->ignore($this->route('actor')->id ?? $this->id)
                    ->whereNull('deleted_at'),
            ],
        ];
    }
}
