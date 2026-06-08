<?php

namespace App\Http\Requests;


class UpdateLogicalFlowRequest extends BaseFormRequest
{
    protected array $htmlFields = ['description'];

    public function authorize(): bool
    {
        return $this->authorizeEdit();
    }

    public function rules(): array
    {
        return [
            'name' => [
                'min:0',
                'max:64',
            ],
            'priority' => [
                'integer',
                'nullable'
            ]
        ];
    }
}
