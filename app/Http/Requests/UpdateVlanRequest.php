<?php

namespace App\Http\Requests;

use Illuminate\Validation\Rule;

class UpdateVlanRequest extends BaseFormRequest
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
                Rule::unique('vlans')
                    ->ignore($this->route('vlan')->id ?? $this->id)
                    ->whereNull('deleted_at'),
            ],
        ];
    }
}
