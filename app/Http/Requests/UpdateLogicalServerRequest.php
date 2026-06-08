<?php

namespace App\Http\Requests;

use App\Rules\IPList;
use Illuminate\Validation\Rule;

class UpdateLogicalServerRequest extends BaseFormRequest
{
    protected array $htmlFields = ['description', 'configuration'];

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
                Rule::unique('logical_servers')
                    ->ignore($this->route('logical_server')->id ?? $this->id)
                    ->whereNull('deleted_at'),
            ],
            'disk' => [
                'nullable',
                'integer',
                'min:0',
                'max:2147483647',
            ],
            'address_ip' => [
                'nullable',
                new IPList,
            ],
        ];
    }
}
