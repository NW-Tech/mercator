<?php

namespace App\Http\Requests;

use Illuminate\Validation\Rule;

class UpdateExternalConnectedEntityRequest extends BaseFormRequest
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
                'max:32',
                'required',
                Rule::unique('external_connected_entities')
                    ->ignore($this->route('external_connected_entity')->id ?? $this->id)
                    ->whereNull('deleted_at'),
            ],
            'src' => [
                'nullable',
                'ip',
            ],
        ];
    }
}
