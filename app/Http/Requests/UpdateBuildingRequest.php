<?php

namespace App\Http\Requests;

use Illuminate\Validation\Rule;

class UpdateBuildingRequest extends BaseFormRequest
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
                'min:2',
                'max:32',
                'required',
                Rule::unique('buildings')
                    ->ignore($this->route('building')->id ?? $this->id)
                    ->whereNull('deleted_at'),
            ],
        ];
    }
}
