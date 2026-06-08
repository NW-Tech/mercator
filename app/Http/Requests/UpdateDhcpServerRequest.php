<?php

namespace App\Http\Requests;

use App\Rules\IPList;
use Illuminate\Validation\Rule;

class UpdateDhcpServerRequest extends BaseFormRequest
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
                Rule::unique('dhcp_servers')
                    ->ignore($this->route('dhcp_server')->id ?? $this->id)
                    ->whereNull('deleted_at'),
            ],
            'address_ip' => [
                'nullable',
                new IPList,
            ],
        ];
    }
}
