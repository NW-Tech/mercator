<?php

namespace App\Http\Requests;

use Illuminate\Validation\Rule;

class UpdateApplicationBlockRequest extends BaseFormRequest
{
    protected array $htmlFields = ['description'];

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
                Rule::unique('application_blocks')
                    ->ignore($this->route('application_block')->id ?? $this->id)
                    ->whereNull('deleted_at'),
            ],
        ];
    }
}
