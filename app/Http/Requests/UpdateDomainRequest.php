<?php

namespace App\Http\Requests;

use Illuminate\Validation\Rule;

class UpdateDomainRequest extends BaseFormRequest
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
                Rule::unique('domains')
                    ->ignore($this->route('domain')->id ?? $this->id)
                    ->whereNull('deleted_at'),
            ],
            'domain_ctrl_cnt' => [
                'nullable',
                'integer',
                'min:0',
                'max:999999',
            ],
            'user_count' => [
                'nullable',
                'integer',
                'min:0',
                'max:999999',
            ],
            'machine_count' => [
                'nullable',
                'integer',
                'min:0',
                'max:999999',
            ],
        ];
    }
}
