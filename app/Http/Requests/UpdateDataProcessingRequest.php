<?php

namespace App\Http\Requests;

use Illuminate\Validation\Rule;

class UpdateDataProcessingRequest extends BaseFormRequest
{
    protected array $htmlFields = ['description', 'responsible', 'purpose', 'lawfulness', 'categories', 'recipients', 'transfert', 'retention'];

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
                Rule::unique('data_processing')
                    ->ignore($this->route('data_processing')->id ?? $this->id)
                    ->whereNull('deleted_at'),
            ],
            'processes.*' => [
                'integer',
            ],
            'processes' => [
                'array',
            ],
            'applications.*' => [
                'integer',
            ],
            'applications' => [
                'array',
            ],
            'informations.*' => [
                'integer',
            ],
            'informations' => [
                'array',
            ],
        ];
    }
}
