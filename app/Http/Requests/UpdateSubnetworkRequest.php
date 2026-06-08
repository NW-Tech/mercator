<?php

namespace App\Http\Requests;

use App\Rules\Cidr;
use Illuminate\Validation\Rule;

class UpdateSubnetworkRequest extends BaseFormRequest
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
                Rule::unique('subnetworks')
                    ->ignore($this->route('subnetwork')->id ?? $this->id)
                    ->whereNull('deleted_at'),
            ],
            'address' => [
                'nullable',
                new Cidr,
            ],
            'default_gateway' => [
                'nullable',
                'ip',
            ],
        ];
    }
}
