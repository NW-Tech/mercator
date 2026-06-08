<?php

namespace App\Http\Requests;

use Illuminate\Validation\Rule;

class UpdateMacroProcessusRequest extends BaseFormRequest
{
    protected array $htmlFields = ['description', 'io_elements'];

    public function authorize() : bool
    {
        return $this->authorizeEdit();
    }

    public function rules() : array
    {
        return [
            'name' => [
                'min:3',
                'max:64',
                'required',
                Rule::unique('macro_processuses')
                    ->ignore($this->route('macro_processus')->id ?? $this->id)
                    ->whereNull('deleted_at'),
            ],
            'security_need' => [
                'nullable',
                'integer',
                'min:0',
                'max:5',
            ],
            'processes.*' => [
                'integer',
            ],
            'processes' => [
                'array',
            ],
        ];
    }
}
